 <?php
/**
 * Template Name: Member Update Profile 1
 * script_name: member-update-profile-1.php
 * parent_script_name: member-update-profile.php             
 * page_name: Member Update Profile 1
 * application_name: Mgr Member Profile
 * business_use: Mgr Member Profile
 * author: Dave Van Abel
 * dev_site: gvaz.org
 * create_date: 2020-06-28
 * last_update_date: 2022-04-22
 * base_note: Member Update Profile
 * status: complete 
 * license: GNU General Public License version 3 https://opensource.org/licenses/GPL-3.0
*/

/* 
 * General Notes
 * 04-21-22: update wp_usermeta, add "wp_usermeta.simulator" for managing member-update-profile.php
	if membership added as SIMULATOR! This could get complex all through the website.
	04-21-22: THOUGHTS ->
		I am not sure if a simulator member should every go beyond "wp_associate"
		Might need a renewal policy down-the-road, which could be an upgrade to the WS renewals (?)
		An entire suite could be developed, if I can get an audience.
 * 08-27-20 App ready for release!
*/
get_header();

if ( ! defined( 'ABSPATH' ) ) {die( '-1' );}

//global $wpdb; 

global $ID;
global $tbl_name;
global $tbl_meta;
global $username;
global $first_name;
global $last_name;
global $displayname;
global $user_email;

global $addr1;
global $addr2;
global $city;
global $thestate;
global $zip;
global $wp_myprofile;
global $wp_associate;
global $wp_fullmembership;
global $phone1;
global $theuser;
global $empty_field;
$empty_field = 0;
$tbl_name = $wpdb->prefix.'users';
$tbl_meta = $wpdb->prefix.'usermeta';
$errors = new WP_Error();

if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "submit") {
		$fields = array(
			'id',
			'username',
			'first_name',
			'last_name',
            'display_name',
			'user_email',
			'orig_email',
			'phone1',
			'addr1',
			'addr2',
			'city',
			'thestate',
			'zip',
			'wp_myprofile',
			'wp_associate',
			'wp_fullmembership'
		);
	foreach ($fields as $field) {
		//echo "Field = $field, value = $_POST[$field]<br>";
		if (isset($_POST[$field])) $posted[$field] = stripslashes(trim($_POST[$field])); else $posted[$field] = '';
	}
	
	if ($posted['id'] != null ) 				{$ID 				=  $_POST['id'];}
	if ($posted['username'] != null ) 			{$username 			=  $_POST['username'];}
	if ($posted['first_name'] != null ) 		{$first_name 		=  $_POST['first_name'];}
	if ($posted['last_name'] != null ) 			{$last_name 		=  $_POST['last_name'];}
	if ($posted['display_name'] != null ) 		{$displayname 		=  $_POST['display_name'];}
	if ($posted['user_email'] != null ) 		{$user_email 		=  $_POST['user_email'];}
	if ($posted['orig_email'] != null ) 		{$orig_email 		=  $_POST['orig_email'];}
	if ($posted['phone1'] != null ) 			{$phone1 			=  $_POST['phone1'];}
	if ($posted['addr1'] != null ) 				{$addr1 			=  $_POST['addr1'];}
	if ($posted['addr2'] != null ) 				{$addr2 			=  $_POST['addr2'];}
	if ($posted['city'] != null ) 				{$city 				=  $_POST['city'];}
	if ($posted['thestate'] != null ) 			{$thesttae 			=  $_POST['thestate'];}
	if ($posted['zip'] != null ) 				{$zip 				=  $_POST['zip'];}
	if ($posted['wp_myprofile'] != null ) 		{$wp_myprofile 		=  $_POST['wp_myprofile'];}
	if ($posted['wp_associate'] != null ) 		{$wp_associate 		=  $_POST['wp_associate'];}
	if ($posted['wp_fullmembership'] != null ) 	{$wp_fullmembership =  $_POST['wp_fullmembership'];}
	else {
		$errors->add('empty_username', __('<strong>Notice</strong>: Please enter your usernname.'));		
	}
}
/* Errror Message for Empty Fields (addr2 an exception) */

/* 04-22-22 SIMULATOR comment out and try to prevent required! */
//if (!$_POST['first_name']) {$empty_field = 1;}
//elseif (!$_POST['last_name']) {$empty_field = 2;}
//elseif (!$_POST['user_email']) {$empty_field = 3;}
//elseif (!$_POST['phone1']) {$empty_field = 4;}
//elseif (!$_POST['addr1']) {$empty_field = 5;}
//elseif (!$_POST['city']) {$empty_field = 6;}
//elseif (!$_POST['thestate']) {$empty_field = 7;}
//elseif (!$_POST['zip']) {$empty_field = 8;}
 
if ($empty_field >= 1) {
	echo "<h3><font color='red'>All fields must be filled in (except Address 2) before saving. 
	Sorry previouosly entered data is not saved for you.</font><br></h3>";
	echo "<h3><a href='/index.php/member-update-profile/'>Please re-enter</a>.</h3></br>";
	die;
}

/* Email Validation */

if ($orig_email != $user_email) {
	if (is_email( $user_email )) {
		/* CHECK FOR AVAILABLE EMAIL ADDRESS */
		//echo "Email test 1<br>";

		/* Validate email */
		
		if (filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
			//echo "Email test 2<br>";
		}
		else {
			echo "<h3><font color='red'>$user_email is not a valid email address!</font><br></h3>";
			echo "<h3><a href='/index.php/join/'>Please use another email</a>.</h3></br>";
			die;
		}

	/* Additionally you can check whether the domain defines an MX record */
	
	list($a,$domain) = explode("@", $user_email);
	
	if (checkdnsrr($domain , "MX")) {
		//echo "Email test 3<br>";
	} 
	else {
		echo "<h3><font color='red'>$user_email mx - fail!</font><br></h3>";
		echo "<h3><a href='/index.php/join/'>Please use another email</a>.</h3></br>";
		die;
	}
		/* Check against wp_users.user_email */
		
		$theuser = NULL;
		$theuser = $wpdb->get_results("SELECT user_email FROM wp_users WHERE user_email = '$user_email'",OBJECT );
		if (!$theuser ) {
			//echo "Email test 4<br>";
			//echo "$user_email available.<br>";
		} else {
			echo "<h3><font color='red'>$user_email is already used.</font><br></h3>";
			echo "<h3><a href='/index.php/mgr-member-profile/'>Please use another email</a>.</h3></br>";
			die;
		}
		/* End */
	} else {
		echo "<h3><font color='red'>$user_email is an invalid email address.</font><br></h3>";
		echo "<h3><a href='/index.php/mgr-member-profile/'>Please enter a valid email</a>.</h3></br>";
		die;
	}
}

/* End Email Validation*/

	if ($orig_email != $user_email) {
		if (is_email( $user_email )) {
			// Update wp_users (only)
			$userdata = array( 
				'ID' => $ID,
				'user_email' => $user_email
			);
		}
	}
	wp_update_user( $userdata );

	// Update wp_usermeta (only)
	$metas = (array) null;
	$metas = array( 
		'first_name'	=> $first_name,
		'last_name'		=> $last_name,
		'display_name'	=> $displayname,
		'phone1'		=> $phone1,
        'addr1'			=> $addr1,
		'addr2'			=> $addr2,
		'city'			=> $city,
		'thestate'		=> $thestate,
		'zip'			=> $zip
	);
	
	foreach($metas as $key => $value) {
		update_user_meta( $ID, $key, $value );
	}
	if ($wp_myprofile) {
		$product_meta = 'associate';
		wpmem_set_user_product($product_meta, $ID, false);
		
		$product_meta = 'myprofile';
		wpmem_remove_user_product($product_meta, $ID, false);
	}
	/* Remove associate if fullmembership set */
	
	if (($wp_fullmembership >=1 ) && ($wp_associate >=1 )) {
		$product_meta = 'associate';
		wpmem_remove_user_product($product_meta, $ID, false);		
	}
 
?>

<h2>Update Member Profile Complete</h2>

<h3><a href="<?php site_url(); ?>/index.php/">Return to Main Menu</a>.</h3>
