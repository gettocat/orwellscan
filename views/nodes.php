
<h3>Peer info</h3>
<table class="table table-bordered table-striped">

    <tr>
        <td>Node</td>
        <td>LastMsg</td>
        <td>Height</td>
        <td>Services</td>
        <td>Useragent</td>
        <td>Connected</td>
    </tr>

    <?php foreach ($nodes as $addr => $info): ?>
        <tr>
            <td><?php
                list($ip) = explode("//", $addr);
                $inf = ipinfo($ip);
                echo "<img src='{$inf['icon']}' /> " . $inf['title']
                ?></td>
            <td><?php echo time_since($info['lastMsg']) ?> ago</td>
            <td>
                <a href='/height/<?php echo $info['top']['height'] ?>'><?php echo $info['top']['height'] ?></a>
                <br />
                <a href='/block/<?php echo $info['top']['hash']?>'><?php 
                echo truncate(str_replace("00","", $info['top']['hash']), false, 8);
                ?></a>
            </td>
            <td><?php
                if ($info['services'] == 0)
                    echo "Orwell Core";
                if ($info['services'] == 1)
                    echo "Network listener"
                    ?></td>
            <td><?php echo $info['agent']?> (v<?php echo $info['agent_version']?>)</td>
            <td><?php echo time_since(time()-$info['conntime'])?> ago</td>
        </tr>
    <?php endforeach; ?>
</table>