<div class="block-info">
    <h3>Block info</h3>
    <table class="table table-bordered table-striped table-responsive">
        <tr>
            <th>Name</th>
            <th>Value</th>
        </tr>
        <tr>
            <td>Version</td>
            <td><?php echo $block['version'] ?></td>
        </tr>
        <tr>
            <td>Hash</td>
            <td><?php echo $block['hash'] ?></td>
        </tr>
        <tr>
            <td>Confirmation</td>
            <td><?php echo $block['confirmation'] ?></td>
        </tr>
        <tr>
            <td>Prev block</td>
            <td><a href="/block/<?php echo $block['prev'] ?>"><?php echo $block['prev'] ?></a></td>
        </tr>
        <?php if ($block['next_block']): ?>
            <tr>
                <td>Next block</td>
                <td><a href="/block/<?php echo $block['next_block'] ?>"><?php echo $block['next_block'] ?></a></td>
            </tr>
        <?php endif ?>
        <tr>
            <td>Merkle root</td>
            <td><?php echo $block['merkle'] ?></td>
        </tr>
        <tr>
            <td>Block time</td>
            <td><?php echo time_since(time() - $block['time']) ?> ago
                / <?php echo date("d.m.Y H:i", $block['time']) ?></td>
        </tr>
        <tr>
            <td>Bits</td>
            <td><?php echo "0x" . dechex($block['bits']) ?></td>
        </tr>
        <tr>
            <td>Difficulty</td>
            <td><?php echo number_format($block['bits']) ?></td>
        </tr>
        <tr>
            <td>Size(kB)</td>
            <td><?php echo sprintf("%.9f", $block['size'] / 1024) ?></td>
        </tr>
        <tr>
            <td>Height</td>
            <td><a href="/height/<?php echo $block['height'] ?>"><?php echo $block['height'] ?></a></td>
        </tr>
        <tr>
            <td>Nonce</td>
            <td><?php echo $block['nonce'] ?></td>
        </tr>
        <tr>
            <td>Block reward</td>
            <td><?php echo $block['reward'] ?> orwl</td>
        </tr>
    </table>

    <h3>Coinbase</h3>
    <a name='coinbase'></a>
    <?php $coinbase = $block['tx'][0] ?>
    <table class="table table-bordered table-striped table-responsive">
        <tr>
            <th>Name</th>
            <th>Value</th>
        </tr>
        <tr>
            <td>Hash</td>
            <td><a href="/tx/<?php echo $coinbase['hash'] ?>"><?php echo $coinbase['hash'] ?></a></td>
        </tr>
        <tr>
            <td>Block author</td>
            <td>
                <a href="/address/<?php echo $coinbase['k'] ?>"><?php echo $coinbase['k'] ?></a>
            </td>
        </tr>
        <tr>
            <td>Amount</td>
            <?php
            $coinbaseamount = 0;
            foreach ($coinbase['out'] as $o) {
                $coinbaseamount += $o['amount'] / 1e8;
            }
            ?>
            <td><?php echo sprintf("%.9f", $block['reward']) ?> (<?php echo $coinbaseamount ?>
                + <?php echo sprintf("%.9f", $block['reward'] - $coinbaseamount) ?>)
            </td>
        </tr>
        <tr>
            <td>Mined By</td>
            <td>
                <a href="/address/<?php echo $coinbase['out'][$block['nonce']]['address'] ?>"><?php echo $coinbase['out'][$block['nonce']]['address'] ?></a>
            </td>
        </tr>

        <?php if ($coinbase['coinbaseData']['authorName']): ?>
            <tr>
                <td>Coinbase author</td>
                <td><?php echo $coinbase['coinbaseData']['authorName'] ?></td>
            </tr>
        <?php endif ?>

        <?php if ($coinbase['coinbaseData']['hardwareName']): ?>
            <tr>
                <td>Coinbase hardware/software</td>
                <td><?php echo $coinbase['coinbaseData']['hardwareName'] ?></td>
            </tr>
        <?php endif ?>

        <?php if ($coinbase['coinbaseData']['time']): ?>
            <tr>
                <td>Coinbase date</td>
                <td><?php echo time_since(time() - $coinbase['coinbaseData']['time']) ?> ago
                    / <?php echo date("d.m.Y H:i", $coinbase['coinbaseData']['time']) ?></td>
            </tr>
        <?php endif ?>

        <?php if (count($coinbase['coinbaseData']['bytes'])): ?>
            <tr>
                <td>Coinbase signal flags</td>
                <td>
                    <?php if (count($coinbase['coinbaseData']['bytes'])): ?>
                        <table class="table table-bordered">
                            <tr>
                                <?php foreach ($coinbase['coinbaseData']['bytes'] as $k => $v): ?>
                                    <td><?php echo $k + 1 ?></td>
                                <?php endforeach ?>
                            </tr>
                            <tr>
                                <?php foreach ($coinbase['coinbaseData']['bytes'] as $v): ?>
                                    <td><?php echo $v ?></td>
                                <?php endforeach ?>
                            </tr>
                        </table>
                    <?php endif ?>
                </td>
            </tr>
        <?php endif ?>

    </table>

    <h3>Tx list</h3>
    <table class="table table-bordered txlist table-responsive">
        <tr>
            <th>Tx</th>
            <th class='text-center'>In</th>
            <th class='text-center'>Out</th>
            <th class='text-center'>Fee</th>
            <th class='text-center'>Size</th>
        </tr>
        <?php $cls = '' ?>
        <?php foreach ($block['tx'] as $i => $tx): ?>
            <tr class='<?php echo $i % 2 == 0 ? 'tx-even' : '' ?>'>


                <td>
                    <a name='<?php echo $tx['hash'] ?>'></a>
                    <a href="/tx/<?php echo $tx['hash'] ?>"><?php echo $tx['hash'] ?></a>
                </td>
                <td class='text-center'><?php echo $tx['in_amount'] ?></td>
                <td class='text-center'><?php echo $tx['out_amount'] ?></td>
                <td class='text-center'><?php echo sprintf("%.9f", $tx['fee'] / 1e8) ?></td>
                <td class='text-center'><?php echo round($tx['size'] / 1024, 2) ?></td>

            </tr>

            <tr class='<?php echo $i % 2 == 0 ? 'tx-even' : '' ?>'>
                <td colspan="5">
                    <div class="row">

                        <div class="col text-center">

                            <?php if ($tx['cb']): ?>
                                <a href='#coinbase'>coinbase</a>
                            <?php else: ?>
                                <?php foreach ($tx['in'] as $in): ?>
                                    <div class='row'>
                                        <div class='col-sm-8'>
                                            <a href='/address/<?php echo $in['writerAddress'] ?>'><?php echo $in['writerAddress'] ?></a>
                                        </div>
                                        <div class='col-sm-4'>
                                            <a href='/tx/<?php echo $in['hash'] ?>?out=<?php echo $in['index'] ?>'><?php echo truncate($in['hash']) ?>
                                                ... out <<?php echo $in['index'] ?>></a>
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
                                <div class='row'>
                                    <div class='col-sm-8'>
                                        <a href='/address/<?php echo $out['address'] ?>'><?php echo $out['address'] ?></a>
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

</div>