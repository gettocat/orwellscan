<h3>Creation record</h3>
<pre><?php echo htmlentities(json_encode($data['db']['creation'], JSON_PRETTY_PRINT)) ?></pre>

<h3>Actual settings record</h3>
<pre><?php echo htmlentities(json_encode($data['db']['actualSettings'], JSON_PRETTY_PRINT)) ?></pre>


<h3>Records list</h3>
<pre><?php echo htmlentities(json_encode($data['db']['list'], JSON_PRETTY_PRINT)) ?></pre>

<?php if ($data['db']['pager']['pages'] > 1): ?>
    <ul class="pagination pagination-lg  justify-content-center">
        <?php if ($data['db']['pager']['page'] > 1): ?>
            <li class="page-item"><a class="page-link"
                                     href="<?php echo $data['db']['pager']['path'] ?>page=<?php echo $data['db']['pager']['page'] - 1 ?>">Prev</a>
            </li>
        <?php endif; ?>

        <?php for ($i = $data['db']['pager']['nearLeft']; $i <= $data['db']['pager']['nearRight']; $i++): ?>
            <?php if ($i == $data['db']['pager']['page']): ?>
                <li class="page-item active">
                    <a class="page-link" href="#"><?php echo $i ?> <span class="sr-only">(current)</span></a>
                </li>
            <?php else: ?>
                <li class="page-item"><a class="page-link"
                                         href="<?php echo $data['db']['pager']['path'] ?>page=<?php echo $i ?>"><?php echo $i ?></a>
                </li>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($data['db']['pager']['page'] < $data['db']['pager']['pages']): ?>
            <li class="page-item"><a class="page-link"
                                     href="<?php echo $data['db']['pager']['path'] ?>page=<?php echo $data['db']['pager']['page'] + 1 ?>">Next</a>
            </li>
        <?php endif; ?>
    </ul>
<?php endif; ?>