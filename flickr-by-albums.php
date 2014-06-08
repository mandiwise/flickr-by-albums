<?php
/**
 * @package   Flickr_by_Albums
 * @author    Mandi Wise <hello@mandiwise.com>
 * @license   GPL-2.0+
 * @link      http://mandiwise.com/
 * @copyright 2014 Mandi Wise
 *
 * @wordpress-plugin
 * Plugin Name:       Flickr by Albums
 * Plugin URI:        http://mandiwise.com/project/flickr-by-albums/
 * Description:       Display thumbnail-style galleries of complete Flickr albums using a simple shortcode in a WordPress post or page.
 * Version:           1.0.0
 * Author:            Mandi Wise
 * Author URI:        http://mandiwise.com/
 * Text Domain:       flickr-by-albums
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/mandiwise/flickr-by-albums/
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Define the plugin directory
define( 'FBA_DIR', dirname( __FILE__ ) );

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( FBA_DIR . '/public/class-flickr-by-albums.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'Flickr_by_Albums', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Flickr_by_Albums', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Flickr_by_Albums', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( FBA_DIR . '/admin/class-flickr-by-albums-admin.php' );
	add_action( 'plugins_loaded', array( 'Flickr_by_Albums_Admin', 'get_instance' ) );

}
