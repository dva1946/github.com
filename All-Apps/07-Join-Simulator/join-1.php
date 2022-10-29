<?php  
/**
 * Template Name: Join 1
 * script_name: join-1.php
 * parent_script_name: 
 * page_name: Join 1
 * application_name: Registration Page
 * business_use: Tightly managed registration page for new users.
 * author: Dave Van Abel
 * dev_site: gvaz.org
 * create_date: 2020-08-13
 * last_update_date: 2022-09-30
 * base_note: Utility for creating memberships
 * status: Complete
 * license: GNU General Public License version 3 https://opensource.org/licenses/GPL-3.0
*/

/*
09-30-22: Reading -> https://wpappsforthat.com/index.php/registration-join-simulator-slideshow/
09-30-22: Reading -> https://wpappsforthat.com/index.php/registration-join-simulator-code-slideshow/
04-25-22: Cleanup for strictly a SIMULATOR APP!
04-21-22: update wp_usermeta, add "wp_usermeta.simulator" for managing member-update-profile.php
		  if membership added as SIMULATOR! This could get complex all through the website.
04-19-22 remodelling for a new WpAppsForThat.Com Simulator
10-17-21 revise to encapsulate if POST
	10-24-21 I believe this change should
	not be done, as it was defined to prevent pirating if it.
	However, not rolled back yet (10-24-21)
	The simpulator code: join-sinulator.php, join-1.php & join-2.php
	The non-simulator code: join.php, join-1.php & join-2.php
03-24-21 corrected email domain.
8-29-20 removed admin toolbar display
08-13-20 moving to WpAppsForThat.Com Website.
*/

get_header();   
if ( ! defined( 'ABSPATH' ) ) {die( '-1' );}

global $wpdb;
global $errors;
global $terms;
global $n95;  
global $simulator;
global $simulator_userno;
global $simulator_email;
global $simulator_text;
global $ID;

//echo "Test: Line 37<br>";
//die;

if (isset($_GET['simulator'])) {
	$simulator 	= 1;
	$terms 		= "agree";
	$n95 		= "StopSaw";
	$simulator_userno 	= $_GET['simulator_userno'];
	$simulator_email 	= $_GET['simulator_email'];
	$simulator_text 	= $_GET['simulator_text'];
	//echo "$simulator_userno, $simulator_email, $simulator_text <br>";
	
	$theuser = $wpdb->get_results( "
	SELECT user_login AS userno FROM wp_users WHERE user_login = $simulator_userno", OBJECT );
	if (!empty($theuser)) {
		echo "Random generated number detected as a duplicate member value. Try again.<br>";
		die;
	}
}
// 10-17-21 revise to encapsulate if POST 
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "submit") {
	// defined fields
	$fields = array(
	'terms',
	'n95'
	);
	foreach ($fields as $field) {
		//echo "Field = $field, value = $_POST[$field]<br>";
		if (isset($_POST[$field])) $posted[$field] = stripslashes(trim($_POST[$field])); else $posted[$field] = '';
	}	
	if ($posted['terms'] != null ) 	{$terms =  $_POST['terms'];}
	if ($posted['n95'] != null ) 	{$n95 	=  $_POST['n95'];}
//}

	if ($n95 != "StopSaw") {
		echo "Unable to process your registration<br>";
		die;
	}
	if ($terms != "agree") {
		echo "You did not check Terms<br>";
		die;
	}	
}
if ($simulator) {
	goto SIMULATOR;
}

?>
<head>
<link rel=”stylesheet” type=”text/css” href=”style.css”>
<h2>NEW MEMBER - A Tightly Managed Registration App For New Users</h2>
</head>
<!-- <table style="width:50%" id="customers"> -->
<table style="width:auto" id="customers">
<!-- <table id="customers"> -->

<form name="register" id="register" method="post" action="/index.php/join-2/"  >
<div class="row">
<tr>
    <th colspan="3" style="text-align: center;">
    	<h3>Continue With Joining</h3>
    </th>
</tr>
<!-- USERNAME -->
<tr>
    <td>Username (also shown on next page)</td>
	<div class="col-50">
    <td>
 			<input  maxlength="20" size="20" title="GVRNo" id="username" name="username" type="number" min="100000" max="400000" required /> 
   	</td>
	<div class="col-50">
		<td>Enter your desired username. It will be validate for uniqueness. 
		</td
	</div>			     
</tr>
<!-- EMAIL -->
<tr>
	<div class="col-25">
	    <td>Email address</td>
    </div>	
	<div class="col-25">
	    <td>
	    	<input type="text" size="30" name="email" id="email" required>  
	   </td>
    </div>	
	<div class="col-50">
		<td>A valid UNIQUE email address is required for membership. Email addresses 
		can not shared between members. it will be checked for uniqueness.
		</td>
	</div>			     
</tr>
<!-- PASSWORD -->
<tr>
	<div class="col-25">
    	<td>Password</td>
    </div>	
	<div class="col-25">
		<td class="pods-tr-alignrt">
			<input type="password" size="30" name="password" id="password" required>  
		</td>
    </div>	
	<div class="col-50">
		<td>
			Password must be 8 characters long. YOU WILL NOT receive an email with 
			the password, for security purposes.
		</td>
	</div>			     
</tr>
<!-- PASSWORD CONFIRM-->
<tr>
	<div class="col-50">
	    <td>Password Confirm</td>
    </div>	
	<div class="col-25">
		<td class="pods-tr-alignrt">
		  <input type="password" size="30" name="confirm_password" id="confirm_password" required>  
		</td>
    </div>	
	<div class="col-50">
		<td>
			Confirming password required.
		</td>
	</div>			     
</tr>
<!-- SUBMIT-->
<tr>
	<div class="col-50">
	    <td>Submit To Continue Registion</td>
    </div>	
	<div class="col-25">
    <td>
		<input type="hidden" name="terms" value="<?php echo $terms ?>">       
		<input type="submit" id="submit" name="action" value="submit" />  
     </td>
    </div>	
	<div class="col-50">
		<td>
			Submitting will confirm your GVR# and valid email address. 
			If invalid submittal errors will be reported on the next page. 
			A valid submittal will register you and Your Profile Page will 
			be displayed for you to provide a full profile.
		</td>
	</div>			     
</tr>
</div>
</form>
</table>			
<?php die; ?>
<?php SIMULATOR:
	// 04-20-22: Go away from the default password. May add complexity to it later, too.
	$password = "Aa" ."$simulator_userno" . "oP";	// not from a form on this version
	//echo "password = $password<br>";
	$confirm_password = $password;
?>
<!-- SIMULATOR - SIMULATOR - SIMULATOR - SIMULATOR -->
<head>                                
<link rel=”stylesheet” type=”text/css” href=”style.css”>
<h2>SIMULATOR - Demonstrationg A Tightly Managed Registration App For New Users</h2>
</head>
<!-- <table style="width:50%" id="customers"> -->
<div style="overflow-x:auto;">
<table id="customers">

<form name="register" id="register" method="post" action="/index.php/join-2/"  >
<div class="row">
<tr>
    <td colspan="2">
    	This is a simulated record intended for creating a new registration.
    	After submitting, a new page will provide login information.
    </td>
</tr>
<!-- USERNAME -->
<tr>
    <td>Username</td>
	<td><?php echo $simulator_userno;?></td>
</tr>
<!-- EMAIL -->
<tr>
	<td>Email address</td>
	<td><?php echo $simulator_email;?></td>
</tr>
<!-- PASSWORD -->
<tr>
	<td>Password</td>
	<td><?php echo $password;?></td>
</tr>
<!-- PASSWORD CONFIRM-->
<tr>
   	<td>Password Confirm</td>
	<td><?php echo $confirm_password;?></td>	
</tr>
<!-- SUBMIT-->
<tr>
	<td >
		<input type="hidden" name="username" value="<?php echo $simulator_userno ?>">       
		<input type="hidden" name="email" value="<?php echo $simulator_email ?>">       
		<input type="hidden" name="password" value="<?php echo $password ?>">       
		<input type="hidden" name="confirm_password" value="<?php echo $confirm_password ?>">       
		<input type="hidden" name="terms" value="<?php echo $terms ?>">       
		<input type="hidden" name="simulator" value="1">       
		<input type="submit" id="submit" name="action" value="submit" />  
     </td>
     <td> Submit to register</td>
</tr>
</div>
</form>
</table>			
 </div>
