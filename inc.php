<?php

Class Config {

    public static $error_reporting = 0;
    public static $display_errors = 0;
    public static $title = 'Orwellscan';
    public static $nodeRpcHost = '127.0.0.1';
    public static $nodeRpcPort = 41991;
    public static $sitename = 'orwellscan';
    public static $memcache_host = 'localhost';
    public static $memcache_port = 11211;
    public static $onpage = 100;

}

if ($_SERVER['HTTP_HOST'] == 'orwellscan') {//debug on local machine
	Config::$nodeRpcHost = 'host.docker.internal';
}	

function time_since($since) {
    $chunks = array(
        array(60 * 60 * 24 * 365, 'year'),
        array(60 * 60 * 24 * 30, 'month'),
        array(60 * 60 * 24 * 7, 'week'),
        array(60 * 60 * 24, 'day'),
        array(60 * 60, 'hour'),
        array(60, 'minute'),
        array(1, 'second')
    );

    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
        $seconds = $chunks[$i][0];
        $name = $chunks[$i][1];
        if (($count = floor($since / $seconds)) != 0) {
            break;
        }
    }

    $print = ($count == 1) ? '1 ' . $name : "$count {$name}s";
    return $print;
}

function cache($id, $data, $exp = 3600) {
    if ($d = memget($id))
        return $d;

    $d = $data($id);
    memset($id, $d, $exp);
    return $d;
}

function cacheNo($id, $data) {
    return $data($id);
}

function memset($key, $val, $exp = 3600) {
    if (class_exists('Memcache')) {
        if (!Flight::has('memcache')) {
            Flight::set('memcache', new Memcache());
            Flight::get('memcache')->connect(Config::$memcache_host, Config::$memcache_port);
        }

        return Flight::get('memcache')->set(Config::$sitename . "_" . $key, $val, false, $exp);
    }

    return false;
}

function memget($key) {
    if (class_exists('Memcache')) {
        if (!Flight::has('memcache')) {
            Flight::set('memcache', new Memcache());
            Flight::get('memcache')->connect(Config::$memcache_host, Config::$memcache_port);
        }

        return Flight::get('memcache')->get(Config::$sitename . "_" . $key);
    }

    return false;
}

function memfree($key = '') {
    if (class_exists('Memcache')) {
        if (!Flight::has('memcache')) {
            Flight::set('memcache', new Memcache());
            Flight::get('memcache')->connect(Config::$memcache_host, Config::$memcache_port);
        }

        if ($key)
            return Flight::get('memcache')->delete(Config::$sitename . "_" . $key);
        else
            return Flight::get('memcache')->flush();
    }

    return false;
}

function truncate($hash, $both = false, $cnt = 5) {
    if (!$both)
        return substr($hash, 0, $cnt);

    return substr($hash, 0, $cnt) . "..." . substr($hash, -1 * $cnt);
}

function ziphash($hash) {

    $buff = "";
    $size = strlen($hash);
    for ($i = 0; $i < $size; $i++) {
        if ($i > 0 && $i % 16 == 0)
            $buff.="<br/>";
        $buff.=$hash{$i};
    }

    return $buff;
}

function ipinfo($ip) {

    return cache("ip-$ip", function() use($ip) {
        $data = json_decode(file_get_contents("http://freegeoip.net/json/$ip"), 1);
        $data['icon'] = '/assets/ico/flags/' . strtolower($data['country_code']) . '.png';
        $data['title'] = $data['country_name'] ." ({$data['country_code']}) {$data['region_name']} {$data['city']}";
        return $data;
    }, 31 * 24 * 3600);
}
