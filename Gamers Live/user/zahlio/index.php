<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php

error_reporting(0);

include_once("http://www.gamers-live.net/analyticstracking.php");

// we first get data from our mysql database
$database_url = "127.0.0.1";
$database_user = "root";
$database_pw = "";

$dir_name = basename(__DIR__);

// connect to database
$connect = mysql_connect($database_url, $database_user, $database_pw) or die(mysql_error());
			
// select the database we need
$select_db = mysql_select_db("live", $connect) or die(mysql_error());
		
			
$result = mysql_query("SELECT * FROM channels WHERE channel_id='$dir_name'");
$row = mysql_fetch_array($result);

$channel_id = $row['channel_id'];
$server_rtmp = $row['server_rtmp'];
$game = $row['game'];
$views = $row['views'] + 1;
$online = $row['online'];
$title = $row['title'];
$info1 = chunk_split($row['info1'], 150, '<br>');
$info2 = chunk_split($row['info2'], 150, '<br>');
$info3 = chunk_split($row['info3'], 150, '<br>');
$featured = $row['featured'];
$banned = $row['banned'];
$viewers = $row['viewers'] + 1;
$subscribers = $row['subscribers'];
$ads = $row['ads'];
$donate = $row['donate'];
$videoad = $row['ad_level'];
$ads_channel = $row['adsense_video_channel'];
$raw_chat_key = $row['chat_key'];
$to_disable_ads = $row['ads_disable'];

// get options form mysql

$options_get = mysql_query("SELECT * from channel_options WHERE active='1'") or die(mysql_error());
$options = mysql_fetch_array($options_get);

// show ads if they are enabled = 1

if($donate == "1"){
    $donate_butten = '<a href="http://www.gamers-live.net/store/tip/?channel='.$channel_id.'&tip=true" class="button_link btn_green"><span>Tip the streamer</span></a>';
}

session_start();

if ($_SESSION['access'] != true) {
	$chat_msg = "You need to be logged in to chat.";
	$login_box = ' <div class="top_login_box"><a href="http://www.gamers-live.net/account/login/?link='.$channel_id.'">Sign in</a><a href="http://www.gamers-live.net/account/register/">Register</a></div>';
}else{
	$login_box = '<div class="top_login_box"><a href="http://www.gamers-live.net/account/logout/">Logout</a><a href="http://www.gamers-live.net/account/settings/">Settings</a></div>';
	$name = $_SESSION['channel_id'];

    // we now check if the user is banned
    $check_if_chat_banned = mysql_query("SELECT * FROM chat_bans WHERE channel_id='$channel_id' AND user_id='$name' AND banned='1'") or die(mysql_error());
    $count = mysql_num_rows($check_if_chat_banned);
    if($count >= 1){
        $chat_ban = 'true';
        $name = null;
        $ban_msg = "You are banned from the chat...";
    }else{
        $chat_ban = 'false';
    }

    // we now check if the user has donated enough to disable ads
    $check_donation_user = mysql_query("SELECT SUM(value) as total_donation FROM tips_payza WHERE user='$name' AND paid='1' AND streamer='$channel_id'") or die(mysql_error());
    $check_donation_user_row = mysql_fetch_array($check_donation_user);
    $total_donated = $check_donation_user_row['total_donation'];

    if($total_donated >= $to_disable_ads && $to_disable_ads != "0"){
        $ads = "0";
    }

    $avatar_url = "http://www.gamers-live.net/user/".$name."/avatar.png";
    $profile_url = "http://www.gamers-live.net/account/channel/chat/ban.php?username=".$name."&channel=".$channel_id."";
	$subscribe = '<a href="http://www.gamers-live.net/account/sub/?channel='.$channel_id.'" class="button_link"><span>Subscribe</span></a>';
}

if($ads == "1"){
    // then we show them
    $ad1 = $options['ad1'];
    $chatad = $options['chatad1'];
    $chatad2 = $options['chatad2'];
}else{
    // we dont show ads
    $videoad = "none";
}
// offline rediction and updating
$offline_url = "window.location = '?status=offline'";
$status = $_GET['status'];

if($status != "offline"){
    $status = 'online';
}

if($status == "offline"){
    // then our stream is offline
    $offline_url = ""; // stop redicting
}else{
    // we will also then add one to the views
    $status = 'online';
    $add_view = mysql_query("UPDATE channels SET views='$views' WHERE channel_id='$channel_id'");
}

if($banned == "1"){
    // account is then banned
    header( 'Location: http://www.gamers-live.net/banned/' ) ;
}

$chat = $_GET['chat'];

if($chat == 'true' && $status == 'online'){
    $width = "650";
    $height = "383";
    $chat_display = '

<iframe src="http://www.gamers-live.net/chat/?channel='.$channel_id.'" height="380px" align="right">
  <p>Your browser does not support iframes.</p>
</iframe>

    ';
}else{
    $width = "960";
    $height = "540";
    $chat_display = "";
}

?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Description" content="<?=$title?>" />
<title>Gamers Live - <?=$channel_id?></title>
<link href="http://www.gamers-live.net/style.css" media="screen" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="http://www.gamers-live.net/js/jquery.min.js"></script>
<script type="text/javascript" src="http://www.gamers-live.net/js/preloadCssImages.js"></script>
<script type="text/javascript" src="http://www.gamers-live.net/js/jquery.color.js"></script>

<script type="text/javascript" language="JavaScript" src="http://www.gamers-live.net/js/general.js"></script>
<script type="text/javascript" language="JavaScript" src="http://www.gamers-live.net/js/jquery.tools.min.js"></script>
<script type="text/javascript" language="JavaScript" src="http://www.gamers-live.net/js/jquery.easing.1.3.js"></script>

<script type="text/javascript" language="JavaScript" src="http://www.gamers-live.net/js/slides.jquery.js"></script>
<script type="text/javascript" src="http://www.gamers-live.net/files/flowplayer-3.2.11.min.js"></script>

<link rel="stylesheet" href="http://www.gamers-live.net/css/prettyPhoto.css" type="text/css" media="screen" />
<script src="http://www.gamers-live.net/js/jquery.prettyPhoto.js" type="text/javascript"></script>

<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="http://www.gamers-live.net/css/ie.css" />
<![endif]-->
</head>

<script type="text/javascript">
    function popchat(url) {
        popupWindow = window.open(
                url,'popUpWindow','height=700,width=400left=10,top=10,resizable=yes,scrollbars=yes,toolbar=no,menubar=no,location=no,directories=no,status=yes')
    }
</script>

<body>
<div class="body_wrap thinpage">

<div class="header_image" style="background-image:url(header.png)">&nbsp;</div>

<div class="header_menu">
	<div class="container">
		<div class="logo"><a href="http://www.gamers-live.net/"><img src="http://www.gamers-live.net/images/logo.png" alt="" /></a></div>
        <?=$login_box?>
        <div class="top_search">
        	<form id="searchForm" action="http://www.gamers-live.net/browse/" method="get">
                <fieldset>
                	<input type="submit" id="searchSubmit" value="" />
                    <div class="input">
                        <input type="text" name="s" id="s" value="Type & press enter" />
                    </div>                    
                </fieldset>
            </form>
        </div>
        
    <?php
        // menu included if we should get future changes
        include 'http://www.gamers-live.net/user/inc/menu.php';
    ?>

    </div>
</div>     	
<!--/ header -->

<!-- middle -->
<div class="middle full_width">
    <div class="container_12">

        <div class="back_title">
            <div class="back_inner">
            <a href="http://www.gamers-live.net/"><span>Home</span></a>
            </div>
        </div>


        <!-- content -->
        <div class="content">
            <br />
            <h1><?=$title?><br /><?=$subscribe?><?=$donate_butten?><?php if($_SESSION['admin'] == true){
            echo '<a href="http://www.gamers-live.net/account/admin/user.php?channel='.$channel_id.'" class="button_link btn_red"><span>ADMIN EDIT</span></a>';
            }
            ?>
            </h1>
            <div class="col col_2_3">
                <a style="display:block;width:<?=$width?>px;height:<?=$height?>px;margin:10px auto" id="stream">
                </a>
            </div>
            <div class="col col_1_3"></div>
                <p id="chat_display" style="margin-left: 5px">
                    <center>
                    <?=$chat_display?>
                    </center>
                </p>
            </div>
        <div class="clear"></div>
                <p align="right" id="chat_show_hide">
                    <b><?=$ban_msg?></b>
                    <a href="?status=<?=$status?>&chat=false" onclick="JavaScript:popchat('http://www.gamers-live.net/chat/?channel=<?=$channel_id?>');"" class="button_link"><span>Windowed Chat</span></a>
                    <a href="?status=<?=$status?>&chat=<?php if($chat == 'true'){ echo 'false';}else{ echo 'true';} ?>" class="button_link"><span><?php if($chat == 'true' && $status == 'online'){echo 'Hide Chat';}else{ echo 'Show Chat';}?></span></a>
                </p>
            <script type="text/javascript">
                flowplayer("stream", "http://www.gamers-live.net/files/flowplayer.commercial-3.2.11.swf",
                    {

                        clip: {
                            url: '<?=$channel_id?>',
                            live: true,
                            provider: 'rtmp',
                            ads: "<?=$videoad?>"
                        },
                        plugins: {

                            controls: {
                                autoHide: "never"
                            },

                            rtmp: {
                            url: 'http://www.gamers-live.net/files/flowplayer.rtmp-3.2.11.swf',
                            netConnectionUrl: '<?=$server_rtmp?><?=$channel_id?>'
                            },

                            adsense: {
                                url: "http://www.gamers-live.net/files/bigsool.adsense-2.0.swf",
                                publisherId: "ca-video-pub-2504383399867703",
                                channel: "<?=$ads_channel?>"
                            }
                        },
                        onError: function(err) {
                            this.unload();
                            $('#stream').html('<img src="offline_img.png" height="540" width="960" />').fadeIn('fast');
                            <?=$offline_url?>
                        }
                    }
                );
            </script>
            <center><?=$ad1?></center>
            <h3 class="toggle box">Stream Stats<span class="ico"></span></h3>
                <div class="toggle_content boxed" style="display: block;">
                    <div class="styled_table table_white">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="width:25%">Game</th>
                                <th style="width:25%">Viewers</th>
                                <th style="width:25%">Total Views</th>
                                <th style="width:25%">Subscribers</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?=$game?></td>
                                <td><?=$viewers?></td>
                                <td><?=$views?></td>
                                <td><?=$subscribers?></td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            <br />
            <div class="tabs_framed small_tabs">

            <ul class="tabs">
                <li class="current"><a href="#tabs_1_1">Stream Information</a></li>
                <li class=""><a href="#tabs_1_2">Streamer Information</a></li>
                <li class=""><a href="#tabs_1_3">Additional Information</a></li>
            </ul>

            <div id="tabs_1_1" class="tabcontent" style="display: none;">
                <?=$info1?>
            </div>

            <div id="tabs_1_2" class="tabcontent" style="display: none;">
                <p><?=$info2?></p>
            </div>

            <div id="tabs_1_3" class="tabcontent" style="display: block;">
                <?=$info3?>
            </div>
        </div>
        <!--/ content -->
        <div class="clear"></div>
    </div>
</div>
<!--/ middle -->

<div class="footer">
    <div class="footer_inner">
        <div class="container_12">

            <div class="grid_8">
                <h3>Hostse.net</h3>

                <div class="copyright">
                &copy; 2013 GAMERS LIVE. An Hostse.net production. All Rights Reserved. <br /><a href="http://www.gamers-live.net/company/legal/">Terms of Service</a> - <a href="http://www.gamers-live.net/company/support/">Contact</a> -
                <a href="http://www.gamers-live.net/company/legal/">Privacy guidelines</a> - <a href="http://www.gamers-live.net/company/support/">Advertise with Us</a> - <a href="http://www.gamers-live.net/company/about/">About Us</a></p>
                </div>
            </div>

            <div class="grid_4">
                <h3>Follow us</h3>
                <div class="footer_social">
                    <a href="http://www.gamers-live.net/facebook/" class="icon-facebook">Facebook</a>
                    <a href="http://www.gamers-live.net/twitter/" class="icon-twitter">Twitter</a>
                    <a href="http://www.gamers-live.net/rss/" class="icon-rss">RSS</a>
                    <div class="clear"></div>
                </div>
            </div>

            <div class="clear"></div>
        </div>
    </div>
</div>
</div>
</body>
</html>