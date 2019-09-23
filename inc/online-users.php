<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 16/12/2016
 * Time: 12:00 PM
 */

include '../models/config.php';
$onlineQuery = $mysqli->query("SELECT DISTINCT `username` FROM `portalsessions` WHERE lastActive >= UNIX_TIMESTAMP(date_sub(now(), interval 10 minute))");

$onlineCount = $onlineQuery->num_rows;

?>

<div class="sidebar-category">
    <div class="sidebar-category--head">
        <h3><a href="#">Online Users</a></h3>
        <h4>There are currently <?php echo $onlineCount; ?> online users.</h4>
    </div>
</div>
<div class="sidebar-category--content" style="margin-top: 20px">
    <p style="padding-right: 15px;">
        <?php while ($userOnline = $onlineQuery->fetch_array()){?>
            <a href="/user/stats/<?php echo $userOnline['username']; ?>"><?php echo $userOnline['username'];?></a>,
        <?php }?>
     </p>
</div>
