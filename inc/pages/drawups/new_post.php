<?php

/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 14/07/2017
 * Time: 12:40 PM
 */
class new_post
{
    public function __construct(){}

    public function parse($content, $reply_id){
        $this->content = $content;
        $this->reply_id = $reply_id;
    }
    public function value($thread_id){
        global $mysqli, $loggedInUser;

        $lmao = $mysqli->query("SELECT forum_thread.sub_category_id, user_id, thread_id, locked, topic_header, pinned, timestamp FROM `forum_thread` INNER JOIN forum_sub_category ON forum_thread.sub_category_id = forum_sub_category.sub_category_id WHERE forum_thread.thread_id = $thread_id");
        $v1 = $lmao->fetch_array();


        $thread_id = $v1['thread_id'];

        if(currentForumThreadView($thread_id) == 0){
            newForumThreadView($thread_id);
        }

        $sqlStr = "SELECT * FROM `forum_replys` WHERE `reply_id` = '$this->reply_id' and deleted = false ORDER BY `thread_id` desc";

        $query = $mysqli->query($sqlStr);

        $v2 = $query->fetch_array();

        ?>
        <?php
        $name = getUsername($v2['user_id'], "forum_replys");
        $display_name = getDisplayName($v2['user_id'], "forum_replys");
        $new_post_content = "";

        $new_post_content .= '
<div class="panel panel-default" id="'. $v2['reply_id'].'">
    <div class="panel-body comment">
        <div class="module-comment-block">
            <div class="avatar-info">
                <div class="module-comment-avatar">
                    <div class="comment-avatar" style="background-image: url(http://www.habbo.com/habbo-imaging/avatarimage?user='. $name .'&amp;action=std&amp;direction=2&amp;head_direction=2&amp;gesture=sml&amp;size=m&amp;img_format=gif)"></div>
                    <ul>
                        <li>'. forumUserAlge($v2['user_id']) .'</li>
                       '. forumProgressBar($name, totalUserPosts($v2['user_id'])).'
                    </ul>
                </div>
            </div>
            <div class="module-comment-text">
                <a href="'. FULL_PATH .'/user.php?username='. $name.'" class="usernameofcomment">
                    <strong>'. $display_name .'</strong>
                </a>
                <div class="postContentDiv">';

        $mention = new mentions();

        $userf = fetchAllUsers();

        foreach ($userf as $users1){
            $mention->add_name($users1['user_name']);
        }
        $parser = new \SBBCodeParser\Node_Container_Document();

     //   $quoted1 = get_all_string_between($this->content);

        $parser->add_emoticons(array(
            ':)' => 'emoji/1F603.png',
            ':D' => 'emoji/1F604.png',
            'xD' => 'emoji/1F632.png'
        ));
        $this->content = $parser->parse($this->content)
            ->detect_links()
            ->detect_emails()
            ->detect_emoticons()
            ->get_html();

        $this->content = $mention->process_text($this->content);
        
        $new_post_content .= $this->content;

        $new_post_content .= ' </div>
            </div>
        </div>
    </div>
     <div class="panel-footer">
        <div class="row">
            <div class="pull-left">
                <span class="footer-timestamp">Posted
                  <span data-livestamp="'. $v2['timestamp'] .'"></span>
                  <span id="likesBar'. $v2['reply_id'].'">'.forumLikesHtml($v2['reply_id']).'</span>
                </span>
            </div>
            <div class="pull-right">
                <div class="options-comments">
                    <ul class="">';

        if (forumlikes($v2['reply_id']) == 0) {
            $new_post_content .= '  <li id="likePost'. $v2['reply_id'].'">
                                <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Like" class="like" data-post_id="'. $v2['reply_id'] .'" data-reciever_user_id="'. $v2['user_id'] .'"><i class="ion ion-ios-heart"></i> </a>
                            </li> ';
        } else {
            $new_post_content .= '
                             <li>
                                <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="" data-original-title="You have already liked this post"><i style="border-color: black;" class="ion-ios-heart-outline liked-post"></i> </a>
                            </li>';
        }
        if($loggedInUser->checkPermission(array(2))){

            $new_post_content .= '  <li>
                <a href="javascript:void(0);"  data-toggle="modal" data-target="#threadManage"><i class="ion ion-gear-a"></i> </a>
            </li> ';

        }

        $new_post_content .= '
                        <li><a href="javascript:void(0);" data-placement="top" data-toggle="tooltip" title="" data-original-title="More"><i class="ion-android-more-horizontal" id="optionModal" style="font-size: 17px;" data-post_id="'.$v2['reply_id'].'"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- /module-comment-block -->
</div>';
return $new_post_content;

    }
}