<?php  
/**
 * Template Name: Join 2
 * script_name: join-2.php
 * parent_script_name: join-1.php 
 * page_name: Join 2
 * application_name: Registration Page
 * business_use: Tightly managed registration page for new users.
 * author: Dave Van Abel
 * dev_site: gvaz.org
 * create_date: 2020-08-13
 * last_update_date: 2022-09-30 
 * base_note: 03-24-21
 * status: Complete
 * license: GNU General Public License version 3 https://opensource.org/licenses/GPL-3.0
*/
/*
09-30-22: Reading -> https://wpappsforthat.com/index.php/registration-join-simulator-slideshow/
09-30-22: Reading -> https://wpappsforthat.com/index.php/registration-join-simulator-code-slideshow/
04-25-22: Cleanup for strictly a SIMULATOR APP!
04-19-22: remodelling for a new WpAppsForThat.Com Simulator
04-21-22: update wp_usermeta, add "wp_usermeta.simulator" for managing member-update-profile.php
		  if membership added as SIMULATOR! This could get complex all through the website.
03-24-21 corrected email domain.
8-29-20 removed admin toolbar display
08-13-20 moving to WpAppsForThat.Com Website.
*/

get_header();   
if ( ! defined( 'ABSPATH' ) ) {die( '-1' );}

global $wpdb, $user_id, $errors, $terms, $domain;
global $simulator; 		
global $username; 			
global $email; 			
global $password; 			
global $confirm_password; 	
global $pwl;
$errors = 0;

//Check whether the user is already logged in  
$user_id = get_current_user_id();

if ($user_id) {
	echo "You do not belong here. Bye!<br>";
	$errors++;
	die;
}
    
if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "submit") {
		// defined fields
		$fields = array(
		'simulator',
		'username',
		'email',
		'password',
		'confirm_password',
		'terms'
	);
	foreach ($fields as $field) {
		//echo "Field = $field, value = $_POST[$field]<br>";
		if (isset($_POST[$field])) $posted[$field] = stripslashes(trim($_POST[$field])); else $posted[$field] = '';
	}
	//die;	
	if ($posted['simulator'] != null ) 			{$simulator 		=  $_POST['simulator'];}
	if ($posted['terms'] != null ) 				{$terms 			=  $_POST['terms'];}
	if ($posted['username'] != null ) 			{$username 			=  $_POST['username'];}
	if ($posted['email'] != null ) 				{$email 			=  $_POST['email'];}
	if ($posted['password'] != null ) 			{$password 			=  $_POST['password'];}
	if ($posted['confirm_password'] != null ) 	{$confirm_password 	=  $_POST['confirm_password'];}
	
	/* Validate Password */
	
	if ($password == $confirm_password) {
		//echo "Passwords match<br>";
	}
	else {
		echo "Password mis-match! Re-entry required.<br>";
		$errors++;
		die;
	}

/* Validate email */
	
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		//echo "$email is a valid email address<br>";
	}
	else {
		echo "$email is not a valid email address!<br>";
		$errors++;
		die;
	}

	/* Additionally you can check whether the domain defines an MX record */
	
	list($a,$domain) = explode("@", $email);
	
	if (checkdnsrr($domain , "MX")) {
		//echo "mx - pass <br>";
	} 
	else {
		echo "$email mx - fail .<br>";
		$errors++;
		die;
	}
	//But this still doesn't guarantee that the mail exists. 
	//The only way to find that out is by sending a confirmation mail.
    
	/* No GvrNo provided, should not happen */

	if(empty($username)) {   
		echo "Please enter a username.<br>";
		$errors++;
		die;
	} 
    else {  	  

    	/* Check username is present and not already in use */ 
    	
		$theuser = $wpdb->get_results( "
		SELECT user_login AS user_id 
			FROM wp_users 
			WHERE wp_users.user_login = '$username'", 
		OBJECT );

		if ($theuser) {
			foreach ( $theuser as $theuser ) {
				$found_user_id 		= $theuser->user_id;
				//$found_user_email 	= $theuser->user_email;
				//echo "FOUND: $theuser->user_id <br>";
			}
			if (($theuser) && ($username === $theuser->user_id)) {
				echo "$theuser->user_id already in use, bye!<br>";
				$errors++;
				die;
			}
			if (!$theuser) {
				//echo "Testing, continuing.<br>";
			}
		}
		//echo "End of Username testing<br>";   

		/* Check email address is present and valid */
		
		$theuser = NULL;
		$theuser = $wpdb->get_results("
			SELECT user_email AS user_email
			FROM wp_users 
			WHERE user_email = '$email' ",OBJECT );
		if (!$theuser ) { 
			//echo "$email available.<br>";
		} 
		else {
			echo "$email is already used.";
			echo "You must use another email.<br>";
			$errors++;
			die;
		}
        // 04-25-22 Check password is valid - there is no control set anywhere in the code,
		// but the initial creation of password lenght does = 8, so we can keep the
		// code here.

        $pwl = strlen($password);
        if ($pwl < 8) {
        	echo "Password must be at least eight characters long<br>";
        	$errors++;
        	die;
        }  
   
   
        /* Check terms of service */
        
        if($_POST['terms'] != "agree") {  
            "You must agree to Terms of Service.<br>";  
            $errors++;
            die;
        }
        
        /* END OF VALIDATIONS */
		
		//echo "TESTING PASSED, READY TO SAVE A NEW WOOD SHOP MEMBER!<br>";
		//echo "Must add new usermeta for wp-members<br>";
		//echo "Need to manage error handling per standards<br>";
		//echo "1-errors=$errors<br>";
   		
		/* CREATE NEW MEMBER + ADD NEW PW_USERMETA RECS */
   		
        if(empty($errors)) {  
			$new_user_id = wp_create_user( $username, $password, $email );  

			/* 
				04-21-22: update wp_usermeta, add "wp_usermeta.simulator" for managing member-update-profile.php
						  if membership added as SIMULATOR! This could get complex all through the website.
				06-30-20: update wp_usermeta, add to comply w/orientation 
			*/
            if ($new_user_id) {
				$pht 		= new DateTime('now', new DateTimeZone('America/Phoenix')); // create an object
				$today_date = $pht->format('Y-m-d');	// format the date
				list($y,$m,$d) = explode("-", $today_date);
				$ny = (int)$y+1;
				$nyr= "$ny-01-01";
				$metas = array( 
					'paid_through'			=> $nyr,
					'active'          		=> '1',
					'expires'         		=> $nyr,
					'associate'				=> '1',
					'show_admin_bar_front'	=> 'false',
					'simulator'				=> $simulator
				);
				
				foreach($metas as $key => $value) {
					update_user_meta( $new_user_id, $key, $value );
				}

				// auto-added on new memberships: myprofile
				
			}
		
		}	// End of adding new records
   
    }  
    //echo "Testing, appears to work.<br>";
}  
//echo "New Member Now Added<br>";
//echo "<br>";
//echo "ID = $new_user_id<br>";
//echo "Username = $username<br>";
//echo "Email Address = $email<br>";
//echo "Password = $password<br>";
//echo "Login Now: Username = $username, Password = $password<br>";
//echo "MyProfile = 1.<br>";
//echo "logged in, see menu = MyProfile, because profile not done.<br>";
//echo "product = associate on registration REMOVED.<br>";
//echo "upon completion of profile. remove myprofile & add associate.<br>";
//echo "must activate new member<br>";
// good ;looking code for update user https://developer.wordpress.org/reference/functions/wp_update_user/
//<?php
//$user_id = 6;
//$website = 'http://example.com';
// 
//$user_data = wp_update_user( array( 'ID' => $user_id, 'user_url' => $website ) );
// 
// *** NEED BELOW ERROR CODE **
//if ( is_wp_error( $user_data ) ) {
//    // There was an error; possibly this user doesn't exist.
//    echo 'Error.';
//} else {
//    // Success!
//    echo 'User profile updated.';
//}
?>

<head>
<link rel=”stylesheet” type=”text/css” href=”style.css”>
<h2>SIMULATOR - Demonstrationg A Tightly Managed Registration App For New Users</h2>
</head>
<h3>Initial Registration is Now Complete!</h3>
<div style="overflow-x:auto;">

<table name="manual-memberpayments-1" id="customers" >
	<div class="row">		
		<!-- GvrNo -->
		<tr> 
			 <div class="col-50">
				<td><b>Username</b></td>
			 </div>			     
			 <div class="col-50">
				<td> 
					<?php echo $username;?>
				</td>
			 </div>			     
		</tr>
		<!-- Email -->	
		<tr> 
			 <div class="col-50">
				<td><b>>Email</b></td>
			 </div>			     
			 <div class="col-50">
				<td>
					<?php echo $email;?>
				</td>
			 </div>			     
		</tr>
		<!-- Password -->	
		<tr> 
			 <div class="col-50">
				<td><b>Password</b></td>
			 </div>			     
			 <div class="col-50">
				<td><?php echo $password;?></td>
			 </div>			     
		</tr>
		<!-- PRINT IT -->
		<tr>  
			<div class=50>
				<td >
					<b>Print Page</b>
				</td >
				<td >
					Use your browser to print this page.
				</td >
			</div>
		</tr>
	</div>
</table> 
</div>								
<h3><a href="<?php site_url(); ?>/index.php/login/">You may now login to complete your registration</a>.</h3>
