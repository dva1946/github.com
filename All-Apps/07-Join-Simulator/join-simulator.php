<?php
/**
 * Template Name: Join Simulator
 * script_name: join-simulator.php          
 * parent_script_name: 
 * page_name: Join Simulator
 * application_name: Join Simulator
 * business_use: Tightly managed registration page for new users.
 * author: Dave Van Abel
 * dev_site: wpappsforthat.com
 * create_date: 2020-08-13
 * last_update_date: 2022-09-28
 * base_note: Utility for creating memberships
 * status: Complete
 * license: GNU General Public License version 3 https://opensource.org/licenses/GPL-3.0
 * */
// Copyright Dave Van Abel, 2020 / 2021 / 2022
/*
09-30-22: Reading -> https://wpappsforthat.com/index.php/registration-join-simulator-slideshow/
09-30-22: Reading -> https://wpappsforthat.com/index.php/registration-join-simulator-code-slideshow/
09-28-22: Spelling correction.
04-25-22: If this app is hidden from a LOGGED IN MENU, it can not run for a logged in user.
04-21-22: update wp_usermeta, add "wp_usermeta.simulator" for managing member-update-profile.php
		  if membership added as SIMULATOR! This could get complex all through the website.
04-19-22 remodelling for a new WpAppsForThat.Com Simulator
	Using join-simulator, (join not used) join-1 & join-2 for live registration on WpAppsForThat.Com
	Objective is:
		1) make a simple & safe App for demoing & 
		2) Could be packaged like this from these 3/4 pieces of code.
04-19-22 Made copies of join-simulator, join, join-1 & join-2 & placed in sub-folder = 1-joinOriginalsFrom-041922 (not zipped)
03-24-21 corrected email domain.
08-29-20 removed admin toolbar display
08-13-20 moving to WpAppsForThat.Com Website.
*/

if ( ! defined( 'ABSPATH' ) ) {die( '-1' );}

/* Retrieve Selected Values */

global $wpdb;
global $post; 
global $url;
global $random;
global $now_time;
global $sim_email;
global $sim_text;
global $url2;
global $min;
global $max;

get_header();
	
//** 04-20-22 Working on rand syntax: **/
$random = null;		// 04-20-22 not sure this does anything.
$min = 1000;
$max = 9999;
$random = rand($min, $max);
//echo "random: $random<br>";
//**************************************/

$theuser = $wpdb->get_results( "
SELECT user_login AS userno FROM wp_users WHERE user_login = $random", OBJECT );
if (!empty($theuser)) {
	echo "Random generated number detected as a duplicate member value. Try again.<br>";
	die;
}
$sim_email 	= "$random" . "@wpappsforthat.com";
$sim_text 	= "$random" . "-New+Member";
$url2 = "join-1?simulator=1&simulator_userno=$random&simulator_email=$sim_email&simulator_text=$sim_text";
//echo "url2=$url2<br>";
?>	
<head>
<link rel=”stylesheet” type=”text/css” href=”style.css”>
<h2>SIMULATOR - Demonstrates A Tightly Managed Registration For New Members</h2>
</head>
<div style="overflow-x:auto;">
<table id="customers">
<tbody>
<tr>
	<td colspan="2">
    	This simulator will allow you to create a user registration. 
		Next login to complete your profile and save it. 
		Now the full site menu will be displayed.
    </td>
</tr>
<tr>
    <td colspan="2">
    	<b>NOTE</b>: This simulator can be upgraded to a functional JOIN APP.
    </td>
</tr>
<tr>
	<td style="text-align: center;">
		<b>
			<a href="<?php site_url(); ?>/index.php/<?php echo $url2?>/">Click To Begin The Simple Process!</a>
		<b>
	</td>
</tr>
</div>   
</tbody>
</table>
</div>                                    