<?php
/**
 * Template Name: Member Update Profile
 * script_name: member-update-profile.php
 * parent_script_name: mgr-member-profile.php
 * page_name: Member Update Profile
 * application_name: Member Update Profile
 * business_use: Member Update Profile
 * author: Dave Van Abel
 * dev_site: gvaz.org
 * create_date: 2020-06-28
 * last_update_date: 2022-10-01
 * base_note: Member Update Profile
 * status: Complete 
 * license: GNU General Public License version 3 https://opensource.org/licenses/GPL-3.0
*/

/* 
 * General Notes
 * 10-01-22: Not too sure what is the real code. I think it got stripped down from WS version(s).
 * 04-21-22: update wp_usermeta, add "wp_usermeta.simulator" for managing member-update-profile.php
		     if membership added as SIMULATOR! This could get complex all through the website.
 * 08-27-20 App ready for release!
*/

get_header();
if ( ! defined( 'ABSPATH' ) ) {die( '-1' );}
global $ID;
$ID = get_current_user_id();	//use to get wp_users.ID of logged in user
/* Check if logged in user, if not throw error and die! */
access_denied($ID);
/* Get Waiver File Information  */
list($user_dirname, $file) = user_waiver_file($ID);	// Code in functions.php 08-25-20
        
global $wpdb; 
global $theuser;
global $edit_user_login;		//optional
global $post_type;
global $email         ;
global $displayname   ;    

global $active        ;
global $capabilities  ;

global $username; 			
global $first_name; 		
global $last_name; 			
global $phone1; 				
global $zip;
global $zip_d;
global $alt_zip;
global $alt_zip_d;
global $alt2_zip;
global $alt2_zip_d;
global $alt3_zip;
global $alt3_zip_d;

global $email;
global $orig_email;
global $user_registered;
global $paid_through_mdy;
global $covid_waiver;
global $user_dirname;
global $file;
global $simulator;

// Starting over 04-07-20 

$tbl_user 		= $wpdb->prefix.'users';
$tbl_usermeta	= $wpdb->prefix.'usermeta';
$errors 		= "";
$ID = get_current_user_id();	// Get's logged in wp_users.ID

	if ($ID) {
		//echo "Have selected User: $username<br>";
		$theuser = $wpdb->get_results( "
		SELECT 
			wp_users.ID
			,wp_users.user_login 		AS user_login
			,wp_users.user_email 		AS user_email
            ,wp_users.user_registered	AS user_registered   
			,metaln.meta_value			AS last_name
			,metafn.meta_value			AS first_name
			,metadis.meta_value		    AS display_name   
            ,metaac.meta_value			AS active
			,metaca.meta_value			AS wp_capabilities
			,metaph.meta_value			AS phone1
			,metaad1.meta_value			AS addr1
			,metaad2.meta_value			AS addr2
			,metacity.meta_value		AS city			
			,metast.meta_value			AS thestate
			,metazp.meta_value			AS zip
			,metapdth.meta_value		AS paid_through
			,metaexpire.meta_value		AS expires
			,metawpprof.meta_value		AS wp_myprofile
			,metawppass.meta_value		AS wp_associate
			,metawpful.meta_value		AS wp_fullmembership
			,metacovid.meta_value 		As covid_waiver
			,metasimm.meta_value 		As simulator

		FROM wp_users  
            LEFT JOIN wp_usermeta metafn 		ON (wp_users.ID = metafn.user_id 		AND metafn.meta_key 	= 'first_name')   
			LEFT JOIN wp_usermeta metaln 		ON (wp_users.ID = metaln.user_id 		AND metaln.meta_key 	= 'last_name')   
            LEFT JOIN wp_usermeta metadis 		ON (wp_users.ID = metadis.user_id 		AND metadis.meta_key 	= 'display_name')   
			LEFT JOIN wp_usermeta metaac 		ON (wp_users.ID = metaac.user_id 		AND metaac.meta_key 	= 'active')   
            LEFT JOIN wp_usermeta metaca 		ON (wp_users.ID = metaca.user_id 		AND metaca.meta_key 	= 'wp_capabilities')   
			LEFT JOIN wp_usermeta metaph 		ON (wp_users.ID = metaph.user_id 		AND metaph.meta_key 	= 'phone1')   
			LEFT JOIN wp_usermeta metaad1 		ON (wp_users.ID = metaad1.user_id 		AND metaad1.meta_key 	= 'addr1') 
			LEFT JOIN wp_usermeta metaad2 		ON (wp_users.ID = metaad2.user_id 		AND metaad2.meta_key 	= 'addr2') 				
			LEFT JOIN wp_usermeta metacity 		ON (wp_users.ID = metacity.user_id 		AND metacity.meta_key 	= 'city') 
			LEFT JOIN wp_usermeta metast 		ON (wp_users.ID = metast.user_id 		AND metast.meta_key 	= 'thestate') 
			LEFT JOIN wp_usermeta metazp 		ON (wp_users.ID = metazp.user_id 		AND metazp.meta_key 	= 'zip') 
			LEFT JOIN wp_usermeta metapdth 		ON (wp_users.ID = metapdth.user_id 		AND metapdth.meta_key 	= 'paid_through') 
			LEFT JOIN wp_usermeta metaexpire 	ON (wp_users.ID = metaexpire.user_id 	AND metaexpire.meta_key = 'expires') 
			LEFT JOIN wp_usermeta metawpprof 	ON (wp_users.ID = metawpprof.user_id 	AND metawpprof.meta_key	= '_wpmem_products_myprofile')
			LEFT JOIN wp_usermeta metawppass 	ON (wp_users.ID = metawppass.user_id 	AND metawppass.meta_key	= '_wpmem_products_associate') 
			LEFT JOIN wp_usermeta metawpful 	ON (wp_users.ID = metawpful.user_id 	AND metawpful.meta_key	= '_wpmem_products_full-membership') 
			LEFT JOIN wp_usermeta metacovid 	ON (wp_users.ID = metacovid.user_id 	AND metacovid.meta_key	= 'covid_waiver') 
			LEFT JOIN wp_usermeta metasimm 		ON (wp_users.ID = metasimm.user_id 		AND metasimm.meta_key	= 'simulator') 
		WHERE 
		wp_users.ID = $ID", OBJECT );			

	}	// end of if on SELECT 

		/* Conditionals */
		
	if (!$theuser) { 
		$wpdb->flush();
		$not_found = 1;
		echo "<h3>Your User Login not found. Re-enter .</h3>";
		die;
	}	
	
/* Only have one row & need to get variable */
	    
if(!empty($ID)) {
	// User data 
	foreach($theuser as $row) {
		$gvrno        		= $row->user_login; 
		$email        		= $row->user_email;
		$orig_email    		= $row->user_email;
		$displayname  		= $row->display_name;
		$last_name     		= $row->last_name;
		$first_name    		= $row->first_name;
		$addr1    			= $row->addr1;
		$addr2    			= $row->addr2;
		$city    			= $row->city;
		$thestate    		= $row->thestate;
		$zip    			= $row->zip;
		$active       		= $row->active;
		$capabilities 		= $row->wp_capabilities;
		$phone1        		= $row->phone1;
		$paid_through		= $row->paid_through;
		$wp_myprofile		= $row->wp_myprofile;
		$wp_associate		= $row->wp_associate;
		$wp_fullmembership	= $row->wp_fullmembership;
		$covid_waiver		= $row->covid_waiver;		
		$simulator			= $row->simulator;		
	}                         
	
	/* Initial Member Update Control Settings - want 0 so can test for all saves */
	
	if ($wp_myprofile) {
		$wp_associate = 0;
		$wp_fullmembership = 0;
	}
	
	//Remodel date format w/o doing manually
     $timestamp = strtotime($paid_through);
     $paid_through_mdy = date("m-d-Y", $timestamp);
    
	/* Zipcode */
	//if ($zip == "54130") {
	//	$zip_d = "54130";
	//	$alt_zip = "54220"; 
	//	$alt_zip_d = "54220";
	//	$alt2_zip = "notlisted";
	//	$alt2_zip_d = "Not Listed";
	//} 
	//elseif ($zip == "54130") {
	//	$zip_d = "54130";
	//	$alt_zip = "54220"; 
	//	$alt_zip_d = "54220";
	//	$alt2_zip = "notlisted";
	//	$alt2_zip_d = "Not Listed";
	//} 
	//elseif ($zip == "notlisted") {
	//	$zip_d = "Not Listed";
	//	$alt_zip = "54130"; 
	//	$alt_zip_d = "54130";
	//	$alt2_zip = "54220";
	//	$alt2_zip_d = "54220";
	//} 
	//else {
	//	//echo "In else of zip<br>";
	//	$zip = "";
	//	$zip_d = "-- Select --";
	//	$alt_zip = "54130"; 		
	//	$alt_zip_d = "54130";
	//	$alt2_zip = "54220";
	//	$alt2_zip_d = "54220";
	//	$alt3_zip = "notlisted";
	//	$alt3_zip_d = "Not Listed";
	//} 
	/* Membership Status */
	if ($active == "1") {
		$active_d = "Yes";
		$alt_active = "0"; 
		$alt_active_d = "No";
	} 
	if ($active == "0") {
		$active_d = "No"; 
		$alt_active = "1"; 
		$alt_active_d = "Yes";
	}		 
}

/* Get Waiver File Information - check to see how user_id variable above & pass correctly */

list($user_dirname, $file) = user_waiver_file($ID);	// Code in functions.php 08-25-20

/* 04-22-22 SIMULATOR MODIFY FROM HERE HOPEFULLY */

?>    
<head>
<link rel=”stylesheet” type=”text/css” href=”style.css”>
</head>
<h2>Update Member Profile
	<?php 
		if ($simulator) {
			echo " - SIMULATOR MEMBER";
		}
	?>
</h2>
<div style="overflow-x:auto;">
<table id="customers">
	<form name="MemberProfile" id="MemberProfile" method="post" action="/index.php/member-update-profile-1" >
		<div class="row">		
			<!-- TOP ROW - General Info -->
			<tr class="pods-th">  
				<div > 
					<td colspan="3">
					You can update your profile here. Complete required fields.
					</td>  
				</div>
			</tr>
			<!-- GVRNo (username - actually user_login) -->
			<tr > 
			     <div >
					<td class="trtd-bold">User Login</td>
			     </div>			     
			     <div >
					<td><?php echo $gvrno;?></td>
			     </div>			     
			     <div >
					<td>This is your User Login</td>
			     </div>			     
			</tr>
			<!-- Covid Waiver 04-22-22 try to remove it -->
			<?php
				if ($file) {
					echo "<tr>"; 
						echo "<td>";
						echo "<b>Covid Waiver</b>";
						echo "</td>";
						echo "<td colspan='2'>";
						echo "<a href=\"/wp-content/uploads/$ID/$file\">Your Covid-19 Wavier on file in the Wood Shop</a>";
						echo "</td>";
					echo "</tr>";
				}
			?>			
			<!-- First Name (first_name) -->	
			<tr> 
			     <div >
					<td class="trtd-bold">First Name</td>
			     </div>			     
			     <div >
					<td> <input  maxlength="20" value="<?php echo $first_name;?>" title="First Name" id="first_name" name="first_name" type="text" /></td>
			     </div>			     
			     <div >
					<td>Optional</td>
			     </div>			     
			</tr>
			<!-- Last Name (last_name) -->	
			<tr> 
			     <div >
					<td class="trtd-bold">Last Name</td>
			     </div>			     
			     <div >
					<td> <input maxlength="20" value="<?php echo $last_name;?>" title="Last Name" id="last_name" name="last_name" type="text" /></td>
			     </div>			     
			     <div >
					<td>Optional</td>
			     </div>			     
			</tr>
			<!-- Display Name (displayname) -->	
			<tr> 
			     <div >
					<td class="trtd-bold">Display Name</td>
			     </div>			     
			     <div >
					<td> <input maxlength="50" value="<?php echo $displayname;?>" title="Display Name" id="display_name" name="display_name" type="text" /></td>
			     </div>			     
			     <div >
					<td>
						Optional
					</td>
			     </div>			     
			</tr>
<?php 
	if ($wp_myprofile) {
		goto NOMORE;
	}
?>
			<!-- Shop Status (active) -->	
			<tr> 
			     <div >
					<td class="trtd-bold">Membership</td>
			     </div>			     
			     <div >
					<td>
						<?php 
							if ($active == 1) {
								echo 'Active';
							} else {
								echo 'In-Active';
							}
						?> 
					</td>
			     <div >
					<td>Current status</td>
			    </div>			     
			     </div>			     
			</tr>
			<!-- Paid Through -->	
			<tr> 
			     <div >
					<td class="trtd-bold">Membership Expires</td>
			     </div>			     
			     <div >
					<td> <?php echo $paid_through_mdy;?></td>
			     </div>			     
			     <div >
					<td>Expiration of membership</td>
			    </div>			     
			</tr>
<?php 
	NOMORE:
?>
			<!-- Submit (submit) -->	
			<!-- DISPLAY ONLY rows: Don't Need To Pass, as no changes -->
			<tr>  
				<div> 
					<td colspan="2" style="text-align: center;">
						<input type="hidden" name="action" value="submit" > 
						<input type="hidden" id="referer" name="username" value="<?php echo $gvrno;?>" > 
						<input type="hidden" id="referer" name="orig_email" value="<?php echo $orig_email;?>" > 
						<input type="hidden" id="referer" name="id" value="<?php echo $ID;?>" > 
						<!-- Only applicable for Initial Member updating full  -->
						<input type="hidden" id="referer" name="wp_myprofile" value="<?php echo $wp_myprofile;?>" > 
						<input type="hidden" id="referer" name="wp_associate" value="<?php echo $wp_associate;?>" > 
						<input type="hidden" id="referer" name="wp_fullmembership" value="<?php echo $wp_fullmembership;?>" > 
						<input value="Submit" name="button" type="submit" />
					</td>  
				</div>
			</tr>
		</div>
	</form>
</table>
</div>