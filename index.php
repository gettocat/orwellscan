<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

include 'vendor/autoload.php';
include 'inc.php';

use JsonRPC\Client;

Flight ::set('flight.log_errors', false);
Flight:: set('flight.handle_errors', false);

//+caching
Flight::route('/', function() {
    Flight::view()->set('title', 'Orwell p2p network block explorer - ' . Config::$title);

    $client = Flight::get('rpc');
    $onpage = Config::$onpage;
    $page = intval($_GET['page']);
    if (!$page)
        $page = 1;

    try {

        if ($_GET['q']) {
            $res = cache("search-" . $_GET['q'], function() use($client) {
                try {
                    $hash = $_GET['q'];

                    if (preg_match("#^[0-9]+$#ims", $hash)) {//is index
                        $result = $client->execute('height', array($hash));
                        if ($result[0]['hash'])
                            return ("/block/{$result[0]['hash']}");
                    }

                    if (!preg_match("/^([0-9a-z]{1,150})$/ims", $hash))
                        return false;


                    //try to block
                    $result = $client->execute('block', array($hash));
                    if ($result[0]['hash'])
                        return ("/block/{$result[0]['hash']}");

                    //try to tx
                    $result = $client->execute('printtx', array($hash));
                    if ($result['hash'])
                        return ("/tx/{$result['hash']}");
                    //try address
                    $result = $client->execute('address', array($hash));
                    if (!($result['error']['error']))
                        return ("/address/{$result['address']}");
                } catch (Exception $e) {
                    return "/";
                }
            });

            if ($res)
                Flight::redirect($res);
            else
                return Flight::renderTemplate('error', array(
                            'error' => 'Invalid query',
                ));
        }

        $offset = $onpage * ($page - 1);

        $top = Flight::top();
        $f = 'cache';

        if ($top['hash'] != memget("listcachedtop"))
            $f = 'cacheNo';

        $result = $f("blocks-$page", function() use($client, $onpage, $offset) {
            $res = $client->execute('chain', array($onpage, $offset));
            $top = $res['list'][0]['hash'];
            memset("listcachedtop", $top);
            return $res;
        });

        $items = $result['count'];
        $pages = ceil($items / $onpage);

        if ($page > $pages)
            $page = 1;
    } catch (Exception $e) {
        Flight::renderTemplate('error', array(
            'error' => $e->getMessage(),
        ));
    }

    Flight::renderTemplate('index', array(
        'list' => $result,
        'pager' => array(
            'path' => '?',
            'count' => $items,
            'pages' => $pages,
            'page' => $page,
            'onpage' => $onpage,
            'nearLeft' => (($page - 2) < 1) ? 1 : $page - 2,
            'nearRight' => ($page + 2 > $pages) ? $pages : $page + 2,
        ),
    ));
});

Flight::route('/block/@hash', function($hash) {
    Flight::view()->set('title', 'Explore block ' . $hash . ' at orwell network - ' . Config::$title);

    try {
        if (!preg_match("/^([0-9a-z]{64})$/ims", $hash))
            throw new Exception('Invalid hash');

        $client = Flight::get('rpc');
        $block = cache("block$hash", function() use($client, $hash) {
            return $client->execute('block', array($hash));
        });

        if (!count($block))
            throw new Exception("Block with hash $hash not finded");

        Flight::renderTemplate('block', array(
            'block' => $block[0],
        ));
    } catch (Exception $e) {
        Flight::renderTemplate('error', array(
            'error' => $e->getMessage(),
        ));
    }
});

Flight::route('/tx/@hash', function($hash) {
    Flight::view()->set('title', 'Explore transaction ' . $hash . ' at orwell network - ' . Config::$title);

    try {
        if (!preg_match("/^([0-9a-z]{64})$/ims", $hash))
            throw new Exception('Invalid hash');

        $client = Flight::get('rpc');
        $tx = cache("tx$hash", function() use($client, $hash) {
            return $client->execute('printtx', array($hash, 1));
        });

        if (!count($tx))
            throw new Exception("Tx with hash $hash not finded");

        Flight::renderTemplate('tx', array(
            'tx' => $tx,
        ));
    } catch (Exception $e) {
        Flight::renderTemplate('error', array(
            'error' => $e->getMessage(),
        ));
    }
});

Flight::route('/height/@height', function($height) {
    Flight::view()->set('title', 'Explore block at height ' . $height . ' in orwell network - ' . Config::$title);

    try {
        if (!preg_match("/^[0-9]+$/ims", $height))
            throw new Exception('Invalid height');

        $client = Flight::get('rpc');
        $block = cache("height$height", function() use($client, $height) {
            return $client->execute('height', array($height));
        });

        if (!count($block))
            throw new Exception("Block with at height $height not finded");

        Flight::renderTemplate('block', array(
            'block' => $block[0],
        ));
    } catch (Exception $e) {
        Flight::renderTemplate('error', array(
            'error' => $e->getMessage(),
        ));
    }
});

Flight::route('/address/@addr', function($address) {
    Flight::view()->set('title', 'Explore address ' . $address . ' in orwell network - ' . Config::$title);

    try {
        if (!preg_match("/^[a-z0-9]{10,130}$/ims", $address))//for hash too
            throw new Exception('Invalid address');

        $onpage = Config::$onpage;
        $page = intval($_GET['page']);
        if (!$page)
            $page = 1;

        $offset = $onpage * ($page - 1);
        $client = Flight::get('rpc');
        $info = cache("height$address-$page", function() use($client, $address, $onpage, $offset) {
            return $client->execute('address', array($address, $onpage, $offset));
        });

        if ($info['error'])
            throw new Exception($info['error']['error']);

        $items = $info['unspent']['count'];
        $pages = ceil($items / $onpage);

        if ($page > $pages)
            $page = 1;

        $info['pager'] = array(
            'path' => '?',
            'count' => $info['unspent']['items'],
            'pages' => $pages,
            'page' => $page,
            'onpage' => $onpage,
            'nearLeft' => (($page - 2) < 1) ? 1 : $page - 2,
            'nearRight' => ($page + 2 > $pages) ? $pages : $page + 2,
        );

        Flight::renderTemplate('address', array(
            'addr' => $info,
        ));
    } catch (Exception $e) {
        Flight::renderTemplate('error', array(
            'error' => $e->getMessage(),
        ));
    }
});

Flight::route('/db(/@addr)(/@dataset)', function($db, $dataset) {
    Flight::view()->set('title', 'Explore p2p database ' . $db . ($dataset ? '/' . $dataset : '') . ' in orwell network - ' . Config::$title);

    try {
        if (!preg_match("/^[a-z0-9]{10,130}$/ims", $db))//for hash too
            throw new Exception('Invalid db address');

        $onpage = Config::$onpage;
        $page = intval($_GET['page']);
        if (!$page)
            $page = 1;

        $offset = $onpage * ($page - 1);
        $client = Flight::get('rpc');

        if ($dataset)
            $options = array($db, $dataset, $onpage, $offset);
        else
            $options = array($db);

        $info = cache("db$db-$dataset-$page", function() use($client, $options) {
            return $client->execute('dbinfo', $options);
        });

        if ($info['error'])
            throw new Exception($info['error']['error']);

        $items = $info['count'];
        $pages = ceil($items / $onpage);

        if ($page > $pages)
            $page = 1;

        $info['pager'] = array(
            'path' => '?',
            'count' => $info['items'],
            'pages' => $pages,
            'page' => $page,
            'onpage' => $onpage,
            'nearLeft' => (($page - 2) < 1) ? 1 : $page - 2,
            'nearRight' => ($page + 2 > $pages) ? $pages : $page + 2,
        );

        Flight::renderTemplate('db', array(
            'address' => $db,
            'dataset' => $dataset,
            'db' => $info,
        ));
    } catch (Exception $e) {
        Flight::renderTemplate('error', array(
            'error' => $e->getMessage(),
        ));
    }
});

Flight::route('/databases', function() {
    Flight::view()->set('title', 'Explore p2p databases in orwell network - ' . Config::$title);

    try {

        $onpage = Config::$onpage;
        $page = intval($_GET['page']);
        if (!$page)
            $page = 1;

        $offset = $onpage * ($page - 1);
        $client = Flight::get('rpc');
        $info = cache("dblist-$page", function() use($client, $onpage, $offset) {
            return $client->execute('dblist', array($onpage, $offset));
        });

        if ($info['error'])
            throw new Exception($info['error']['error']);

        $items = $info['count'];
        $pages = ceil($items / $onpage);

        if ($page > $pages)
            $page = 1;

        $info['pager'] = array(
            'path' => '?',
            'count' => $info['count'],
            'pages' => $pages,
            'page' => $page,
            'onpage' => $onpage,
            'nearLeft' => (($page - 2) < 1) ? 1 : $page - 2,
            'nearRight' => ($page + 2 > $pages) ? $pages : $page + 2,
        );

        Flight::renderTemplate('dblist', array(
            'db' => $info,
        ));
    } catch (Exception $e) {
        Flight::renderTemplate('error', array(
            'error' => $e->getMessage(),
        ));
    }
});

Flight::route('/nodes', function() {
    Flight::view()->set('title', 'Explore nodes in orwell network - ' . Config::$title);

    try {


        $client = Flight::get('rpc');
        $info = cache("nodes", function() use($client) {
            return $client->execute('peerinfo');
        }, 300);

        if ($info['error'])
            throw new Exception($info['error']['error']);

        Flight::renderTemplate('nodes', array(
            'nodes' => $info,
        ));
    } catch (Exception $e) {
        Flight::renderTemplate('error', array(
            'error' => $e->getMessage(),
        ));
    }
});

Flight::route('/mempool', function() {
    Flight::view()->set('title', 'Explore mempool in orwell network - ' . Config::$title);

    try {
        //$client = Flight::get('rpc');
        throw new Exception("Not implemented yet");
    } catch (Exception $e) {
        Flight::renderTemplate('error', array(
            'error' => $e->getMessage(),
        ));
    }
});

Flight::map('renderTemplate', function($template, $data) {
    Flight::render($template, $data, 'content');
    Flight::render('layout', $data);
});

Flight::set('rpc', new Client('http://' . Config::$nodeRpcHost . ':' . Config::$nodeRpcPort));
Flight::map('top', function() {

    if (!Flight::has('tophash')) {
        $top = cache("besttophash", function() {
            return Flight::get('rpc')->execute('bestblockhash');
        }, 5 * 60);
        Flight::set("tophash", $top);
    }

    return Flight::get('tophash');
});
Flight::start();

function pd($var) {
    ob_start();
    var_dump($var);
    $v = ob_get_clean();
    $v = highlight_string("<?\n" . $v . '?>', true);
    $v = preg_replace('/=&gt;\s*<br\s*\/>\s*(&nbsp;)+/i', '=&gt;' . "\t" . '&nbsp;', $v);
    $v = '<div style="margin-bottom:5px;padding:10px;background-color:#fcfab6;border:1px solid #cc0000;">' . $v . '</div>';
    return $v;
}

function d() {
    $arr = func_get_args();
    foreach ($arr as $var) {
        echo pd($var);
    }
}

function dd() {
    $arr = func_get_args();
    call_user_func_array("d", $arr);
    die;
}
