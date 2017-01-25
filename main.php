<?php
/* Plugin Name: LeadsNearby Schema Options
Plugin URI: http://leadsnearby.com/
Description: Creates admin page to enter global schema and adds a meta box to each page add schema page description and select custom schema itemtype for clients with more than one business veritical.

Version: 1.1.1
Author: Leads Nearby
Author URI: http://leadsnearby.com/
License: GPLv2 or later
*/

// Enqueue Styles
function lnb_schema_styles() { 
	wp_register_style('schema-styles', plugins_url('/schema-options/css/schema-styles.css'));
	wp_enqueue_style('schema-styles');	
}
add_action('wp_enqueue_scripts', 'lnb_schema_styles');
add_action('admin_head', 'lnb_schema_styles');

// Load Additional Files
define('SchemaOptions_MAIN', plugin_dir_path( __FILE__ ));
require_once(SchemaOptions_MAIN . '/shortcodes.php');
require_once(SchemaOptions_MAIN . '/meta-boxes.php');

if ( is_admin() ) {
	require_once( SchemaOptions_MAIN . '/lib/updater/github-updater.php' );
    new GitHubPluginUpdater( __FILE__, 'LeadsNearby', 'schema-options' );

    require_once( SchemaOptions_MAIN . '/lib/admin/admin-init.php' );
    new schema_admin_page;
}

?>