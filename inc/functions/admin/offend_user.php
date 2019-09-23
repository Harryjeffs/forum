<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 17/01/2017
 * Time: 4:28 PM
 */

include("../../../models/config.php");

    $user_id = $_POST['user_id'];
    $offense_type = $_POST['offense_type'];
    $reason = $_POST['reason'];
    $offense_read = false;
    $time_given = time();
    $time_end = time() * $_POST[''];


$stmt = $mysqli->prepare("INSERT INTO `forum_offences`(`user_id`, `offense_type`, `reason`, `autherising_user_id`, `offense_read`, `timestamp_given`, `timestamp_end`) VALUES (?,?,?,?,?,?,?)");

$stmt->bind_param('iisibii', $user_id, $offense_type, $reason, $loggedInUser->user_id, $offense_read, $time_given, $time_end);
$stmt->execute();

$stmt->close();

