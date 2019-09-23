<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 10/07/2017
 * Time: 1:58 PM
 */
include '../../models/config.php';

if (isset($_POST)){
   /* Define an array for validation */
    $errors = array();
    $json = array();

   /*user Id*/
   $user_id = $loggedInUser->user_id;

    /* Preferences */
    $email = $_POST["email"];
    $display_name = $_POST['display_name'];
    $online = $_POST['show_online'];
    $gender = $_POST['gender'];

    /* Social Accounts */
    $skype = $_POST['skype'];
    $twitter = $_POST['twitter'];
    $facebook = $_POST['facebook'];


    /* Fetch the current data in the database so we can compare to the old user data to the new user data */
    $stmt = $mysqli->query("SELECT * FROM `portaluser_preferences` WHERE user_id = $user_id");
    $preference = $stmt->fetch_object();

    /*
     * Begin Validation
     */

   /* Validation for the users email! */
    if ($email != $loggedInUser->email) {
        if (trim($email) == "") {
            $json[] = array("error"=>true, "type"=>"error", "title"=>"Empty Email?", "text"=>lang("ACCOUNT_SPECIFY_EMAIL"));
        } else if (!isValidEmail($email)) {
            $json[] = array("error"=>true, "type"=>"error", "title"=>"Invalid Email", "text"=>lang("ACCOUNT_INVALID_EMAIL"));
        } else if (emailExists($email)) {
            $json[] = array("error"=>true, "type"=>"error", "title"=>"Invalid Email", "text"=>lang("ACCOUNT_EMAIL_IN_USE", array($email)));
        }
        //End data validation
        if (count($json) == 0) {
            $loggedInUser->updateEmail($email);
            $json[] = array("error"=>true, "type"=>"success", "title"=>"Success", "text"=>lang("ACCOUNT_EMAIL_UPDATED"));
        }
    }
    /*Update display name*/
    if ($loggedInUser->display_name  != $display_name) {
        $valid_chars = array('-', '_', ' ', '.', '@', '=', '^', ':', '!');

        //Validate display name
        if (displayNameExists($display_name)) {
            $json[] = array("error"=>true, "type"=>"error", "title"=>"The same?", "text"=>lang("ACCOUNT_DISPLAYNAME_IN_USE", array($display_name)));
        } elseif (minMaxRange(3, 25, $display_name)) {
            $json[] = array("error"=>true, "type"=>"error", "title"=>"An Error", "text"=>lang("ACCOUNT_DISPLAY_CHAR_LIMIT", array(3, 25)));
        }
        else if( ! ctype_alnum(str_replace($valid_chars, '', $display_name)) ) {
            $json[] = array("error"=>true, "type"=>"error", "title"=>"An Error", "text"=>lang("ACCOUNT_DISPLAY_INVALID_CHARACTERS"));
        } else {
            if (updateDisplayName($user_id, $display_name)) {
                $json[] = array("error"=>true, "type"=>"success", "title"=>"Success!", "text"=>lang("ACCOUNT_DISPLAYNAME_UPDATED", array($display_name)));
            } else {
                $json[] = array("error"=>true, "type"=>"error", "title"=>"An Error", "text"=>lang("ACCOUNT_DISPLAY_INVALID_CHARACTERS"));
            }
        }

    }
    /*
     * UPDATE A USERS ONLINE STATUS
     */
    if($preference->pref_showOnline != $online){
        if($online == "undefined"){
            $json[] = array("error"=>true, "type"=>"error", "title"=>"Woops", "text"=>lang("SQL_ERROR"));
        }else{
            if(updateUserPref("pref_showOnline", $online, "i")){
                $json[] = array("error"=>true, "type"=>"success", "title"=>"Success!", "text"=>lang("USER_ONLINE_SUCCESS"));
            }else{
                $json[] = array("error"=>true, "type"=>"error", "title"=>"Woops", "text"=>lang("SQL_ERROR"));
            }
        }
    }
    /*
     * UPDATE A USERS GENDER
     */
    if($preference->pref_Gender != $gender){
        if($gender == ""){
            $json[] = array("error"=>true, "type"=>"error", "title"=>"Woops", "text"=>lang("SQL_ERROR"));
        }else{
            if(updateUserPref("pref_Gender", $gender, "s")){
                $json[] = array("error"=>true, "type"=>"success", "title"=>"Success!", "text"=>lang("USER_GENDER_SUCCESS", array($gender)));
            }else{
                $json[] = array("error"=>true, "type"=>"error", "title"=>"Woops", "text"=>lang("SQL_ERROR"));
            }
        }
    }
    /*
     * UPDATE A USERS SKYPE
     */
    if($preference->social_skype != $skype){
        if($skype == ""){
            $json[] = array("error"=>true, "type"=>"error", "title"=>"Woops", "text"=>lang("SQL_ERROR"));
        }else{
            if(updateUserPref("social_skype", $skype, "s")){
                $json[] = array("error"=>true, "type"=>"success", "title"=>"Success!", "text"=>lang("USER_SKYPE_SUCCESS"));
            }else{
                $json[] = array("error"=>true, "type"=>"success", "title"=>"Woops", "text"=>lang("SQL_ERROR"));
            }
        }
    } /*
     * UPDATE A USERS TWITTER
     */
    if($preference->social_twitter != $twitter){
        if($facebook == ""){
            $json[] = array("error"=>true, "type"=>"error", "title"=>"Woops", "text"=>lang("SQL_ERROR"));
        }else{
            if(updateUserPref("social_twitter", $twitter, "s")){
                $json[] = array("error"=>true, "type"=>"success", "title"=>"Success!", "text"=>lang("USER_TWITTER_SUCCESS"));
            }else{
                $json[] = array("error"=>true, "type"=>"success", "title"=>"Woops", "text"=>lang("SQL_ERROR"));
            }
        }
    } /*
     * UPDATE A USERS FACEBOOK
     */
    if($preference->social_facebook != $facebook){
        if($facebook == ""){
            $json[] = array("error"=>true, "type"=>"error", "title"=>"Woops", "text"=>lang("SQL_ERROR"));
        }else{
            if(updateUserPref("social_facebook", $facebook, "s")){
                $json[] = array("error"=>true, "type"=>"success", "title"=>"Success!", "text"=>lang("USER_FACEBOOK_SUCCESS"));
            }else{
                $json[] = array("error"=>true, "type"=>"success", "title"=>"Woops", "text"=>lang("SQL_ERROR"));
            }
        }
    }

    /*
     *  ECHO OUT ALL THE JSON DATA
     */
    if(count($json) == 1) {
        echo json_encode($json);
    }
    if(count($json) == 0){
        echo "[".json_encode(array("error"=>true, "type"=>"error", "title"=>"It's all the same?", "text"=>lang("NOTHING_TO_UPDATE")))."]";
     }
     if (count($json) > 1){
        for($i=1;$i<count($json); $i++){
            echo json_encode($json);
        }
    }
}