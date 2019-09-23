<?php

/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 12/06/2017
 * Time: 6:50 PM
 * Desc: This class is used for calculating the users statistics amongst the varies Logs and Forum features.
 */
class user_stats
{

    var $user_id;
    var $user_name;
    var $type;

    public function __construct(){}

    public function calculate($username, $user_id){
        $this->user_name = $username;
        $this->user_id = $user_id;
    }
    public function promotion($type){
        global $mysqli;
        return $mysqli->query("SELECT * from Promotion WHERE user_id = '$this->user_id'")->num_rows;
    }
    public function trainer($type){
        global $mysqli;
        return $mysqli->query("SELECT * FROM Trainer WHERE user_id = '$this->user_id' ")->num_rows;
    }
    public function fired($type){
        global $mysqli;
        return $mysqli->query("SELECT * FROM fired_log WHERE user_id = $this->user_id ")->num_rows;
    }
    public function sold_ranks($type){
        global $mysqli;
        return $mysqli->query("SELECT * FROM sold_ranks WHERE Sellers_name = '$this->user_name' ")->num_rows;
    }
    public function discipline($type){
        global $mysqli;
        return $mysqli->query("SELECT * FROM Demotions WHERE Demoted_name = '$this->user_name' ")->num_rows;
    }
    public function promo_received($type){
        global $mysqli;
        return $mysqli->query("SELECT * FROM promotion WHERE promoted_name = '$this->user_name' ")->num_rows;
    }
    public function forumTotalPosts(){
        global $mysqli;
        return $mysqli->query("SELECT * from forum_replys where user_id = $this->user_id")->num_rows;
    }
    public function totalLikesRec(){
        global $mysqli;
        return $mysqli->query("SELECT reciever_user_id FROM forum_likes WHERE reciever_user_id = $this->user_id")->num_rows;
    }
}