<?php
/**
 * Plugin Name: Product shipping notices for WCFM
 * Plugin URI: https://art-idesenvolvimento.com.br/wordpress/plugins/aviso-de-frete-para-marketplaces
 * Description:
 * Version: 0.1.1
 * Requires at least:    5.6.1
 * Tested up to:         5.8.2
 * WC requires at least: 5.0.0
 * WC tested up to:      5.9.0
 * Requires PHP:         7.1
 * Author: Luis Eduardo Casper Braschi
 * Author URI: http://art-idesenvolvimento.com.br
 * Text Domain: arti-psn
 * Domain Path: languages/
 */

defined( 'ABSPATH' ) or die;

define( 'ARTI_PSN_FILE',     __FILE__ );
define( 'ARTI_PSN_BASENAME', plugin_basename( ARTI_PSN_FILE ));
define( 'ARTI_PSN_URL',      plugin_dir_url( ARTI_PSN_FILE ));
define( 'ARTI_PSN_PATH',     untrailingslashit(plugin_dir_path( ARTI_PSN_FILE )));

define( 'ARTI_PSN_SLUG',           'arti-psn' ); //textdomain as well

// define( 'ARTI_PSN_FONTS', ARTI_PSN_URL . 'assets/fonts' );
// define( 'ARTI_PSN_IMG',   ARTI_PSN_URL . 'assets/images' );
// define( 'ARTI_PSN_JS',    ARTI_PSN_URL . 'assets/js' );
// define( 'ARTI_PSN_CSS',   ARTI_PSN_URL . 'assets/css' );

define( 'ARTI_PSN_TEMPLATE',   ARTI_PSN_PATH . '/templates/' );

function arti_psn_includes(){
	include_once ARTI_PSN_PATH . '/includes/marketplace-functions.php';
	include_once ARTI_PSN_PATH . '/includes/class-shipping-notices.php';
	include_once ARTI_PSN_PATH . '/includes/class-core.php';
}

add_action( 'plugin_loaded', 'arti_psn_includes' );

function arti_psn_load_plugin_textdomain() {
	load_plugin_textdomain( 'arti-psn', false, dirname( ARTI_PSN_BASENAME ) . '/languages' );
}
add_action( 'init', 'arti_psn_load_plugin_textdomain' );
