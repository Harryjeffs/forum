<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 21/01/2017
 * Time: 9:53 AM
 */

include("../../models/config.php");
if($loggedInUser->checkPermission(array(2,14))) {
    $thread_id = $_GET['Tid'];
    if (!is_numeric($thread_id)) {
        header("Location: /forum/");
    }
        $action = "$loggedInUser->username deleted the thread $thread_id.";
        $page = "Delete Thread";
        $level = 3;
        userForumLogs($page, $action, $level);


        $posts = $mysqli->query("SELECT * FROM forum_replys WHERE thread_id = $thread_id");
        $posts = $posts->fetch_array();
        $deleted = 1;


        $stmt = $mysqli->prepare("UPDATE `forum_replys` SET deleted = ? WHERE thread_id = ?");

        $stmt->bind_param('ii', $deleted, $thread_id);

        $stmt->execute();
        $stmt->close();

        $stmt2 = $mysqli->prepare("UPDATE `forum_thread` SET deleted = ? WHERE thread_id = ?");

        $stmt2->bind_param('ii', $deleted, $thread_id);

        $stmt2->execute();
        $stmt2->close();

}else{
    header("Location: /forum");
}