<?php

session_start();

error_reporting(0);

include_once("".$conf_site_url."/analyticstracking.php");
if ($_SESSION['access'] != true) {
    header( 'Location: '.$conf_site_url.'/account/login/?msg=Please login to view this page' ) ;
    exit;
}

$inc_path = $_SERVER['DOCUMENT_ROOT'];
$inc_path .= "/config.php";
include_once($inc_path);

$dir_name = basename(__DIR__);

// connect to database


// select the database we need


$login_name = $_SESSION['channel_id'];

$mod_name = $_POST['mod'];
$channel_id = $_POST['channel'];

// now we check the database

$staff_check = mysql_query("SELECT * FROM channels WHERE channel_id='$mod_name' AND admin='1'");
$staff_check_count = mysql_num_rows($staff_check);
if($staff_check_count == 1){
    $is_admin = true;
}

// we need to see if user is mod
$get_auth_user = mysql_query("SELECT * FROM chat_mods WHERE user_id='$login_name' AND channel_id='$channel_id'") or die(mysql_error());
$get_auth_rows_user = mysql_fetch_array($get_auth_user);

if(($get_auth_rows_user['admin'] = "1") || ($login_name == $channel_id) || ($is_admin != true)){
    $is_admin = true;
}else{
    header( 'Location: '.$conf_site_url.'/account/channel/chat/ban.php?channel='.$channel_id.'&msg=You are not allowed to add moderators on this channel' ) ;
    exit;
}



if($is_admin == true){
    // we add moderator
    $add_mod = mysql_query("INSERT INTO chat_mods (channel_id, user_id, moderator) VALUES ('$channel_id', '$mod_name', '1')") or die(mysql_error());
    header( 'Location: '.$conf_site_url.'/account/channel/chat/ban.php?channel='.$channel_id.'&msg=You have added a new moderator to the chat of your channel' ) ;
    exit;
}else{
    header( 'Location: '.$conf_site_url.'/account/channel/chat/ban.php?channel='.$channel_id.'&msg=You are not allowed to add moderators on this channel' ) ;
    exit;
}

?>