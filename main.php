<?php
/* Plugin Name: LeadsNearby Schema Options
Plugin URI: http://leadsnearby.com/
Description: Creates admin page to enter global schema and adds a meta box to each page add schema page description and select custom schema itemtype for clients with more than one business veritical.

Version: 1.0
Author: Leads Nearby
Author URI: http://leadsnearby.com/
License: GPLv2 or later
*/

// Enqueue Styles
function lnb_schema_styles()  
{ 
	if (!is_admin()) {
		wp_register_style('widget-styles', plugins_url('/schema-options/css/widget-styles.css'));
		wp_enqueue_style('widget-styles');
	}

	if (is_admin()) {
		wp_register_style('schema-admin-styles', plugins_url('/schema-options/css/admin-styles.css'));
		wp_enqueue_style('schema-admin-styles');
	}		
}
add_action('wp_enqueue_scripts', 'lnb_schema_styles');
add_action('admin_enqueue_scripts', 'lnb_schema_styles');

//Creates Admin Page	
add_action('admin_menu', 'lnb_schema_admin_options');

function lnb_schema_admin_options () {
	if ( count($_POST) > 0 && isset($_POST['lnb_schema_admin_html']) ) {
		$options = array (
			'schema_itemtype',
			'schema_itemname',
			'schema_tel',
			'schema_email',
			'schema_url',
			'schema_address_street',
		);

		foreach ( $options as $opt ) {
			$old_value = get_option('lnb_'.$opt);
			$new_value = $_POST[$opt];
			if ($old_value != $new_value) {
				update_option('lnb_'.$opt, $new_value);
			}
		}			
	}
	
	add_options_page(__('Schema Options'), __('Schema Options'), 'edit_themes', basename(__FILE__), 'lnb_schema_admin_html');
}

// Store Schema Itemtypes in Array
global $lnb_schema_itemtype_array;
$lnb_schema_itemtype_array = array(
	'Electrician' => array(
		'title' => 'Electrican',
		'value' => 'Electrican',
		),
	'General Contractor' => array(
		'title' => 'General Contractor',
		'value' => 'GeneralContractor',
		),
	'Home & Construction Business' => array(
		'title' => 'Home & Construction Business',
		'value' => 'HomeAndConstructionBusiness',
		),
	'House Painter' => array(
		'title' => 'House Painter',
		'value' => 'HousePainter',
		),
	'HVAC' => array(
		'title' => 'HVAC',
		'value' => 'HVACBusiness',
		),
	'Local Business' => array(
		'title' => 'Local Business',
		'value' => 'LocalBusiness',
		),
	'Locksmith' => array(
		'title' => 'Locksmith',
		'value' => 'Locksmith',
		),
	'Moving Company' => array(
		'title' => 'Moving Company',
		'value' => 'MovingCompany',
		),
	'Plumber' => array(
		'title' => 'Plumber',
		'value' => 'Plumber',
		),
	'Roofing Contractor' => array(
		'title' => 'Roofing Contractor',
		'value' => 'RoofingContractor',
		),
	);

function lnb_schema_admin_html() {?>

<script>
jQuery(document).ready(function($) {
	$(init);
	function init(){
		$("#tabs").tabs();
	}
});
</script>

<div id="lnb-schema-wrapper">
<form method="post" action="">
<h1>Schema for <?php echo get_bloginfo( 'name' ); ?></h1>
<div id ="tabs">
	<ul>
		<li><a href="#gen-settings">General</a></li>
		<li><a href="#contact-page-settings">Contact Page</a></li>
	</ul>
	<div id="gen-settings">
		<fieldset>
		<legend><h2>General Settings</h2></legend>
			<table class="form-table">
				<tr valign="top">
					<td class="description">
						<label for="schema_itemname">Company Name</label>
					</td>
					<td class="form-field">
						<input name="schema_itemname" type="text" id="schema_itemname" class="medium" value="<?php echo get_option('lnb_schema_itemname'); ?>" class="regular-text" />
					</td>
				</tr>			
				<tr>
					<td class="description">
						<label for="schema_itemtype">Select Type of Business</label>
					</td>
					<td class="form-field">
						<select class="" name="schema_itemtype" id="schema_itemtype"> 
							<?php
							global $lnb_schema_itemtype_array;
							$schema_itemtype_array = $lnb_schema_itemtype_array;
							foreach ($schema_itemtype_array as $option) { ?>
								<option value="<?php echo $option['value']; ?>"<?php if(get_option('lnb_schema_itemtype') == $option['value'] ){ echo 'selected="selected"';}?>><?php echo $option['title']?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<p class="submit">
							<input type="submit" name="Submit" class="button-primary" value="Save Changes" />
							<input type="hidden" name="lnb_schema_admin_html" value="save" style="display:none;" />
						</p>
					</td>
				</tr>												
			</table>			
		</fieldset>
	</div>
	<div id="contact-page-settings">
		<fieldset>
		<legend><h2>Contact Page Settings</h2></legend>
			<table class="form-table">
				<tr>
					<td>
						<label for="schema_address_street">Address</label>
					</td>
					<td>
						<table class="form-table">
							<tr>
								<td colspan="3" class="form-field">
									<input name="schema_address_street" type="text" id="schema_address_street" placeholder="Street Address" value="<?php echo get_option('lnb_schema_address_street'); ?>"/>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="description">
						<label for="schema_tel">Telephone Number</label>
					</td>
					<td class="form-field">
						<input name="schema_tel" type="tel" id="schema_tel" class="medium" value="<?php echo get_option('lnb_schema_tel'); ?>" class="regular-text" />
					</td>
				</tr>
				<tr>
					<td class="description">
						<label for="schema_email">Email Address</label>
					</td>
					<td class="form-field">
						<input name="schema_email" type="email" id="schema_email" class="medium" value="<?php echo get_option('lnb_schema_email'); ?>" class="regular-text" />
					</td>
				</tr>
				<tr>
					<td class="description">
						<label for="schema_url">Web Address (URL)</label>
					</td>
					<td class="form-field">
						<input name="schema_url" type="url" id="schema_url" pattern="https?://.+" class="medium" value="<?php echo get_option('lnb_schema_url'); ?>" class="regular-text" />
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<p class="submit">
							<input type="submit" name="Submit" class="button-primary" value="Save Changes" />
							<input type="hidden" name="lnb_schema_admin_html" value="save" style="display:none;" />
						</p>
					</td>
				</tr>												
			</table>
		</fieldset>
	</div>
</div>
</form>


<?php }

// Load Additional Files
define('SchemaOptions_MAIN', plugin_dir_path( __FILE__ ));
require_once(SchemaOptions_MAIN . '/shortcodes.php');
require_once(SchemaOptions_MAIN . '/meta-boxes.php');

require_once( SchemaOptions_MAIN . '/lib/updater/github-updater.php' );

if ( is_admin() ) {
    new GitHubPluginUpdater( __FILE__, 'LeadsNearby', 'schema-options' );
}

?>