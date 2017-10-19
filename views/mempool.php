<h3>Memory pool (<?php echo count($list)?>)</h3>

<table class="table table-bordered table-striped table-responsive">
    <tr>
        <th>Tx</th>
        <th>Recived</th>
    </tr>
    <?php foreach ($list as $v): ?>
        <tr>
            <td>
                <a href='/tx/<?php echo $v['hash'] ?>'><?php echo $v['hash'] ?></a>
            </td>
            <td>
                <?php echo time_since(time()-$v['time'])?> ago / <?php echo date("d.m.Y H:i",$v['time'])?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
