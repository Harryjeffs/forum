<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 20/07/2017
 * Time: 12:36 PM
 */

include '../../../models/config.php';

if (!empty($_POST)) {


    $user_id = intval($_POST['user_id']);
    $offence_type = intval($_POST['punishment']);
    $disabled = intval($_POST['disable']);
    $reason = $_POST['reason'];
    $autherising_user_id = $loggedInUser->user_id;
    $exp_date = strtotime($_POST['datetimepicker']);
    $timestamp_given = time();
    $offence_read = false;
    $offended = $_POST['optradio'];
    $json = array();


    if ($offence_type == "") {
        $json[] = array("error" => true, "title" => "It's not all there!", "text" => "Please fill out all the punish form appropriately.", "type" => "error");
    } elseif (strlen($reason) < 31) {
        $json[] = array("error" => true, "title" => "That's not enough :(", "text" => "Your reason must be more then 30 characters in length. Please give detail.", "type" => "error");
    } else {
        switch ($offence_type) {
            case 1:
                $timestamp_end = time();
                break;
            case 2:
                $timestamp_end = time() + 7200;
                break;
            case 3:
                $timestamp_end = time() + 86400;
                break;
            case 4:
                $timestamp_end = time() + 604800;
                break;
            case 5:
                $timestamp_end = 1752749810;
                break;
            default:
                $timestamp_end = time();
        }
        $stmt = $mysqli->prepare("INSERT INTO `forum_offences`(`user_id`, `offense_type`, `reason`, `autherising_user_id`, `offense_read`, `timestamp_given`, `timestamp_end`) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param('iisibii', $user_id, $offence_type, $reason, $autherising_user_id, $offence_read, $timestamp_given, $timestamp_end);
        $stmt->execute();
        $stmt->close();

        $json[] = array("error" => false, "title" => "Success", "text" => "This user has been successfully sanctioned.", "type" => "success");
        if($offended == "Yes") {
            if ($disabled > 0) {
                $stmt1 = $mysqli->prepare("INSERT INTO `forum_disable_user`(`user_id`, `disable_type`,`timestamp`,`exp_timestamp` , `user_id_given`) VALUES (?,?,?,?,?)");

                $stmt1->bind_param('iiiii', $user_id, $disabled, $timestamp_given, $exp_date, $autherising_user_id);
                if ($stmt1->execute()) {
                    $json[] = array("error" => false, "title" => "Woops", "text" => "This user has had their privileges disabled.", "type" => "success");
                } else {
                    $json[] = array("error" => true, "title" => "Success", "text" => lang("SQL_ERROR"), "type" => "error");
                }
                $stmt1->close();
            }
        }
    }
    /*
     *
     *
     * EDIT USER FUNCTIONS COME IN HERE
     *
     *
     */

    $username = $_POST['new_username'];
    $display_name = $_POST['display'];
    $email = $_POST['email'];
    $rank = $_POST['rank'];
    $tag = $_POST['tag'];

    $userdetails = fetchUserDetails(NULL, NULL, $user_id);

//update the users tag
    if ($userdetails['promo_tag'] != $tag) {

        if (minMaxRange(1, 4, $tag)) {
            $json[] = array("error" => true, 'title' => "Invalid Tag Length", "text" => lang("TAG_TOO_SHORT"), "type" => "error");
        } else {
            $sql = updateAdminTag($user_id);
            $json[] = array("error" => false, 'title' => "Congrats", "text" => lang("ADMIN_TAG_UPDATE", array($tag)), "type" => "success");
        }

    }
//Update display name
    if ($userdetails['display_name'] != $display_name) {
        $valid_chars = array('-', '_', ' ', '.', '@', '=', '^', ':', '!');

        //Validate display name
        if (displayNameExists($display_name)) {
            $json[] = array("error" => true, 'title' => "Already in use :/", "text" => lang("ACCOUNT_DISPLAYNAME_IN_USE", array($display_name)), "type" => "error");
        } elseif (minMaxRange(3, 25, $display_name)) {
            $json[] = array("error" => true, 'title' => "We have limits!", "text" => lang("ACCOUNT_DISPLAY_CHAR_LIMIT", array(3, 25)), "type" => "error");
        } else if (!ctype_alnum(str_replace($valid_chars, '', $display_name))) {
            $json[] = array("error" => true, 'title' => "Some error", "text" => lang("ACCOUNT_DISPLAY_INVALID_CHARACTERS"), "type" => "error");

        } else {
            if (updateDisplayName($user_id, $display_name)) {
                $json[] = array("error" => false, 'title' => "Congrats", "text" => lang("ACCOUNT_DISPLAYNAME_UPDATED", array($display_name)), "type" => "success");
            } else {
                $json[] = array("error" => true, 'title' => "Fatal Error", "text" => lang("SQL_ERROR"), "type" => "error");
            }
        }

    }

//Update email
    if ($userdetails['email'] != $email) {

        //Validate email
        if (!isValidEmail($email)) {
            $json[] = array("error" => true, 'title' => "Invalid Format", "text" => lang("ACCOUNT_INVALID_EMAIL"), "type" => "error");
        } elseif (emailExists($email)) {
            $json[] = array("error" => true, 'title' => "Already in use :/", "text" => lang("ACCOUNT_EMAIL_IN_USE", array($email)), "type" => "error");

        } else {
            if (updateEmail($user_id, $email)) {
                $json[] = array("error" => false, 'title' => "Success", "text" => lang("ACCOUNT_EMAIL_UPDATED"), "type" => "success");
            } else {
                $json[] = array("error" => true, 'title' => "Fatal Error", "text" => lang("SQL_ERROR"), "type" => "error");
            }
        }
    }
    if ($userdetails['user_name'] != $username) {

        updateUserName($username, $user_id);
        $json[] = array("error" => false, 'title' => "Success", "text" => lang("ACCOUNT_USERNAME_UPDATED"), "type" => "success");
    }
//update the users rank
    if ($userdetails['rank'] != $rank) {

        //End data validation
        $sql = updateAdminRank($rank, $user_id);
        $json[] = array("error" => false, 'title' => "Success", "text" => lang("ADMIN_RANK_UPDATE", array($display_name, $rank)), "type" => "success");

    }
    /*
     *
     *
     * USER PERMISSIONS EDITS GO IN HERE
     *
     *
     */

    if (!empty($_POST['removePermission'])) {
        $remove = $_POST['removePermission'];

        if ($deletion_count = removePermission($remove, $user_id)) {
            $json[] = array("error" => false, "title" => "Success", "text" => lang("ACCOUNT_PERMISSION_REMOVED", array($deletion_count)), "type" => "success");
        } else {
            $json[] = array("error" => true, 'title' => "Fatal Error", "text" => lang("SQL_ERROR"), "type" => "error");
        }
    }

    if (!empty($_POST['addPermission'])) {
        $add = $_POST['addPermission'];

        if ($addition_count = addPermission($add, $user_id)) {
            $json[] = array("error" => false, "title" => "Success", "text" => lang("ACCOUNT_PERMISSION_ADDED", array($addition_count)), "type" => "success");
        } else {
            $json[] = array("error" => true, 'title' => "Fatal Error", "text" => lang("SQL_ERROR"), "type" => "error");
        }
    }

    /*
     *  ECHO OUT ALL THE JSON DATA
     */

    if (count($json) == 1) {
        echo json_encode($json);
    }
    if (count($json) == 0) {
        echo "[" . json_encode(array("error" => true, "type" => "error", "title" => "It's all the same?", "text" => lang("NOTHING_TO_UPDATE"))) . "]";
    }
    if (count($json) > 1) {
         for($i=1; $i<count($json); $i++) {
             echo json_encode($json);
         }
    }
}else{
    echo "[" . json_encode(array("error" => true, "type" => "error", "title" => "Woops", "text" => lang("SQL_ERROR"))) . "]";
}