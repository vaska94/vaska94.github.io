<?php 
/*
	Plugin Name: WP Harden
	Plugin URI: https://sysadmin.lol
	Description: Combines Several Plugins and disables unnecessary functions.
	Tags: xml-rpc,rest, rest-api, api, json, disable, head, header, link, http
	Author: Various Authors
	Author URI: https://sysadmin.lol
	Requires at least: 4.8
	Tested up to: 5.5
	Stable tag: 1.0
	Version: 1.0
	Requires PHP: 5.6.20
	License: GPL v2 or later
	License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) die();

/*
	Disable REST API link in HTTP headers
	Link: <https://example.com/wp-json/>; rel="https://api.w.org/"
*/
remove_action('template_redirect', 'rest_output_link_header', 11);

/*
	Disable REST API links in HTML <head>
	<link rel='https://api.w.org/' href='https://example.com/wp-json/' />
*/
remove_action('wp_head', 'rest_output_link_wp_head', 10);
remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');

/*
	Disable REST API
*/	
add_filter('rest_authentication_errors', 'disable_wp_rest_api');
function disable_wp_rest_api($access) {
	
	if (!is_user_logged_in()) {
		
		$message = apply_filters('disable_wp_rest_api_error', __('REST API restricted to authenticated users.', 'disable-wp-rest-api'));
		
		return new WP_Error('rest_login_required', $message, array('status' => rest_authorization_required_code()));
		
	}
	
	return $access;
	
}
/*
	Disable XML-RPC
*/
add_filter( 'xmlrpc_enabled', '__return_false' );

/*
	Remove WordPress Version From Head
*/
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/*
	Remove X-Pingback Header
*/
header_remove( 'X-Pingback' );
