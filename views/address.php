
<div class="tx-info">
    <h3>Address info</h3>
    <table class="table table-bordered table-striped table-responsive">
        <tr>
            <th>Name</th>
            <th>Value</th>
        </tr>
        <tr>
            <td>Address</td>
            <td><?php echo $addr['address'] ?></td>
        </tr>
        <tr>
            <td>Hash 160</td>
            <td><a href='/address/<?php echo $addr['hash160'] ?>'><?php echo $addr['hash160'] ?></a></td>
        </tr>
        <?php
        $txcount = ($addr['unspent']['count']);
        $spnt_cnt = 0;
        $spent = 0;
        $unspent = 0;
        foreach ($addr['unspent']['list'] as $v): {
                if (!$v['spent'] && !$v['spentHash'])
                    $unspent+=$v['amount'] / 10e8;
                else {
                    $spent += $v['amount'] / 10e8;
                    $spnt_cnt+=1;
                }
            }
        endforeach
        ?>
        <tr>
            <td>Tx coint</td>
            <td><?php echo $txcount ?></td>
        </tr>

        <tr>
            <td>Unspent inputs</td>
            <td><?php echo $addr['unspent']['stats']['unspent_inputs'] ?></td>
        </tr>

        <tr>
            <td>Spent inputs</td>
            <td><?php echo ($addr['unspent']['stats']['spent_inputs']) ?></td>
        </tr>

        <tr>
            <td>Unspent balance</td>
            <td><?php echo number_format($addr['unspent']['stats']['unspent_amount'] / 10e8, 9) ?></td>
        </tr>
        <tr>
            <td>Spent balance</td>
            <td><?php echo number_format($addr['unspent']['stats']['spent_amount'] / 10e8, 9) ?></td>
        </tr>
        <tr>
            <td>Db list</td>
            <td><a href='/db/<?php echo $addr['address'] ?>'>It can be a db</a></td>
        </tr>

    </table>

    <?php
    $list = $addr['unspent']['list'];
    ?>
    <h3>Transactions (<?php echo ($addr['pager']['count']) ?>)</h3>
    <table class="table table-bordered txlist table-responsive">
        <tr>
            <th>Tx from</th>
            <th class='text-center'>Can spent</th>
            <th>Tx to</th>
            <th class='text-center'>Amount</th>
        </tr>
        <?php $cls = '' ?>
        <?php foreach ($list as $i => $data): ?>
            <tr class='<?php echo $i % 2 == 0 ? 'tx-even' : '' ?>'>
                <td>
                    <a href="/tx/<?php echo $data['tx'] ?>?out=<?php echo $data['index'] ?>"><?php echo truncate($data['tx'], true) ?></a> [<?php echo $data['index'] ?>]
                </td>
                <td class='text-center'><?php $canSpent = !$data['spent'] && !$data['spentHash']; ?>
                    <i class='fa fa-2x <?php
                    if ($canSpent)
                        echo 'fa-check green';
                    else
                        echo 'fa-times red'
                        ?>'></i>
                    <br />
                    <span class=''><?php
                        if ($canSpent)
                            echo "unspent";
                        else
                            echo "spent"
                            ?></span>
                </td>
                <td><?php if ($data['spentHash']): ?><a href='/tx/<?php echo $data['spentHash'] ?>'><?php echo truncate($data['spentHash'], true) ?></a><?php endif ?></td>
                <td class='text-center'><?php echo sprintf("%.9f", $data['amount'] / 1e8) ?></td>

            </tr>


        <?php endforeach ?>
    </table>

    <?php if ($addr['pager']['pages'] > 1): ?>
        <ul class="pagination pagination-lg  justify-content-center">
            <?php if ($data['pager']['page'] > 1): ?>
                <li class="page-item"><a class="page-link" href="<?php echo $addr['pager']['path'] ?>page=<?php echo $addr['pager']['page'] - 1 ?>">Prev</a></li>
            <?php endif; ?>

            <?php for ($i = $addr['pager']['nearLeft']; $i <= $addr['pager']['nearRight']; $i++): ?>
                <?php if ($i == $addr['pager']['page']): ?>
                    <li class="page-item active">
                        <a class="page-link" href="#"><?php echo $i ?> <span class="sr-only">(current)</span></a>
                    </li>
                <?php else: ?>
                    <li class="page-item"><a class="page-link" href="<?php echo $addr['pager']['path'] ?>page=<?php echo $i ?>"><?php echo $i ?></a></li>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($addr['pager']['page'] < $addr['pager']['pages']): ?>
                <li class="page-item"><a class="page-link" href="<?php echo $addr['pager']['path'] ?>page=<?php echo $addr['pager']['page'] + 1 ?>">Next</a></li>
                <?php endif; ?>
        </ul>
    <?php endif; ?>


</div>