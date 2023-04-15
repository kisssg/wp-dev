<?php
/*
Plugin Name: Gravity Forms Bulk Add Fields
Plugin URI: http://jetsloth.com/gravity-forms-bulk-add-fields/
Description: Easily add fields in bulk
Author: JetSloth
Version: 1.0.3
Requires at least: 4.0
Tested up to: 4.8
Author URI: http://jetsloth.com
License: GPL2
Text Domain: gf_bulk_add_fields
*/

/*
	Copyright 2017 JetSloth

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

define('GFBAF_VERSION', '1.0.3');
define('GFBAF_HOME', 'http://jetsloth.com');
define('GFBAF_NAME', 'Gravity Forms Bulk Add Fields');
define('GFBAF_SLUG', 'gf-bulk-add-fields');
define('GFBAF_TEXT_DOMAIN', 'gf_bulk_add_fields');
define('GFBAF_AUTHOR', 'JetSloth');
define('GFBAF_TIMEOUT', 20);
define('GFBAF_SSL_VERIFY', false);

add_action( 'gform_loaded', array( 'GF_Bulk_Add_Fields_Bootstrap', 'load' ), 5 );

class GF_Bulk_Add_Fields_Bootstrap {

	public static function load() {

		if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
			return;
		}

		require_once( 'class-gf-bulk-add-fields.php' );

		GFAddOn::register( 'GFBulkAddFields' );
	}
}

function gf_bulk_add_fields() {
	if ( ! class_exists( 'GFBulkAddFields' ) ) {
		return false;
	}

	return GFBulkAddFields::get_instance();
}


add_action('init', 'gf_bulk_add_fields_plugin_updater', 0);
function gf_bulk_add_fields_plugin_updater() {

	if (gf_bulk_add_fields() === false) {
		return;
	}

	if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
		// load our custom updater if it doesn't already exist
		include( dirname( __FILE__ ) . '/inc/EDD_SL_Plugin_Updater.php' );
	}

	// retrieve the license key
	$license_key = trim( gf_bulk_add_fields()->get_plugin_setting( 'gf_bulk_add_fields_license_key' ) );

	// setup the updater
	$edd_updater = new EDD_SL_Plugin_Updater( GFBAF_HOME, __FILE__, array(
			'version'   => GFBAF_VERSION,
			'license'   => $license_key,
			'item_name' => GFBAF_NAME,
			'author'    => 'JetSloth'
		)
	);

}
