<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Flickr_by_Albums
 * @author    Mandi Wise <hello@mandiwise.com>
 * @license   GPL-2.0+
 * @link      http://mandiwise.com/
 * @copyright 2014 Mandi Wise
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'fba_options' );

// @TODO: Define uninstall functionality here
