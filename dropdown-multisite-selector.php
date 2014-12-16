<?php
/**
 *
 * Plugin Name:       Dropdown multisite selector
 * Plugin URI:        https://github.com/alordiel/dropdown-multisite
 * Description:       Allows you to configure a select option of redirecting to different webpages.
 * Version:           0.4.1
 * Author:            alordiel
 * Author URI:        http://profiles.wordpress.org/alordiel
 * Text Domain:       dropdown-multisite-selector
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages/
 * GitHub Plugin URI: https://github.com/alordiel/dropdown-multisite
 */

/*  Copyright 2014  Alexander Vasilev  (email : alexander.dorn@gmail.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'WPINC' ) ) {
		die;
}

/**
 * Register textdomain.
 */
add_action('plugins_loaded', 'dropdown_multisite_meta_init');
function dropdown_multisite_meta_init() {
	load_plugin_textdomain( 'dropdown-multisite-selector', false, dirname( plugin_basename( __FILE__ ) ) ); 
}


/**
 * Register style sheet and scripts for the admin area.
 */
add_action( 'admin_enqueue_scripts', 'admin_styles_script' );
function admin_styles_script() {
	wp_enqueue_script( 'dms-functions', plugins_url( '/js/dms-functions.js' , __FILE__) );
	wp_enqueue_style( 'dms-style', plugins_url( '/css/dms-style.css', __FILE__ ) );
	
	//Adding localization for script string
	$translation_array = array( 
		'tag_err' => __('Please enter a select tag name.'),
		'let_err' => __('This field can contain only letters.'),
		'emt_err' => __('All Names and URL fields should be filled.'),
		'dup_err' => __('One of your Option names is written twice or more.'),
		'suc_err' => __('Your settings were saved successfully.'),
		'err_err' => __('Something went wrong!Please check your data and try again.')
	);
	wp_localize_script( 'dms-functions', 'trans_str', $translation_array );

	wp_localize_script( 'dms-functions', 'dms_ajax_vars', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));
}

/**
 * Register style sheet and scripts for the front.
 */
add_action( 'wp_enqueue_scripts', 'front_styles_script' );
function front_styles_script(){
	wp_enqueue_script( 'dms-functions-front', plugins_url( '/js/dms-front.js' , __FILE__), array(), '1.0.0', true );
	wp_enqueue_style( 'dms-style-front', plugins_url( '/css/dms-front.css', __FILE__ ) );
}

/**
 * Register submenu in Settings.
 */
add_action('admin_menu','register_submenu');
function register_submenu(){
	
	add_submenu_page( 'options-general.php', 'Dropdown Multisite Selector', 'Dropdown Multisite', 'manage_options', 'dropdown-multisite-selector', 'dms_admin' );

}

/**
 * Building the admin part.
 */ 
function dms_admin(){

	$out = include('dms-admin.php');

	return $out;
}

/**
 * Building the admin part.
 */ 
add_action("wp_ajax_dms_add_fields", "dms_ajax_update_fields");
function dms_ajax_update_fields() {
	global $wpdb;
	$res = $_REQUEST;
	$name;
	$options;
	$multisite;
	$placeholder = '';
	$radiobuttons = array('none','all','usersonly');	

	if(array_key_exists('name', $res)){
		$name = $res['name'];
		if($name==""){
			$name = false;
		}
	}

	if(array_key_exists('options', $res)){
		$options = $res['options'];
	}

	if(array_key_exists('multisite', $res)){
		$multisite = $res['multisite'];
	}

	if(array_key_exists('placeholder', $res)){
		$placeholder = $res['placeholder'];
	}

	//validate
	if($name){
		if (preg_match('/[\^<,\"@\/\{\}\(\)\*\$%\?=>:\|;#]+/i', $name)) {
			_e("This field can contain only letters.",'dropdown-multisite-selector');
			die();
		}
	}


	if($multisite){
		if (preg_match('/[\^<,\"@\/\{\}\(\)\*\$%\?=>:\|;#]+/i', $multisite) || !in_array($multisite, $radiobuttons)) {
			_e("Don't bug with the code, please!",'dropdown-multisite-selector');
			die();
		}
	}
	else{
		_e("Don't bug with the code, please!",'dropdown-multisite-selector');
		die();
	}

	if ( $options ) {
		if ( !is_array($options) ) {
			_e("Please make sure that you have entered all fields correctly.",'dropdown-multisite-selector');
			die();
		}
	}
	else{
		_e("Please enter a place tag name.",'dropdown-multisite-selector');
		die();
	}

	//sanitaze
	if($name !== false) {$name = cleanInput(sanitize($name));}
	$options = cleanInput(sanitize($options));
	$multisite = cleanInput(sanitize($multisite));
	$placeholder = cleanInput(sanitize($placeholder));

	//uploade in db
	$options_db_name = 'dms_select_name';
	$options_db_select = 'dms_options';
	$options_db_multisite = 'dms_multisite';
	$options_db_placeholder = 'dms_placeholder';

  
	if ( get_option( $options_db_name ) !=  $name ) {
		if(!update_option($options_db_name , $name )) {
			_e("Something went worng with updating your settigns.",'dropdown-multisite-selector');
			die();
		}
	}

	if ( get_option( $options_db_select ) != $options ) {
		if(!update_option($options_db_select , $options )) {
		   _e("Something went worng with updating your settigns.",'dropdown-multisite-selector');
			die();
		}
	}

	if ( get_option( $options_db_multisite ) != $multisite ) {
		if(!update_option($options_db_multisite , $multisite )) {
		   _e("Something went worng with updating your settigns.",'dropdown-multisite-selector');
			die();
		}
	}

	if ( get_option( $options_db_placeholder ) != $placeholder ) {
		if(!update_option($options_db_placeholder , $placeholder )) {
		   _e("Something went worng with updating your settigns.",'dropdown-multisite-selector');
			die();
		}
	}


	echo true;
	die();
}

/**
 * Register the shortcode
 */
function build_select(){
   
	$sites_per_user;
	$current_site_id;

	$name = false; // the name of the select's label
	$out=""; // the output
	$multisite; // the multisite option
	$placeholder;
	
	$options_db_name = 'dms_select_name';
	$options_db_multisite = 'dms_multisite';
	$options_db_placeholder = 'dms_placeholder';
	
	if ( get_option( $options_db_name ) ) {
		$name = get_option( $options_db_name );
	}

	if ( get_option( $options_db_multisite ) ) {
		$multisite = get_option( $options_db_multisite );
	}

	if ( get_option( $options_db_placeholder ) ) {
		$placeholder = get_option( $options_db_placeholder );
	}
	else{
		$placeholder = __('Select Option','dropdown-multisite-selector');
	}

	$out .="<div class='dms-container'>";
	if($name !== false){$out .= "<label for='dms-select'>" . $name . "</label>";}
	$out .= "<select class='dms-select'>"; 
	$out .= "<option value=''>".$placeholder."</option>";

	if ($multisite == 'none') {
		$out .= noneOptions();
	}
	elseif ($multisite == 'all') {
		$out .= showAll();
	}
	elseif ($multisite =="usersonly"){
		if (is_user_logged_in()) {
			$out .= usersOnly();
		}
		else{
			return false;
		}
	}


	$out .= "</select>";
	$out .= "</div>";

	return $out;  

}
add_shortcode('dms','build_select');


/**
* Functions for displaying the select options on the front
*/
	
function noneOptions(){
	$out = "";
	$options_db_select = 'dms_options';

	if ( get_option( $options_db_select ) ) {
		$options = get_option( $options_db_select );
	}

	if ( $options ) {
		
		foreach ( $options as $key => $value ) {
			$out .= "<option value='" . $value . "'>" . $key . "</option>";
		}
	}
	else{
		$err = "<p class='error-front'>" . __("Missing data. Check if all the settigns are correctly set in your admin area regarding 'Dropdown Multisite selector'",'dropdown-multisite-selector') . "</p>";
		return $err;
		die();
	}
	
	return $out;
}

//show all sites from the WMN
function showAll(){

	$out;
	$all_wmn_sites = wp_get_sites();
	$current_site_id = get_current_blog_id();
	
	foreach ($all_wmn_sites as $site) {
		
		if ($current_site_id != $site["blog_id"]) {
			$the_site = get_blog_details($site["blog_id"]);
			$out .= "<option value='" . $the_site->siteurl . "'>" . $the_site->blogname . "</option>";
		}
	}

	return $out;
}

//show only the sites from the WMN which the user is regged in
function usersOnly(){

	$out;
	$users_sites = get_blogs_of_user( get_current_user_ID() );
	$current_site_id = get_current_blog_id();

	foreach ($users_sites as $site) {
		if ($current_site_id != $site->userblog_id) {
			$out .= "<option value='" . $site->siteurl . "'>" . $site->blogname . "</option>";
		}		
	}

	return $out;
}

/**
 * Function for sanitize
 * @param  string/array $input
 * @return string/array
 */
function cleanInput($input) {
	
	$search = array(
		'@<script[^>]*?>.*?</script>@si',
		'@<[\/\!]*?[^<>]*?>@si',
		'@<style[^>]*?>.*?</style>@siU',
		'@<![\s\S]*?--[ \t\n\r]*>@'
	);
	
	$output = preg_replace($search, '', $input);
	return $output;
}

function sanitize($input) {

	if (is_array($input)) {
		foreach($input as $var=>$val) {
			$output[$var] = sanitize($val);
		}
	}
	else {
		if (get_magic_quotes_gpc()) {
			$input = stripslashes($input);
		}
		$input  = cleanInput($input);
		//$output = mysqli_real_escape_string($input); //For some reasons is deleting the urls
	}
	return $input;
}


// Creating the widget 
class dms_widget extends WP_Widget {

	function __construct() {
		parent::__construct(
		// Base ID
		'dms_widget', 

		// Widget name
		__('Dropdown Multisite Selector', 'dropdown-multisite-selector'), 

		// Widget description
		array( 'description' => __( 'Shows a select options with site/multisites.', 'dropdown-multisite-selector' ), ) 
		);
	}

	// This is where the action happens
	public function widget( $args, $instance ) {
		echo do_shortcode('[dms]');
	}

} 

// Register and load the widget
function dms_load_widget() {
	register_widget( 'dms_widget' );
}
add_action( 'widgets_init', 'dms_load_widget' );



// On install check if dms_mutisite option exists, if not - this is updating from 0.1 so create it with option 'none'
// On install check if dms_placeholder option exists, if not - this is updating from 0.33 so create it with option 'none'
function myplugin_activate() {
	if(!get_option('dms_multisite')){
    	update_option('dms_multisite', 'none');
	}
	if(!get_option('dms_placeholder')){
    	update_option('dms_placeholder', 'Select Option');
	}
}
register_activation_hook( __FILE__, 'myplugin_activate' );

function myplugin_update_db_check() {
	if(!get_option('dms_multisite')){
    	update_option('dms_multisite', 'none');
	}
	if(!get_option('dms_placeholder')){
	   	update_option('dms_placeholder', 'Select Option');
	}
}
add_action( 'plugins_loaded', 'myplugin_update_db_check' );
