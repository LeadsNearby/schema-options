<?php

function lnb_schema_description_meta_box() {
    add_meta_box('lnb-custom-schema-meta-box', 'Custom Schema Options', 'lnb_schema_description_callback', array('page','post'), 'side', 'default', null);
}

add_action('add_meta_boxes', 'lnb_schema_description_meta_box');

function lnb_schema_description_callback($page) {
    wp_nonce_field(basename(__FILE__), "lnb-meta-box-nonce");
    echo "<div><p>Page Description</p><label class='screen-reader-text' for='lnb-schema-description-text'>Page Description</label><textarea style='width:100%' name='lnb-schema-description-text' rows='10' cols='25'>".get_post_meta($page->ID, "lnb-schema-description-text", true)."</textarea>";
    echo "<p>Schema Type</p><label class='screen-reader-text' for='lnb-schema-itemtype'>Schema Itemtype</label><select name='lnb-schema-itemtype'><option value='default'";
    if (get_post_meta($page->ID, "lnb-schema-itemtype", true) == "") { echo " selected='yes'";}
    echo ">Default</option>";
    $lnb_schema_itemtype_array = schema_admin_page::get_schema_itemtypes();
    foreach ($lnb_schema_itemtype_array as $option) {
        echo "<option value='".$option['value']."'";
        if (get_post_meta($page->ID, "lnb-schema-itemtype", true) == $option['value']) {
            echo " selected='yes'";
        }
        echo ">".$option['title']."</option>";
    }
    echo "</select></div>";
}

function save_lnb_schema_meta($post_id, $post, $update)
{
    if (!isset($_POST["lnb-meta-box-nonce"]) || !wp_verify_nonce($_POST["lnb-meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $meta_box_text_value = "";

    if(isset($_POST["lnb-schema-description-text"]))
    {
        $lnb_meta_box_text_value = $_POST["lnb-schema-description-text"];
        $lnb_meta_box_itemtype_value = $_POST["lnb-schema-itemtype"];
    }   
    update_post_meta($post_id, "lnb-schema-description-text", $lnb_meta_box_text_value);
    update_post_meta($post_id, "lnb-schema-itemtype", $lnb_meta_box_itemtype_value);
}

add_action("save_post", "save_lnb_schema_meta", 10, 3);

function add_lnb_page_description_schema() {
    $page_id = get_queried_object_id();
    $lnb_schema_description_text_value = get_post_meta($page_id, 'lnb-schema-description-text', true);
    $lnb_schema_itemtype_default = get_option('lnb_schema_itemtype');
    $lnb_schema_itemtype_specific = get_post_meta($page_id, 'lnb-schema-itemtype', true);
    if ($lnb_schema_itemtype_default != $lnb_schema_itemtype_specific & $lnb_schema_itemtype_specific != 'default' ) {
        $lnb_schema_itemtype = $lnb_schema_itemtype_specific;
    }
    else {
        $lnb_schema_itemtype = $lnb_schema_itemtype_default;
    }
    $lnb_schema_description_text_html = "<div itemscope='' itemtype='http://schema.org/".$lnb_schema_itemtype."'><span itemprop='name' content='".get_option('lnb_schema_itemname')."'></span><span itemprop='description' content='".$lnb_schema_description_text_value."'></span></div>";

    if (!empty($lnb_schema_description_text_value)) {
        echo "<!-- Schema Page Description -->";
        echo $lnb_schema_description_text_html;
    }
}

add_action('wp_footer', 'add_lnb_page_description_schema', 5);
?>