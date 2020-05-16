<script type='text/javascript'>

    $(function () {
        $('.ask').popover({
            trigger: 'click'
        })
    })

</script>

<div class="tx-info">
    <h3>Db info</h3>
    <table class="table table-bordered table-striped">
        <tr>
            <th>Name</th>
            <th>Value</th>
        </tr>
        <tr>
            <td>Address</td>
            <td><?php echo $db['address'] ?></td>
        </tr>
        <tr>
            <td>Hash 160</td>
            <td><a href='/address/<?php echo $db['hash160'] ?>'><?php echo $db['hash160'] ?></a></td>
        </tr>

        <?php if (!$db['dataset']): ?>
            <tr>
                <td>Dataset count</td>
                <td><?php echo count($db['list']) ?></td>
            </tr>
        <?php endif ?>

        <tr>
            <td>Address information</td>
            <td><a href='/address/<?php echo $db['address'] ?>'><?php echo $db['address'] ?></a></td>
        </tr>

        <tr>
            <td>Database</td>
            <td><a href='/db/<?php echo $db['hash160'] ?>'><?php echo $db['hash160'] ?></a></td>
        </tr>

    </table>

    <?php if ($db['dataset']): ?>
        <h3>Dataset actual settings</h3>
        <table class='table table-bordered table-hover'>

            <tr>
                <td>Dataset / Operator</td>
                <td>Writer</td>
                <td>Owner_key</td>
                <td>WriteScript</td>
            </tr>
            <?php $d = $db['actualSettings'] ?>
            <tr class=''>
                <td>
                    <?php echo $d['dataset'] ?><br/>
                    <span class='text-muted'><?php echo $d['operator'] ?></span><br/>
                    <a href='#' onclick='$(".content<?php echo $i ?>").toggleClass("hide");
                            return false;'>Show Datascript content</a>
                </td>
                <td><a title='<?php echo $d['writer'] ?>'
                       href='/address/<?php echo $d['writer'] ?>'><?php echo truncate($d['writer'], true) ?></a></td>
                <td><a title='<?php echo $d['content']['owner_key'] ?>'
                       href='/address/<?php echo $d['content']['owner_key'] ?>'><?php echo truncate($d['content']['owner_key'], true) ?></a>
                </td>
                <td>
                    <?php
                    if ($d['content']['writeScript'] == '5560')
                        echo "PUSHDATA_WRITER<br />OP_CHECKDBPRIVILEGES";
                    else
                        echo "all"
                    ?>
                    <a class='ask' tabindex="0" data-toggle="popover" data-trigger="focus" title="writeScript"
                       data-content="WriteScript decides who can write to this database. 5560 mean 0x55 (PUSHDATA_WRITER) + 0x60 (OP_CHECKDBPRIVILEGES), this means that only the owner whose key is the same as the owner_key or whose key is included in the privileges array is able to write"><i
                                class='fa fa-question'></i></a>
                </td>
            </tr>
            <tr class='content<?php echo $i ?> hide'>
                <td style='overflow-x: overlay' colspan="3" class='<?php echo $i % 2 == 0 ? 'tx-even' : '' ?>'>
                    <?php if ($d['content']): ?>
                        <pre><?php echo htmlentities(json_encode($d['content'], JSON_PRETTY_PRINT)) ?></pre>
                    <?php else: ?>
                        < <?php echo $d['algorithm'] ? $d['algorithm'] : 'rsa' ?> encrypted content >
                    <?php endif ?>
                </td>
            </tr>

        </table>

        <h3>Data in dataset <?php echo $db['addressDomain'] ? ($db['addressDomain'] . " ({$db['hash160']})") : $db['hash160'] ?>/<?php echo $db['dataset'] ?>
            (<?php echo($db['pager']['count']) ?>)</h3>
        <table class='table table-bordered table-hover'>

            <tr>
                <td>Dataset / Operator</td>
                <td>Writer</td>
                <td>Oid</td>
            </tr>
            <?php foreach ($db['list'] as $i => $d): ?>

                <tr class='<?php echo $i % 2 == 0 ? 'tx-even' : '' ?>'>
                    <a name='<?php echo $d['content']['oid'] ?>'></a>
                    <td>
                        <?php echo $d['dataset'] ?><br/>
                        <span class='text-muted'><?php echo $d['operator'] ?></span>
                        <br/><br/>
                        <a href='#' onclick='$(".content<?php echo $i ?>").toggleClass("hide");
                                return false;'>Show Datascript content</a>
                    </td>
                    <td><a title='<?php echo $d['writer'] ?>'
                           href='/address/<?php echo $d['writer'] ?>'><?php echo truncate($d['writer'], true) ?></a>
                    </td>
                    <td><a href='#<?php echo $d['content']['oid'] ?>'><?php echo $d['content']['oid'] ?></a></td>
                </tr>
                <tr class='content<?php echo $i ?> hide'>
                    <td style='overflow-x: overlay' colspan="3" class='<?php echo $i % 2 == 0 ? 'tx-even' : '' ?>'>
                        <?php if ($d['content']): ?>
                            <pre><?php echo htmlentities(json_encode($d['content'], JSON_PRETTY_PRINT)) ?></pre>
                        <?php else: ?>
                            < <?php echo $d['algorithm'] ? $d['algorithm'] : 'rsa' ?> encrypted content >
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>

        <?php if ($db['pager']['pages'] > 1): ?>
            <ul class="pagination pagination-lg  justify-content-center">
                <?php if ($db['pager']['page'] > 1): ?>
                    <li class="page-item"><a class="page-link"
                                             href="<?php echo $db['pager']['path'] ?>page=<?php echo $db['pager']['page'] - 1 ?>">Prev</a>
                    </li>
                <?php endif; ?>

                <?php for ($i = $db['pager']['nearLeft']; $i <= $db['pager']['nearRight']; $i++): ?>
                    <?php if ($i == $db['pager']['page']): ?>
                        <li class="page-item active">
                            <a class="page-link" href="#"><?php echo $i ?> <span class="sr-only">(current)</span></a>
                        </li>
                    <?php else: ?>
                        <li class="page-item"><a class="page-link"
                                                 href="<?php echo $db['pager']['path'] ?>page=<?php echo $i ?>"><?php echo $i ?></a>
                        </li>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($db['pager']['page'] < $db['pager']['pages']): ?>
                    <li class="page-item"><a class="page-link"
                                             href="<?php echo $db['pager']['path'] ?>page=<?php echo $db['pager']['page'] + 1 ?>">Next</a>
                    </li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>
    <?php else: ?>
        <h3>Datasets in
            db <?php echo $db['addressDomain'] ? ($db['addressDomain'] . " ({$db['hash160']})") : $db['hash160'] ?></h3>
        <table class='table table-bordered table-hover'>

            <tr>
                <td>Dataset</td>
                <td>Writer</td>
                <td>Owner</td>
                <td>WriteScript</td>
                <td>Dataset / Operator</td>
            </tr>
            <?php foreach ($db['list'] as $i => $d): ?>

                <tr class='<?php echo $i % 2 == 0 ? 'tx-even' : '' ?>'>
                    <td>
                        <b><a href='/db/<?php echo $db['hash160'] ?>/<?php echo $d['dataset'] ?>'><?php echo $d['dataset'] ?></a></b><br />
                        <a href="/db/<?php echo $db['hash160'] ?>/<?php echo $d['dataset'] ?>">dataset info</a>
                        <a href="/records/<?php echo $db['hash160'] ?>/<?php echo $d['dataset'] ?>">dataset records</a>

                        <br/><br/>
                        <a href='#' onclick='$(".content<?php echo $i ?>").toggleClass("hide");
                                return false;'>Show Datascript content</a>
                    </td>
                    <td><a title='<?php echo $d['writer'] ?>'
                           href='/address/<?php echo $d['writer'] ?>'><?php echo truncate($d['writer'], true) ?></a>
                    </td>
                    <td><a title='<?php echo $d['content']['owner_key'] ?>'
                           href='/address/<?php echo $d['content']['owner_key'] ?>'><?php echo truncate($d['content']['owner_key'], true) ?></a>
                    </td>
                    <td>
                        <?php
                        if ($d['content']['writeScript'] == '5560')
                            echo "PUSHDATA_WRITER<br />OP_CHECKDBPRIVILEGES";
                        else
                            echo "all"
                        ?>
                        <a class='ask' tabindex="0" data-toggle="popover" data-trigger="focus" title="writeScript"
                           data-content="WriteScript decides who can write to this database. 5560 mean 0x55 (PUSHDATA_WRITER) + 0x60 (OP_CHECKDBPRIVILEGES), this means that only the owner whose key is the same as the owner_key or whose key is included in the privileges array is able to write"><i
                                    class='fa fa-question'></i></a>
                    </td>
                    <td>
                        <?php echo $d['dataset'] ?><br/>
                        <span class='text-muted'><?php echo $d['operator'] ?></span>
                    </td>
                </tr>
                <tr class='content<?php echo $i ?> hide'>
                    <td style='overflow-x: overlay' colspan="3" class='<?php echo $i % 2 == 0 ? 'tx-even' : '' ?>'>
                        <?php if ($d['content']): ?>
                            <pre><?php echo htmlentities(json_encode($d['content'], JSON_PRETTY_PRINT)) ?></pre>
                        <?php else: ?>
                            < <?php echo $d['algorithm'] ? $d['algorithm'] : 'rsa' ?> encrypted content >
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    <?php endif ?>


</div>