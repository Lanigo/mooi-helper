<?php
/*
Plugin Name: Mooi Helper Plugin
Plugin URI: http://lanigouws.co.za/mooi
Description: Custom functionality plugin which extends the Mooi theme
Author: Lani Gouws
Version: 1.0
Author URI: http://lanigouws.co.za
*/

// activation/deactivation
function mooihelp_activation() {
}
register_activation_hook(__FILE__, 'mooihelp_activation');

function mooihelp_deactivation() {
}
register_deactivation_hook(__FILE__, 'mooihelp_deactivation');


/*
* Enqueue the slider scripts
*/
function mooihelp_scripts() {

	wp_enqueue_style('mooihelp_slidesjs_style', plugins_url( 'inc/assets/css/slides.css', __FILE__), '' );

	wp_enqueue_style('mooihelp_font_awesome', plugins_url( 'inc/assets/css/font-awesome.min.css', __FILE__), '' );
	
	wp_enqueue_script( 'mooihelp_slidesjs_core', plugins_url( 'inc/assets/js/jquery.slides.min.js', __FILE__ ), array( "jquery" ), '', true );
	 	 
	wp_enqueue_script( 'mooihelp_slidesjs_init', plugins_url( 'inc/assets/js/slides.init.js', __FILE__ ), array( "jquery" ), '', true );
	 
}
add_action('wp_enqueue_scripts', 'mooihelp_scripts');

/*
* Enqueue the admin styles
*/
function mooihelp_admin_styles() {
    
    wp_enqueue_style( 'mooihelp_admin_css', plugins_url( 'inc/assets/css/admin.css', __FILE__), '' );

}
add_action( 'admin_enqueue_scripts', 'mooihelp_admin_styles' );

/**
 * Include the file with the slider code.
 */
require_once plugin_dir_path(__FILE__) . 'inc/slider.php';
?>
