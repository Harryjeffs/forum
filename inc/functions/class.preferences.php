<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 04/06/2017
 * Time: 6:25 PM
 */

//Initiate the preferences class
class preferences{
    // Define the social media variables
    var $facebook, $twitter, $website, $skype;

    private function sqlUpdate($user_id, $url, $type)
    {
        global $mysqli;

        $field = "social_" . $type;

        $stmt = $mysqli->prepare("UPDATE portaluser_preferences SET $field = $url WHERE user_id = ? ");

        $stmt->bind_param('i', $user_id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function updateSocial($facebook, $twitter, $website, $skype){
        global $loggedInUser;
        //Put everything into an array to help loop through them in the case statement
        $socialArray = array("facebook"=>$facebook, "twitter"=>$twitter, "website"=>$website, "skype"=>$skype);

        // loop through the $socialArray array
        foreach ($socialArray as $type => $URL){
            //Switch Case
            switch ($type){
                case "facebook":
                    $this->sqlUpdate($loggedInUser->user_id, $URL, $type);
                    break;
                case "twitter":
                    $this->sqlUpdate($loggedInUser->user_id, $URL, $type);
                    break;
                case "website":
                    $this->sqlUpdate($loggedInUser->user_id, $URL, $type);
                    break;
                case "skype":
                    $this->sqlUpdate($loggedInUser->user_id, $URL, $type);
            }
        }
    }

    public function updatePref($emailSent, $Gender, $showOnline, $Location){
        global $loggedInUser;

        $prefArray = array("emailSent"=>$emailSent, "Gender"=>$Gender, "showOnline"=>$showOnline, "Location"=>$Location);

        foreach($prefArray as $type => $value){
            switch($type){
                case "emailSent":
                    break;
                case "Gender":
                    break;
                case "showOnline":
                    break;
                case "Location":
                    break;
            }
        }
    }
}
