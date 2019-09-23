<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 06/12/2016
 * Time: 11:40 AM
 */
require_once("../../models/config.php");
//Prevent the user visiting the logged in page if he is not logged in
if(!isUserLoggedIn()) { header("Location: /index.php"); die(); }


$post_id = intval($_POST['post_id']);

$stmt = $mysqli->query("SELECT * FROM forum_replys WHERE reply_id = $post_id");
$post_edit = $stmt->fetch_array();


$new_text = $_POST['replyContent'];
$old_text = $post_edit['reply_content'];

$thread_id = $post_edit['thread_id'];

if ($new_text == $old_text) {
    echo "same";
} else {
    EditInsert($old_text, $new_text, $post_id, $thread_id);

    $action = "$loggedInUser->username edited the post.";
    $page = "Edit Post";
    $level = 3;
    userForumLogs($page, $action, $level);

    editPost($new_text, $post_id);
}