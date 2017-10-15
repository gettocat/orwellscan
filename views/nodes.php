
<h3>Peer info</h3>
<table class="table table-bordered table-striped">

    <tr>
        <td>Node</td>
        <td>LastMsg</td>
        <td>Height</td>
        <td>Top block</td>
    </tr>

    <?php foreach ($nodes as $addr => $info): ?>
        <tr>
            <td><?php
            list($ip) = explode("//", $addr);
            $inf = ipinfo($ip);
            echo "<img src='{$inf['icon']}' /> ".$inf['title']
            ?></td>
            <td><?php echo time_since($info['lastMsg']) ?> ago</td>
            <td><a href='/height/<?php echo $info['top']['height'] ?>'><?php echo $info['top']['height'] ?></a></td>
            <td><a href='/block/<?php echo $info['top']['hash'] ?>'><?php echo $info['top']['hash'] ?></a></td>
        </tr>
    <?php endforeach; ?>
</table>