<?php
/*
Plugin Name: LeadsNearby Schema Options
Plugin URI: http://leadsnearby.com/
Description: Creates admin page to enter global schema and adds a meta box to each page add schema page description and select custom schema itemtype for clients with more than one business veritical.
Version: 1.2.5
Author: LeadsNearby
Author URI: http://leadsnearby.com/
License: GPLv2 or later
 */

// Definitions
define('SchemaOptions_MAIN', plugin_dir_path(__FILE__));

// Enqueue Styles
function lnb_schema_styles() {
    wp_register_style('schema-styles', plugins_url('css/schema-styles.css', __FILE__));
    wp_enqueue_style('schema-styles');
}
add_action('wp_enqueue_scripts', 'lnb_schema_styles');
add_action('admin_head', 'lnb_schema_styles');

// Set Defaults on Activation
function schema_options_activate() {
    $options = array(
        'lnb_schema_itemname' => get_bloginfo('name'),
        'lnb_schema_itemtype' => 'LocalBusiness',
        'lnb_schema_url' => get_bloginfo('url'),
    );

    foreach ($options as $option => $value) {
        $schema_field = get_option($option);
        if (empty($schema_field)):
            update_option($option, $value);
        endif;
    }
}
register_activation_hook(__FILE__, 'schema_options_activate');

if (is_admin()) {

    add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'schema_action_links');
    function schema_action_links($links) {
        $links[] = '<a href="' . admin_url('options-general.php?page=schema-options') . '">Settings</a>';
        return $links;
    }

    require_once SchemaOptions_MAIN . '/lib/admin/admin-init.php';
    $schema_admin_page = new schema_admin_page;

}

add_action('admin_init', function () {
    if (class_exists('\lnb\core\GitHubPluginUpdater')) {
        new \lnb\core\GitHubPluginUpdater(__FILE__, 'schema-options');
    }
}, 99);

// Load Additional Files
require_once SchemaOptions_MAIN . '/shortcodes.php';

add_filter('wpseo_schema_organization', function ($graph_piece) {
    // Switch type from Organization
    $type = get_option('lnb_schema_itemtype', 'LocalBusiness');
    $graph_piece['@type'] = $type;
    if (class_exists('Avada')) {
        $theme_social_links = Avada()->settings->get('social_media_icons', 'url');
        $theme_logo = Avada()->settings->get('logo');
    }
    $graph_piece['sameAs'] = array_values(array_unique(array_filter(array_merge($graph_piece['sameAs'], $theme_social_links))));
    if ($theme_logo) {
        $graph_piece['logo'] = array(
            '@type' => 'ImageObject',
            '@id' => trailingslashit(site_url()) . '#logo',
            'url' => $theme_logo['url'],
        );
        $graph_piece['image'] = array(
            '@id' => trailingslashit(site_url()) . '#logo',
        );
    }
    $lnb_schema_props = array(
        'priceRange' => get_option('lnb_schema_pricerange'),
        'address' => get_option('lnb_schema_address_street'),
        'telephone' => get_option('lnb_schema_tel'),
        'email' => get_option('lnb_schema_email'),
    );
    foreach ($lnb_schema_props as $lnb_schema_prop => $lnb_schema_prop_value) {
        if (!empty($lnb_schema_prop_value)) {
            $graph_piece[$lnb_schema_prop] = $lnb_schema_prop_value;
        }
    }
    return $graph_piece;
}, 10);
