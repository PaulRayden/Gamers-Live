<?php
error_reporting(0);


ob_start();
$inc_path = $_SERVER['DOCUMENT_ROOT'];
$inc_path .= "/config.php";
include_once($inc_path);
include_once("".$conf_ht_docs_gl."/files/check.php");
ob_end_clean();
?>
<!-- topmenu -->
<div class="topmenu">
    <ul class="dropdown">
        <li><a href="<?=$conf_site_url?>/browse/lol/"><span>LoL</span></a></li>
        <li><a href="<?=$conf_site_url?>/browse/dota2/"><span>Dota 2</span></a></li>
        <li><a href="<?=$conf_site_url?>/browse/hon/"><span>HoN</span></a></li>
        <li><a href="<?=$conf_site_url?>/browse/sc2/"><span>SC 2</span></a></li>
        <li><a href="<?=$conf_site_url?>/browse/wow/"><span>WoW</span></a></li>
        <li><a href="<?=$conf_site_url?>/browse/callofduty/"><span>Call Of Duty</span></a></li>
        <li><a href="<?=$conf_site_url?>/browse/minecraft/"><span>Minecraft</span></a></li>
        <li><a href="<?=$conf_site_url?>/browse/other/"><span>Other</span></a></li>
        <li><a href="<?=$conf_site_url?>/events/"><span>Events</span></a></li>
        <li><a href="#"><span>More</span></a>
            <ul>
                
                <li><a href="<?=$conf_site_url?>/company/support/"><span>Contact</span></a></li>
                <li><a href="<?=$conf_site_url?>/account/partner/"><span>Partner</span></a></li>
            </ul>
        </li>
    </ul>
</div>
<!--/ topmenu -->