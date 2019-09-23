<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 07/12/2016
 * Time: 7:58 PM
 */

include ("../../models/config.php");

if(isset($_POST)) {
    $user_id = $loggedInUser->user_id;
    $post_id = abs(intval($_POST['item_id']));


// check if this user ip has liked this item or not
    $query = $mysqli->prepare("SELECT * FROM `forum_flagged_posts` WHERE `reporter_id` = ? AND `post_id` = ? LIMIT 1");
    $query->bind_param('ii', $user_id, $post_id);

    $query->store_result();

    $check = $query->num_rows;
    $query->close();

    if ($check == 0) {
// if not liked before insert the liked item ID and the user IP to database
        $add = $mysqli->prepare("INSERT INTO forum_flagged_posts (`user_id`, `post_id`, ip) VALUES (?,?,?)");

        $add->bind_param('iii', $loggedInUser->user_id, $post_id, ip());
        $add->execute();

        $add->close();

        if ($add) {
// after adding the like (vote) to database, consume the number of item's likes
            $stmt = $mysqli->prepare("SELECT * FROM `forum_flagged_posts` WHERE post_id = ?");
            $stmt->bind_param('i',$post_id);

            $stmt->store_result();

            $numberOfLikes = $stmt->num_rows;
            $stmt->close();

            sleep(1);
                // return new number of item's likes instead of the current likes number.
            return 1;
        }
    } else {
            // if this user has liked the item before return 0 value
        return 0;
    }
} else {
        // if POST not isset return 0 value
    return 1;
}