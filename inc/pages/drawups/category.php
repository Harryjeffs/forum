<?php
/**
 * Created by PhpStorm.
 * User: HarryJeffs
 * Date: 29/09/2017
 * Time: 10:43 PM
 */
class view_category
{
    public function __construct()
    {
    }

    public function parse($user_id, $sub_cat_id)
    {
        $this->user_id = $user_id;
        $this->category_id = $sub_cat_id;
    }

    public function value($cat_fetched)
    {
        global $mysqli;

        $html = "";
        $reply = $mysqli->query("SELECT reply_id FROM `forum_replys` WHERE thread_id = " . $cat_fetched['thread_id'] . " and deleted = false")->num_rows;
        $html .= '<li class="threads-item">
                    <div class="row">
                        <div class="col-xs-10 threadInfo">
                            <div class="threadAvatar pull-left">
                           <div class="userAvatar-md" style="background-image: url(\'http://www.habbo.com/habbo-imaging/avatarimage?user=' . getUsername($cat_fetched['user_id'], "forum_sub_category") . '&gesture=sml\');"></div>
                            </div>
                            <h4 class="titleThread">';
        if ($cat_fetched['pinned'] == 1) {
            $html .= '<i class="ion-pin threadPinned" data-toggle="tooltip" data-trigger="hover" title="Pinned Thread"></i>';
        }
        if ($cat_fetched['locked'] == 1) {
            $html .= '<i class="ion-ios-locked threadPinned" data-toggle="tooltip" data-trigger="hover" title="Locked Thread"></i>';
        }
        $html .= '<a href="/forum/thread/' . $cat_fetched['thread_id'] . '/" class="linkPrimary">' . $cat_fetched['topic_header'] . '</a></h4>
                            <p class="metaThread">Posted <span data-livestamp="' . $cat_fetched['timestamp'] . '"></span> by <a href="' . FULL_PATH . '/user/' . getUsername($cat_fetched['user_id'], "forum_sub_category") . '" class="linkSecondary">' . getUsername($cat_fetched['user_id'], "forum_sub_category") . '</i></a></p>
                        </div>
                        <div class="col-xs-2">
                            <div class="pull-right replyCount">
                                <i class="ion-ios-chatboxes-outline replyCountIcon"></i> <span class="replyCountInt">' . $reply . '</span>
                            </div>
                        </div>
                    </div>
                </li>';
        return $html;
    }
}