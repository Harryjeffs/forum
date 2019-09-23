<?php
/**
 * Created by PhpStorm.
 * User: HarryJeffs
 * Date: 13/6/18
 * Time: 5:53 PM
 */
//Password Reset Button was hit
 include '../../models/config.php';
if ($loggedInUser->checkPermission(array(2,15))) {
    if (isset($_POST['passreset'])) {
        $token = $userdetails['lost_password_request'];
        $rand_pass = getUniqueCode(15); //Get unique code
        $secure_pass = generateHash($rand_pass); //Generate random hash
        $mail = new userCakeMail();

        //Setup our custom hooks
        $hooks = array(
            "searchStrs" => array("#GENERATED-PASS#", "#USERNAME#"),
            "subjectStrs" => array($rand_pass, $userdetails["display_name"])
        );

        if (!$mail->newTemplateMsg("../../models/mail-templates/your-lost-password.txt", $hooks)) {
            $errors[] = lang("MAIL_TEMPLATE_BUILD_ERROR");
        } else {
            if (!$mail->sendMail($userdetails["email"], "Your new password")) {
                $errors[] = lang("MAIL_ERROR");
            } else {
                if (!updateUserPassword($secure_pass, $id)) {
                    $errors[] = lang("SQL_ERROR");
                } else {
                    if (!flagLostPasswordRequest($userdetails["user_name"], 0)) {
                        $errors[] = lang("SQL_ERROR");
                    } else {
                        $successes[] = lang("FORGOTPASS_NEW_PASS_EMAIL");

                        $action = "Reset $displayname's password.";
                        userLogs($action, $title);
                    }
                }
            }
        }
    }
}