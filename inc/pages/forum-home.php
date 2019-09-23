<?php
include('../../models/config.php');

$query = $mysqli->query("SELECT * FROM `forum_category`
    INNER JOIN `forum_sub_category`
    ON forum_category.category_id = forum_sub_category.category_id
    INNER JOIN portaluser_permission_matches
    ON portaluser_permission_matches.permission_id = forum_sub_category.permission_id
    WHERE portaluser_permission_matches.user_id = '$loggedInUser->user_id'
    ORDER BY forum_category.display_order, forum_sub_category.display_order ASC");

$category_desc = NULL;
$sub_category_desc = NULL;
?>
<div class="container main-content" id="content" style="margin-top: 35px;">
    <div class="row">
        <div class="col-md-8">

            <?php
            while($v1 = $query->fetch_array()) {
                $recentQ = $mysqli->query("SELECT forum_replys.timestamp, topic_header, forum_replys.user_id, forum_replys.thread_id, reply_id FROM `forum_replys` INNER JOIN `forum_thread` ON forum_replys.thread_id = forum_thread.thread_id WHERE forum_thread.sub_category_id = " . $v1['sub_category_id'] . " AND forum_thread.deleted = false oRDER BY forum_replys.timestamp DESC");

                $recent = $recentQ->fetch_array();

                if ($category_desc != $v1['category_desc']) {


                    if ($category_desc != NULL) {

                        echo '</ul>';
                        echo '</div>';
                        echo '</div>';
                    }
                    $category_desc = $v1['category_desc'];
                    echo '<div class="panel panel-default">
                                           <div class="panel-heading">
                                            <h3 class="panel-title">' . $category_desc . '</h3>
                                             
                                          </div>';
                    echo '<div class="panel-body" style="padding-bottom: 15px !important">';

                    echo '<ul>';
                }

                echo '  <div class="forum-row row">
                                        <div class="col-xs-7">
                                                <a href='.FULL_PATH.'/category/' . $v1['sub_category_id'] . '>
                                                    <h3 class="forum-title">' . $v1['sub_category_desc'] . '</h3></a>
                                                <p class="forum-description">' . $v1['sub_category_long_desc'] . '</p>
                                            </div>';
                $page_num = $mysqli->query("SELECT reply_id FROM forum_replys WHERE thread_id = ".$recent['thread_id']." ORDER BY reply_id ASC");
                if($recentQ->num_rows == 0) {
                    echo'
                                            <div class="col-xs-5">
                                                <div class="forum-latest" style="background-image: url(http://www.habbo.com/habbo-imaging/avatarimage?user=bagkid&gesture=sml)">
                                                    <div class="row">
                                                        <div class="col-xs-2"></div>
                                                        <div class="col-xs-9">
                                                            <div class="forum-latest--title">
                                                                (No Threads)
                                                            </div>
                                                            <p class="forum-latest--timestamp">There\'s nothing here yet.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>';
                }else {
                    echo '         <div class="col-xs-5">
                                                <div class="forum-latest" style="background-image: url(http://www.habbo.com/habbo-imaging/avatarimage?user='.getUsername($recent['user_id'], "forum_replys").'&gesture=sml)">
                                                    <div class="row">
                                                        <div class="col-xs-2"></div>
                                                        <div class="col-xs-9">
                                                            <div class="forum-latest--title">
                                                                <a data-placement="top" data-toggle="tooltip" href="'.FULL_PATH.'/thread/'.$recent['thread_id'].'/'.calculatePage($page_num->num_rows).'#'.$recent['reply_id'].'" title="'.$recent['topic_header'].'">'.truncateString($recent['topic_header']).'</a>
                                                            </div>
                                                            <p class="forum-latest--timestamp"><span data-livestamp="' . $recent['timestamp'] . '"></span> by '.getUsername($recent['user_id'], "forum_replys").'</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>';
                }
                echo'     </div>
                                ';



            }

            ?>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="online-user-count">

    </div>
    <br>
    <div class="sidebar-category">
        <div class="sidebar-category--head">
            <h3><a href="#">Latest Threads</a></h3>
            <h4>Browse newly created threads.</h4>
        </div>
    </div>

    <div class="sidebar-category--content" style="margin-top: 20px">
        <?php
        $Threadquery = $mysqli->query("SELECT * FROM `forum_thread` WHERE deleted = 0 ORDER by thread_id DESC limit 6");

        while($threadResult = $Threadquery->fetch_array()){
            echo'
                                    <div class="row row-spacing">
                      <div class="col-xs-2">
                        <div class="user-avatar--latst-cir" style="background-image: url(\'http://www.habbo.com/habbo-imaging/avatarimage?user='.getUsername($threadResult['user_id'],"forum_thread").'&amp;gesture=sml\')"></div>
                      </div>
                      <div class="col-xs-10">
                        <div class="user-content--latest2" id="user-content--latest">
                          <a href="'. FULL_PATH.'/thread/'.$threadResult['thread_id'].'/">
                            <h1>'.$threadResult['topic_header'].'</h1></a>
                          <a href="'. FULL_PATH.'/thread/'. $threadResult['thread_id'].'/">
                            <p></p><span data-livestamp="'.$threadResult['timestamp'].'"></span></a>
                        </div>
                      </div>
                    </div>
                                    ';
        }
        ?>
    </div>
    <br>

    <div class="sidebar-category">
        <div class="sidebar-category--head">
            <h3><a href="#">Today’s Posts</a></h3>
            <h4>Browse new threads and responses.</h4>
        </div>
    </div>
    <div class="sidebar-category " style="margin-top: 20px">
        <?php
        $postquery = $mysqli->query("
        SELECT forum_replys.timestamp, topic_header, forum_replys.user_id, forum_replys.thread_id, reply_id
        FROM forum_replys
        INNER JOIN forum_thread
        ON forum_replys.thread_id = forum_thread.thread_id
        WHERE forum_replys.timestamp >= UNIX_TIMESTAMP(CURDATE()) AND forum_replys.deleted = false
        ORDER BY forum_replys.timestamp DESC
        LIMIT 6");
        if($postquery->num_rows == 0){
            echo"<div class='alert alert-info'><p>Nothing has been posted today</p></div>";
        }
        while($postResult = $postquery->fetch_array()){
            echo'
                         <div class="row row-spacing">
                            <div class="col-xs-2">
                                <div class="user-avatar--latst-cir" style="background-image: url(\'http://www.habbo.com/habbo-imaging/avatarimage?user='.getUsername($postResult['user_id'], "forum_replys").'&amp;gesture=sml\')"></div>
                            </div>
                            <div class="col-xs-10">
                                <div class="user-content--latest2" id="user-content--latest">
                                    <a href="'.FULL_PATH.'/thread/'. $postResult['thread_id'].'/'.calculatePage($postquery->num_rows).'#'.$postResult['reply_id'].'">
                                        <h1>'.$postResult['topic_header'].'</h1></a>
                                    <a href="#">
                                        <span data-livestamp="'.$postResult["timestamp"].'"></span></a>
                                </div>
                            </div>
                        </div>
                           <br>         
                                    ';

        }

        ?>
    </div><!-- Modal -->
</div>