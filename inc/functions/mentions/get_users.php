<?php
/**
 * Created by PhpStorm.
 * User: JEFFH14
 * Date: 09/12/2016
 * Time: 5:29 PM
 */

require_once('../../../models/config.php');

$user = $mysqli->query("SELECT user_name from portalusers");

$rows = array();

while($lol = $user->fetch_array()){
    $rows[] = $lol['user_name'];
}

echo json_encode($rows);

