<?php
/**
 * Plugin Name: GST Invoice Inventory SaaS
 * Plugin URI: https://example.com/gii-saas
 * Description: Provides GST invoicing, products, inventory REST APIs, and OAuth flows for the SurajX SaaS theme.
 * Version: 1.0.0
 * Author: Codex Assistant
 * Requires PHP: 8.0
 * Requires at least: 6.0
 * Text Domain: gst-invoice-inventory-saas
 * Domain Path: /languages
 */

namespace GII_SaaS;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( __NAMESPACE__ . '\\PLUGIN_FILE', __FILE__ );
define( __NAMESPACE__ . '\\PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( __NAMESPACE__ . '\\PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( __NAMESPACE__ . '\\PLUGIN_VERSION', '1.0.0' );

spl_autoload_register(
	function ( $class ) {
		if ( str_starts_with( $class, __NAMESPACE__ . '\\' ) ) {
			$relative = strtolower( str_replace( [ __NAMESPACE__ . '\\', '_' ], [ '', '-' ], $class ) );
			$file     = PLUGIN_PATH . 'includes/class-' . $relative . '.php';
			if ( file_exists( $file ) ) {
				require_once $file;
			}
		}
	}
);

require_once PLUGIN_PATH . 'includes/helpers.php';

register_activation_hook( __FILE__, [ __NAMESPACE__ . '\\Plugin', 'activate' ] );
register_deactivation_hook( __FILE__, [ __NAMESPACE__ . '\\Plugin', 'deactivate' ] );

add_action( 'plugins_loaded', function () {
	load_plugin_textdomain( 'gst-invoice-inventory-saas', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	Plugin::instance()->boot();
} );
