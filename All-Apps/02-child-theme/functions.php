<?php
/*
Template Name: Functions
script_name: functions.php
parent_script_name: 
page_name: Not Required or Wanted
application_name: Functions
business_use: Functions for Child Theme
author: Dave Van Abel
dev_site: wpappsforthat.com
create_date: 2022-07-02
last_update_date: 2022-07-02
base_note: Functions for Child Theme, which is used for customized code on plugins and AppsForThat.
status: Complete 
license: GNU General Public License version 3 https://opensource.org/licenses/GPL-3.0
*/
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// **************** //
// ADD THE NEW WAY! //
// **************** //

// SOLUTION: Using RocketGeek's example (May 22, 2020):
//	https://rocketgeek.com/code-snippets/enqueue-a-custom-wp-members-stylesheet/

add_action( 'wp_enqueue_scripts', function() {
    global $wpappsmem;
    $style_url = trailingslashit( get_template_directory_uri() ) . 'wpapps-style.css';
	//echo "style_url=$style_url<br>";
    //wp_enqueue_style ( 'wpapps-style', $style_url, false, $wpappsmem->version );
    //08-01-22 went with simplified wp_enqueue_style
	wp_enqueue_style ('wpapps-style', $style_url );
//}, 20 );
} );

// *********** //
// ** DONE  ** //
// *********** //

// END ENQUEUE PARENT ACTION
