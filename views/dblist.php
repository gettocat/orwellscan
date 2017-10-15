<h3>Orwell Databases (<?php echo ($db['pager']['count'])?>)</h3>

<table class="table table-bordered table-striped table-responsive">
    <tr>
        <th>Name</th>
        <th>~Records</th>
    </tr>
    <?php foreach ($db['list'] as $v): ?>
        <tr>
            <td>
                <a href='/db/<?php echo $v['name'] ?>'><?php echo $v['name'] ?></a>
            </td>
            <td><?php echo number_format($v['records']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php if ($db['pager']['pages'] > 1): ?>
    <ul class="pagination pagination-lg  justify-content-center">
        <?php if ($db['pager']['page'] > 1): ?>
            <li class="page-item"><a class="page-link" href="<?php echo $db['pager']['path'] ?>page=<?php echo $db['pager']['page'] - 1 ?>">Prev</a></li>
        <?php endif; ?>

        <?php for ($i = $db['pager']['nearLeft']; $i <= $db['pager']['nearRight']; $i++): ?>
            <?php if ($i == $db['pager']['page']): ?>
                <li class="page-item active">
                    <a class="page-link" href="#"><?php echo $i ?> <span class="sr-only">(current)</span></a>
                </li>
            <?php else: ?>
                <li class="page-item"><a class="page-link" href="<?php echo $db['pager']['path'] ?>page=<?php echo $i ?>"><?php echo $i ?></a></li>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($db['pager']['page'] < $db['pager']['pages']): ?>
            <li class="page-item"><a class="page-link" href="<?php echo $db['pager']['path'] ?>page=<?php echo $db['pager']['page'] + 1 ?>">Next</a></li>
            <?php endif; ?>
    </ul>
<?php endif; ?>