<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 18/07/2017
 * Time: 2:22 PM
 */

include "models/config.php";

$banned = $mysqli->query("SELECT  `user_id`, `reason`, `autherising_user_id`, `timestamp_given`, `timestamp_end` FROM `forum_offences` WHERE `user_id` = $loggedInUser->user_id")->fetch_object();
if ($banned) {
    die("You have been banned until " . date("d/m/Y h:i:s A", $banned->timestamp_end) . "<br><br><b>Reason:</b> <br>" . $banned->reason);
}else{
    die("Why are you here? You haven't been banned... yet :}");
}