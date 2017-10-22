<script type='text/javascript'>

    window.onload = function () {
        $('#rawdatatab a').click(function (e) {
            e.preventDefault();
            $(".tab-pane").removeClass('active show')
            $("#" + $(this).data('tab')).addClass('active show')
            $(this).tab('show')
        })
    }

</script>

<div class="tx-info">
    <h3>Tx info</h3>
    <table class="table table-bordered table-striped table-responsive">
        <tr>
            <th>Name</th>
            <th>Value</th>
        </tr>
        <tr>
            <td>Version</td>
            <td><?php echo $tx['version'] ?></td>
        </tr>
        <tr>
            <td>Size</td>
            <td><?php echo sprintf("%.2f", $tx['size'] / 1024) ?></td>
        </tr>

        <?php if ($tx['fromBlock']): ?>
            <tr>
                <td>Block</td>
                <td><a href='/block/<?php echo $tx['fromBlock'] ?>'><?php echo $tx['fromBlock'] ?></a> tx[<?php echo $tx['fromIndex'] ?>]</td>
            </tr>
        <?php else: ?>
            <tr>
                <td>Block</td>
                <td><b>Not in blockchain now (recived from memory pool)</b></td>
            </tr>
        <?php endif ?>

        <tr>
            <td>Confirmation</td>
            <td><?php echo $tx['confirmation'] ?></td>
        </tr>

        <tr>
            <td>Common input</td>
            <td><?php echo sprintf("%.9f", $tx['commonInput']) ?></td>
        </tr>

        <tr>
            <td>Common output</td>
            <td><?php echo sprintf("%.9f", $tx['commonOut']) ?></td>
        </tr>

        <tr>
            <td>Fee</td>
            <td><?php echo sprintf("%.9f", $tx['fee'] / 1e8) ?></td>
        </tr>
    </table>

    <?php
    $list = array($tx);
    $transaction = $tx;
    ?>
    <h3>In / Out</h3>
    <table class="table table-bordered txlist table-responsive">
        <tr>
            <th>Tx</th>
            <th class='text-center'>In</th>
            <th class='text-center'>Out</th>
            <th class='text-center'>Fee</th>
            <th class='text-center'>Size</th>
        </tr>
        <?php $cls = '' ?>
        <?php foreach ($list as $i => $tx): ?>
            <tr class='<?php echo $i % 2 == 0 ? 'tx-even' : '' ?>'>


                <td>
                    <a name='#<?php echo $tx['hash'] ?>'></a>
                    <a href="/tx/<?php echo $tx['hash'] ?>"><?php echo $tx['hash'] ?></a>
                </td>
                <td class='text-center'><?php echo $tx['in_count'] ?></td>
                <td class='text-center'><?php echo $tx['out_count'] ?></td>
                <td class='text-center'><?php echo sprintf("%.9f", $tx['fee'] / 1e8) ?></td>
                <td class='text-center'><?php echo round($tx['size'] / 1024, 2) ?></td>

            </tr>

            <tr class='<?php echo $i % 2 == 0 ? 'tx-even' : '' ?>'>
                <td colspan="5">
                    <div class="row">

                        <div class="col text-center">

                            <?php if ($tx['coinbase']): ?>
                                <a href='/block/<?php echo $tx['fromBlock'] ?>#coinbase'>coinbase</a>
                            <?php else: ?>
                                <?php foreach ($tx['in'] as $in): ?>
                                    <div class='row'>
                                        <div class='col-sm-8'>
                                            <a href='/address/<?php echo $in['prevAddress'] ?>'><?php echo $in['prevAddress'] ?></a> 
                                        </div>
                                        <div class='col-sm-4'>
                                            <a href='/tx/<?php echo $in['hash'] ?>?out=<?php echo $in['index'] ?>'><?php echo truncate($in['hash']) ?>... out <<?php echo $in['index'] ?>></a>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            <?php endif ?>

                        </div>
                        <div class='col-sm-1 col-xs-2'>
                            <i class='fa fa-arrow-right fa-2x'></i>
                        </div>
                        <div class="col text-center">


                            <?php foreach ($tx['out'] as $k => $out): ?>
                                <a name='#<?php echo $tx['hash'] ?>-<?php echo $k ?>'></a>
                                <div class='row <?php if (isset($_GET['out']) && $_GET['out'] == $k) echo 'selected' ?>'>
                                    <div class='col-sm-8'>
                                        <a href='/address/<?php echo $out['addr'] ?>'><?php echo $out['addr'] ?></a> 
                                    </div>
                                    <div class='col-sm-4'>
                                        <?php echo sprintf("%.9f", $out['amount'] / 1e8) ?>
                                    </div>
                                </div>
                            <?php endforeach ?>

                        </div>
                        <div class="w-100"></div>

                    </div>
                </td>
            </tr>


        <?php endforeach ?>
    </table>

    <?php $tx = $transaction ?>
    <h3>Datascript</h3>
    <table class='table table-bordered table-hover table-responsive'>

        <tr>
            <td>Db</td>
            <td>Writer</td>
            <td>Dataset</td>
        </tr>
        <?php foreach ($tx['dataScriptContent'] as $i => $d): ?>

            <tr class='<?php echo $i % 2 == 0 ? 'tx-even' : '' ?>'>
                <td>
                    <a href='/address/<?php echo $tx['out'][0]['addr'] ?>'><?php echo $tx['out'][0]['addr'] ?></a><br />
                    <a href='#' onclick='$(".content<?php echo $i ?>").toggleClass("hide"); return false;'>Show datascript content</a>
                </td>
                <td><a title='<?php echo $tx['in'][0]['publicKey'] ?>' href='/address/<?php echo $tx['in'][0]['fromAddress'] ?>'><?php echo truncate($tx['in'][0]['publicKey'], true) ?></a></td>
                <td>
                    <?php echo $d['dataset'] ?><br />
                    <span class='text-muted'><?php echo $d['operator'] ?></span>
                </td>
            </tr>
            <tr class='content<?php echo $i ?> hide'>
                <td style='overflow-x: overlay' colspan="3" class='<?php echo $i % 2 == 0 ? 'tx-even' : '' ?>'>
                    <?php if ($d['content']): ?>
                        <pre><?php echo htmlentities(json_encode($d['content'], JSON_PRETTY_PRINT)) ?></pre>
                    <?php else: ?>
                        < <?php echo $d['algorithm']?$d['algorithm']:'rsa'?> encrypted content >
                    <?php endif ?>
                </td>
            </tr>
        <?php endforeach ?>
    </table>

    <h3>Rawdata</h3>

    <nav id='rawdatatab' class="nav nav-pills nav-fill" role="tablist">
        <a data-tab='wr' class="nav-item nav-link active" role="tab" href="#">Writers (Public Keys)</a>
        <a data-tab='sig' class="nav-item nav-link" role="tab" href="#">Signatures</a>
        <a data-tab='scr' class="nav-item nav-link" role="tab" href="#">ScriptSig`s list</a>
        <a data-tab='ds' class="nav-item nav-link" role="tab" href="#">Datascript</a>
        <a data-tab='tx' class="nav-item nav-link" role="tab" href="#">Tx hex</a>
    </nav>


    <div class='pad'>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="wr" role="tabpanel" aria-labelledby="home-tab">

                <?php foreach ($tx['in'] as $i => $in): ?>
                    <h5>Input[<?php echo $i ?>]</h5>
                    <pre><?php echo $in['publicKey'] ?></pre>
                <?php endforeach ?>

            </div>
            <div class="tab-pane fade" id="sig" role="tabpanel" aria-labelledby="profile-tab">

                <?php foreach ($tx['in'] as $i => $in): ?>
                    <h5>Input[<?php echo $i ?>]</h5>
                    <textarea readonly="" class='form-control' rows='10'><?php echo $in['der'] ?></textarea>
                <?php endforeach ?>

            </div>
            <div class="tab-pane fade" id="scr" role="tabpanel" aria-labelledby="dropdown1-tab">

                <?php foreach ($tx['in'] as $i => $in): ?>
                    <h5>Input[<?php echo $i ?>]</h5>
                    <textarea readonly="" class='form-control' rows='10'><?php echo $in['scriptSig'] ?></textarea>
                <?php endforeach ?>

            </div>
            <div class="tab-pane fade" id="ds" role="tabpanel" aria-labelledby="dropdown2-tab">

                <h5>Datascript array</h5>
                <textarea readonly="" class='form-control' rows='10'><?php echo $tx['datascript'] ?></textarea>

                <?php foreach ($tx['dslist'] as $i=>$d): ?>
                    <h5>Datascript[<?php echo $i ?>]</h5>
                    <textarea readonly="" class='form-control' rows='10'><?php echo $d ?></textarea>
                <?php endforeach ?>

            </div>
            <div class="tab-pane fade" id="tx" role="tabpanel" aria-labelledby="dropdown2-tab">

                <textarea readonly="" class='form-control' rows='10'><?php echo $tx['hex'] ?></textarea>

            </div>
        </div>
    </div>



</div>