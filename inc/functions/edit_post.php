<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 22/01/2017
 * Time: 9:30 PM
 */

require_once("../../models/config.php");
//Prevent the user visiting the logged in page if he is not logged in
if(!isUserLoggedIn()) { header("Location: /index.php"); die(); }


             $post_id = intval($_POST['post_id']);

            $stmt = $mysqli->query("SELECT * FROM forum_replys WHERE reply_id = $post_id");
            $editR = $stmt->fetch_array();

            if ($stmt->num_rows == 0) {
                echo "An error has occurred. Please reload the page and if the problem persists, contact a forum administer.";
            } else {
                ?>

                <form method="post" id="editForm" action="" novalidate>
                    <div class="table-responsive">
                        <input type="hidden" id="post_id" value="<?php echo $post_id; ?>">
                        <textarea id="postContents" name="threadContents" rows="8"><?


                            echo preg_replace('@\x{FFFD}@u', '', $editR['reply_content']);


                            ?></textarea>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="editButton" name="editBtn">Update Post</button>
                    </div>
                </form>
                <?php include '../../models/footer.inc.php'; ?>
                <script>
                    $(document).ready(function() {
                        $("#editForm").submit(function(e){
                            e.preventDefault();
                            $("#editButton").prop('disabled', true);
                            var postId = $("#post_id").val();
                            var replyContent = $("#postContents").bbcode();
                            var dataString = 'replyContent='+ escape(replyContent) + '&post_id='+ postId;
                            // AJAX Code To Submit Form.
                            $.ajax({
                                type: "POST",
                                url: "/forum/inc/functions/edit.php",
                                data: dataString,
                                dataType: "text",
                                success: function(data) {
                                    if (data == "same") {
                                        new PNotify({
                                            title: 'Oh No!',
                                            text: 'Everything looks the same',
                                            type: 'error'
                                        });
                                        $("#editButton").prop('disabled', false);
                                    }  else {
                                        new PNotify({
                                            title: 'Awesome!',
                                            text: 'Successfully updated this post',
                                            type: 'success'
                                        });
                                        $("#edit-modal").modal('hide');
                                        $("#editButton").prop('disabled', false);
                                        $("div #" +postId +" .postContentDiv").html($("#postContents").htmlcode());
                                    }

                                }

                            });
                        });
                        $("#postContents").wysibb();
                    });
                </script>

                <?php
            }

