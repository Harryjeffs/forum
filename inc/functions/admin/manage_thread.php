<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 24/12/2016
 * Time: 2:20 PM
 */


include("../../../models/config.php");
if($loggedInUser->checkPermission(array(2,14))) {


    if(isset($_POST['value'])) {
        $thread_id = $_POST['threadId'];

        $sub_cat_id = $mysqli->query("SELECT sub_category_id from forum_thread WHERE thread_id = $thread_id")->fetch_object()->sub_category_id;

        $sub_cat_desc = $mysqli->query("SELECT sub_category_desc FROM `forum_sub_category` WHERE sub_category_id = $sub_cat_id")->fetch_object()->sub_category_desc;

        $stmt = $mysqli->query("SELECT * FROM forum_thread WHERE thread_id = $thread_id");
        $info = $stmt->fetch_object();

        switch($_POST['event']){
            case "lock":
                $locked = $_POST['value'];

                if ($locked == 1) {
                    $html = "<div class='alert alert-warning'>This thread has been locked. Further discussion is no longer available. </div>";
                    lockForumThread($thread_id);
                    echo json_encode(array("error"=>false,"title"=>"Congrats", "text"=>"Thread successfully locked.","type"=>1, "html"=>$html, "clicked"=>true));
                }
                if ($locked == 0){
                    $html = ' <div class="panel-body"> <form id="formoid" action="" title="" method="post" novalidate> <input type="hidden" name="threadID" id="threadID" value="'.$thread_id.'"> <textarea style="min-height:150px;display:none" id="threadReplyContent" name="threadReplyContent" required></textarea> <button type="button" class="btn btn-success btn-block" id="register" name="threadBtn" style="border-radius:0;border-bottom-left-radius:3px;border-bottom-right-radius:3px">Submit Reply</button> </div>';
                    unlockForumThread($thread_id);
                    echo json_encode(array("error"=>false,"title"=>"Congrats", "text"=>"Thread successfully un-locked.","type"=>1, "html"=>$html, "clicked"=>false));

                }
                break;
            case "moveThread":
                $moveHTML = '<a href="'. FULL_PATH .'/category/'.$sub_cat_id.'">'. $sub_cat_desc .'</a>';

                $new_thread_id = $_POST['value'];

                if ($new_thread_id != $info->sub_category_id and $new_thread_id > 0) {
                    echo $info->sub_category_id;
                    if(updatecategoryid($new_thread_id, $thread_id)){
                        echo json_encode(array("error"=>false,"title"=>"Congrats", "text"=>"You have successfully updated the threads category.","html"=>$moveHTML, "type"=>2));
                    }else{
                        echo json_encode(array("error"=>true,"title"=>"Oops", "text"=>"An error has occurred. Sorry", "type"=>2));
                    }
                }
                break;
            case "delete":
                deleteForumThread($thread_id);
                echo json_encode(array("error"=>false,"title"=>"Congrats", "text"=>"You have successfully deleted this thread", "type"=>3));
                break;
            case "pin":
                $pin = $_POST['value'];
                if($pin == 1){
                    PinForumThread($thread_id);
                    echo json_encode(array("error"=>false,"title"=>"Congrats", "text"=>"Thread successfully pinned. ", "type"=>4));
                }
                if ($pin == 0){
                    unpinForumThread($thread_id);
                    echo json_encode(array("error"=>false,"title"=>"Congrats", "text"=>"Thread successfully un-pinned. ", "type"=>4));

                }

                break;
            default:
                if($_POST['event'] !== 0){
                    echo json_encode(array("error"=>true,"title"=>"error", "text"=>"A fatal error has occurred."));
                }
        }


        }
}else{
    die("You do not have permission to view this page. This action has been logged. ");
}