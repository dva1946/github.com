<?php
/**
 * Template Name: Basic 101         
 * script_name: basic-101.php          
 * parent_script_name: 
 * page_name: Basic 101
 * application_name: Basic 101
 * business_use: Your 1st App
 * author: Dave Van Abel
 * dev_site: wpappsforthat.com
 * create_date: 2020-08-16
 * last_update_date: 2022-10-22
 * base_note: A Basic App
 * status: Complete
 * license: GNU General Public License version 3 https://opensource.org/licenses/GPL-3.0
*/
/**
 * 10-22-22: Cleanup look of output.
 * 10-01-22: Uses page name = Basic 101, template name = Basic 101.
 * 10-01-22: DIY Overview -> https://wpappsforthat.com/index.php/diy-overview/
*/

if ( ! defined( 'ABSPATH' ) ) {die( '-1' );}    // 1st line of php ensures script called from the website

get_header();   //Load the theme’s header

/* Generate a random number */

global $app;
global $pht;
global $today;
global $random; //define a global variable (advisable)

$random = (rand(100000,999999));	// Generate a random number between 100,000 and 999,999
//echo "random=$random<br>";          //Can print to screen from w/in the script
$pht 	= new DateTime('now', new DateTimeZone('America/Chicago')); // create an object
$today 	= $pht->format('m-d-Y');	// format the date
$app 	= "Basic 101";
?>
<head>
<link rel=”stylesheet” type=”text/css” href=”style.css”>
<link rel=”stylesheet” type=”text/css” href=”wpapps-style.css”></head>
<h2><?php echo "$app - $today";?></h2>
<div style="overflow-x:auto;">
<tbody>
<table id="customers">
<thead>
<!-- <tr class="pods-th"> -->
<tr class="pods-th">
	<!-- column 1 -->
	<td colspan="2" class="pods-tr-alignctr">The Basic 101 Script</td>
</tr>
</thead>
<tr>
    <td>
    	A Table
    </td>
    <td >
		A nicely dressed up table using simple css.
    </td>
</tr>
<tr>
    <td>
    	Status?
    </td>
    <td>
    	The App has run and output basic values. 
    </td>
</tr>   
<tr>
    <td>
    	Header
    </td>
    <td>
	    Header displays script name and today's date, which
	    demonstrates dynamic data. This will be covered in 
	    this documentation. Dynamic data enhances your website.
    </td>
</tr>   
<tr>
</table>
</tbody>
</div>
<!—- End of Script and HTML -—>