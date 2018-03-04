<?php
/*
Plugin Name: barcode
Plugin URI: http://www.tcarisland.com
Description: A barcode svg generator
Version: 1.0
Author: Thor Arisland
Author URI: http://www.tcarisland.com
License: Free for personal use
*/

add_action( 'wp_enqueue_scripts', 'barcode_deps' );

function get_barcode() {
    $template = file_get_contents(plugin_dir_url(__FILE__) . "template.html");
    $retval = $template;
    return $retval;
}

function barcode_deps() {
    wp_enqueue_style("barcode_style", plugin_dir_url(__FILE__) . "style.css");
    wp_enqueue_script("barcode_script", plugin_dir_url(__FILE__) . "js/barcode.js", array('jquery'), null, false);
}

add_shortcode('BARCODE_PLUGIN', 'get_barcode');

?>