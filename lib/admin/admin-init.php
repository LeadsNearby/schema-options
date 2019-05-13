<?php

class schema_admin_page {

    public function __construct() {
        add_action('admin_menu', array($this, 'admin_menu'));
    }

    public function admin_menu() {
        if (is_plugin_active('lnb-core/lnb-core.php')) {
            add_submenu_page(
                'lnb-settings.php',
                'Schema Options',
                'Schema Options',
                'manage_options',
                'schema-options',
                array(
                    $this,
                    'settings_page',
                )
            );
        } else {
            add_options_page(
                'Schema Options',
                'Schema Options',
                'manage_options',
                'schema-options',
                array(
                    $this,
                    'settings_page',
                )
            );
        }
    }

    public function settings_page() {

        if (count($_POST) > 0) {
            $options = array(
                'schema_itemtype',
                'schema_tel',
                'schema_email',
                'schema_url',
                'schema_address_street',
                'schema_pricerange',
                'schema_logo',
            );

            foreach ($options as $opt) {
                $old_value = get_option('lnb_' . $opt);
                $new_value = $_POST[$opt];
                if ($old_value != $new_value) {
                    update_option('lnb_' . $opt, $new_value);
                }
            }
        }

        ob_start();?>

			<div id="lnb-schema-wrapper">
			<form method="post" action="">
			<h1>Schema for <?php echo get_bloginfo('name'); ?></h1>
			<fieldset>
				<table class="form-table" style="max-width: 750px">
					<tr>
						<td class="description">
							<label for="schema_itemtype">Select Type of Business</label>
						</td>
						<td class="form-field">
							<select class="" name="schema_itemtype" id="schema_itemtype">
								<?php $schema_itemtype_array = $this->get_schema_itemtypes();
        foreach ($schema_itemtype_array as $option) {?>
									<option value="<?php echo $option['value']; ?>"<?php if (get_option('lnb_schema_itemtype') == $option['value']) {echo 'selected="selected"';}?>><?php echo $option['title'] ?></option>
								<?php }?>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<td class="description" width="">
							<label for="schema_priceRange">Price Range</label>
						</td>
						<td class="form-field" width="">
							<select class="" name="schema_pricerange" id="schema_pricerange">
								<?php $pricerange_array = array('$', '$$', '$$$', '$$$$');
        foreach ($pricerange_array as $price) {?>
									<option
										value="<?php echo $price; ?>"
										<?php if (get_option('lnb_schema_pricerange', '$$') == $price) {echo 'selected="selected"';}?>
									><?php echo $price; ?></option>
								<?php }?>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<label for="schema_address_street">Address</label>
						</td>
						<td>
							<table class="form-table">
								<tr>
									<td class="form-field">
										<input name="schema_address_street" type="text" id="schema_address_street" value="<?php echo get_option('lnb_schema_address_street'); ?>"/>
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
							<input name="schema_tel" type="tel" id="schema_tel" class="large" value="<?php echo get_option('lnb_schema_tel'); ?>" class="regular-text" />
						</td>
					</tr>
					<tr>
						<td class="description">
							<label for="schema_email">Email Address</label>
						</td>
						<td class="form-field">
							<input name="schema_email" type="email" id="schema_email" class="large" value="<?php echo get_option('lnb_schema_email'); ?>" class="regular-text" />
						</td>
					</tr>
					<tr>
						<td class="description">
							<label for="schema_url">Web Address (URL)</label>
						</td>
						<td class="form-field">
							<input name="schema_url" type="url" id="schema_url" pattern="https?://.+" class="large" value="<?php echo get_option('lnb_schema_url'); ?>" class="regular-text" />
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
			</form>
			</div>
		<?php echo ob_get_clean();
    }

    public function get_schema_itemtypes() {

        // Store Schema Itemtypes in Array
        $lnb_schema_itemtype_array = array(
            'Electrician' => array(
                'title' => 'Electrician',
                'value' => 'Electrician',
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

        return $lnb_schema_itemtype_array;
    }
}

?>