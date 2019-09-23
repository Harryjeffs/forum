<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 24/06/2017
 * Time: 1:45 PM
 */
include '../../models/config.php';

$thread_id = $_POST['id'];

$lmao = $mysqli->query("SELECT forum_thread.sub_category_id, user_id, thread_id, locked, topic_header, pinned, timestamp FROM `forum_thread` INNER JOIN forum_sub_category ON forum_thread.sub_category_id = forum_sub_category.sub_category_id WHERE forum_thread.thread_id = $thread_id");
$v1 = $lmao->fetch_array();


$items = 8;
$page = 1;

//    if(currentForumThreadView($thread_id) == 0){
//        newForumThreadView($thread_id);
//    }

if(isset($_POST['page']) and is_numeric($_POST['page']) and $page = $_POST['page'])
    $limit = " LIMIT ".(($page-1)*$items).",$items";
else
    $limit = " LIMIT $items";

$sqlStr = "SELECT * FROM `forum_replys` WHERE `thread_id` = '$thread_id' and deleted = false ORDER BY `thread_id` desc";
$sqlStrAux = $mysqli->query("SELECT * FROM `forum_replys` WHERE `thread_id` = '$thread_id' and deleted = false ORDER BY `thread_id` desc");

$query = $mysqli->query($sqlStr.$limit);

$p = new pagination;
$p->items($sqlStrAux->num_rows);
$p->limit($items);
$p->ajax(true);
$p->currentPage($page);
$p->calculate();

if($query->num_rows == 0) {
    echo "<div class='alert alert-warning'>Error page not found. </div>";
}else {

    include 'drawups/new_post.php';

    while ($loop = $query->fetch_array()) {

        $post_content = htmlspecialchars_decode(preg_replace('@\x{FFFD}@u', '', $loop['reply_content']));

        $posted = new new_post();
        $posted->parse($post_content, $loop['reply_id']);

        echo $posted->value($thread_id);
    }
    echo " <div id='newPostJquery'></div>";
    $p->show();
}

