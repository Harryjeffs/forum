<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 03/01/2017
 * Time: 2:06 PM
 */
include "../../../models/config.php";

    $type = intval($_POST['type']);
    switch ($type){
        case 1:
            $user_id = $loggedInUser->user_id;
            $notification_id = $_POST['notification_id'];

            $stmt = $mysqli->prepare("UPDATE forum_notifications_recipients SET message_read = true WHERE user_id = ? AND notification_id = ?");

            $stmt->bind_param("ii", $user_id, $notification_id);
            $stmt->execute();

            $stmt->close();
            break;
        case 0:
            $user_id = $loggedInUser->user_id;

            $stmt = $mysqli->prepare("UPDATE forum_notifications_recipients SET message_read = true WHERE user_id = ?");

            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            $stmt->close();
            break;
        case 3:
            $notificationsCount = $mysqli->query("SELECT * FROM `forum_notification_new` INNER JOIN forum_notifications_recipients ON forum_notification_new.notification_id = forum_notifications_recipients.notification_id WHERE forum_notifications_recipients.user_id = $loggedInUser->user_id  AND forum_notifications_recipients.message_read = 0")->num_rows;
            echo json_encode(array("notificationCount"=>$notificationsCount));
            break;
        default:
            echo "RIP: An error has occurred.";
            break;
    }