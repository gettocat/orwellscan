<h3>Last blocks (<?php echo ($data['pager']['count'])?>)</h3>


<div class="row">
    <div class="col-1 col-xs-2 text-center">
        <b>Height</b>
    </div>

    <div class="col-7 col-xs-6">
        <b> Block</b>
    </div>
    <div class="col-2 text-center">
        <b>Size / Difficulty </b>
    </div>


    <div class="col-2 text-center">
        <b>Date</b>
    </div>


</div>

<?php foreach ($list['list'] as $i => $v): ?>

    <div class="row block">

        <div class="col-1  col-xs-2  text-center">
            <a target="_blank" href="/height/<?php echo $v['height'] ?>"><?php echo $v['height'] ?></a>
        </div>

        <div class="col-7 col-xs-6">
            <a target="_blank" href="/block/<?php echo $v['hash'] ?>"><?php echo truncate($v['hash'], 1, 8) ?></a><br />
            output <?php echo number_format($v['output']) ?>  
        </div>

        <div class="col-2 text-center">
            <?php echo sprintf("%.2f", $v['size'] / 1024) ?> / 
            <?php echo number_format($v['diff']) ?>
        </div>

        <div class="col-2 text-center">
            <?php echo time_since(time() - $v['time']) ?> ago
        </div>


    </div>

<?php endforeach; ?>

<?php if ($data['pager']['pages'] > 1): ?>
    <ul class="pagination pagination-lg  justify-content-center">
        <?php if ($data['pager']['page'] > 1): ?>
            <li class="page-item"><a class="page-link" href="<?php echo $data['pager']['path'] ?>page=<?php echo $data['pager']['page'] - 1 ?>">Prev</a></li>
        <?php endif; ?>

        <?php for ($i = $data['pager']['nearLeft']; $i <= $data['pager']['nearRight']; $i++): ?>
            <?php if ($i == $data['pager']['page']): ?>
                <li class="page-item active">
                    <a class="page-link" href="#"><?php echo $i ?> <span class="sr-only">(current)</span></a>
                </li>
            <?php else: ?>
                <li class="page-item"><a class="page-link" href="<?php echo $data['pager']['path'] ?>page=<?php echo $i ?>"><?php echo $i ?></a></li>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($data['pager']['page'] < $data['pager']['pages']): ?>
            <li class="page-item"><a class="page-link" href="<?php echo $data['pager']['path'] ?>page=<?php echo $data['pager']['page'] + 1 ?>">Next</a></li>
            <?php endif; ?>
    </ul>
<?php endif; ?>
