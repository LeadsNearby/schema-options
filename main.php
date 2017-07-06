<?php
/* Plugin Name: LeadsNearby Schema Options
Plugin URI: http://leadsnearby.com/
Description: Creates admin page to enter global schema and adds a meta box to each page add schema page description and select custom schema itemtype for clients with more than one business veritical.

Version: 1.2.3
Author: LeadsNearby
Author URI: http://leadsnearby.com/
License: GPLv2 or later
*/

// Definitions
define('SchemaOptions_MAIN', plugin_dir_path( __FILE__ ));

// Enqueue Styles
function lnb_schema_styles() { 
	wp_register_style('schema-styles', plugins_url( 'css/schema-styles.css', __FILE__ ) );
	wp_enqueue_style('schema-styles');	
}
add_action('wp_enqueue_scripts', 'lnb_schema_styles');
add_action('admin_head', 'lnb_schema_styles');

// Set Defaults on Activation
function schema_options_activate() {
	$options = array(
		'lnb_schema_itemname' => get_bloginfo( 'name' ),
		'lnb_schema_itemtype' => 'LocalBusiness',
		'lnb_schema_url' => get_bloginfo( 'url' ),
		);

	foreach( $options as $option => $value ) {
		$schema_field = get_option ( $option );
		if ( empty( $schema_field ) ) :
			update_option( $option, $value );
		endif;
	}
}
register_activation_hook(__FILE__, 'schema_options_activate');

if ( is_admin() ) {

	add_filter('plugin_action_links_'. plugin_basename(__FILE__), 'schema_action_links' );
    function schema_action_links( $links ) {
		$links[] = '<a href="'. admin_url( 'options-general.php?page=schema-options' ) . '">Settings</a>';
		return $links;
	}

	require_once( SchemaOptions_MAIN . '/lib/updater/github-updater.php' );
    new GitHubPluginUpdater( __FILE__, 'LeadsNearby', 'schema-options' );

	require_once( SchemaOptions_MAIN . '/lib/admin/admin-init.php' );
    $schema_admin_page = new schema_admin_page;

}

// Load Additional Files
require_once(SchemaOptions_MAIN . '/shortcodes.php');
require_once(SchemaOptions_MAIN . '/meta-boxes.php');

?>