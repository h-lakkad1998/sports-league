<?php
/**
 * Plugin Name:       Sports league
 * Description:       Handle the basics with this plugin.
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Hardik Lakkad
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       nsl-sports-league-ml
 *
 * @package WordPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if( ! function_exists( 'nls_sports_register_list_widget' ) ){
	// a separate file for admin hooks
	function nls_sports_register_list_widget( $widgets_manager ) {
		require_once( __DIR__ . '/widgets/class-elementor-nsl-league-widget.php' );
		$widgets_manager->register( new \Elementor_NSL_League_Widget() );
	}
	add_action( 'elementor/widgets/register', 'nls_sports_register_list_widget' );  
	// admin-backend hooks  
	require_once( __DIR__ . '/admin/admin-hooks.php' );
	// front-end hooks 
	require_once( __DIR__ . '/front/front-hooks.php' );
}
