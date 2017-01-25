<?php
/*
 Shortcodes for Schema
*/ 
// [lnb-schema-areaserved]
add_shortcode( 'lnb-schema-areaserved', 'lnb_schema_area_served_shortcode' );
function lnb_schema_area_served_shortcode ( $atts ) {
    $atts = shortcode_atts( array(
    	'state' => '',
    	'cities' => array(0),
        'type' => 'textblock',
        'url' => '',
        'itemtype' => get_option('lnb_schema_itemtype'),
        'list_cols' => 'four',
        ), $atts );

    // Turn the 'cities' parameter into an array
    $atts[ 'cities' ] = explode( ",", $atts[ 'cities' ]);

    extract( $atts );

    // Determine schema itemtype
    $global_schema_itemtype = get_option('lnb_schema_itemtype');
    $schema_itemtype = $global_schema_itemtype;
    global $post;
    $specific_schema_itemtype = get_post_meta($post->ID, 'lnb-schema-itemtype', true);
    if ($specific_schema_itemtype != "" && $specific_schema_itemtype !="default") {
    	$schema_itemtype = $specific_schema_itemtype;
    }
    else {
    	$schema_itemtype = $global_schema_itemtype;
    }

    // Shortcode HTML output
	if ($schema_itemtype == $atts['itemtype']) {
		ob_start();
		echo "<div class='citylist footer-citylist'><div itemprop='areaServed' itemscope='' itemtype='http://schema.org/".$schema_itemtype."'>";
	    if ($atts['type'] == 'textblock') {
            echo "<p>";
        } elseif ($atts['type'] == 'list') {
            echo "<ul class='".$atts['list_cols']."-column-responsive-list'>";
        }

        $num_cities = $numItems = count($atts['cities']);
        $i = 0;
    	foreach ($atts['cities'] as $cities) {
    		$cities = trim($cities);
    		//Build link for cities
    		$city_href = strtolower(str_ireplace(['{city}','{state}'],[str_replace(" ", "-", $cities),$atts['state']],$atts['url']));
    		if ($atts['type'] == 'textblock') {
				echo "<span itemprop='name'><a href='".$city_href."'>".$cities."</a></span>";
                if (++$i != $num_cities) { echo "<span class='pipe'> | </span>";}
			}
			elseif ($atts['type'] == 'list') {
				echo "<li itemprop='name'><a href='".$city_href."'>".$cities."</a></li>";
			}
    	}
    	if ($atts['type'] == 'textblock') { echo "<p>";} elseif ($atts['type'] == 'list') { echo "</ul>";}
	    echo "</div></div>";
	    $html_output = ob_get_contents();
	    ob_end_clean();
	    return $html_output;
	}
}


?>