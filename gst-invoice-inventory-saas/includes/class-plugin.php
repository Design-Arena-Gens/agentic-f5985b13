<?php
/**
 * Core plugin orchestrator.
 *
 * @package GII_SaaS
 */

namespace GII_SaaS;

use GII_SaaS\Rest_Controller;
use GII_SaaS\Cpt;
use GII_SaaS\Oauth;
use GII_SaaS\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Plugin {
	/**
	 * Singleton instance.
	 *
	 * @var Plugin|null
	 */
	private static ?Plugin $instance = null;

	/**
	 * Registered services.
	 *
	 * @var array<int, object>
	 */
	private array $services = [];

	private function __construct() {}

	/**
	 * Access singleton.
	 */
	public static function instance(): Plugin {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Bootstrap plugin.
	 */
	public function boot(): void {
		$this->register_service( new Cpt() );
		$this->register_service( new Rest_Controller() );
		$this->register_service( new Oauth() );
		$this->register_service( new Admin() );

		add_action( 'init', [ $this, 'register_assets' ] );
	}

	/**
	 * Register activation tasks.
	 */
	public static function activate(): void {
		flush_rewrite_rules();
	}

	/**
	 * Cleanup on deactivate.
	 */
	public static function deactivate(): void {
		flush_rewrite_rules();
	}

	/**
	 * Register service.
	 */
	private function register_service( object $service ): void {
		if ( method_exists( $service, 'register_hooks' ) ) {
			$service->register_hooks();
		}
		$this->services[] = $service;
	}

	/**
	 * Register plugin assets.
	 */
	public function register_assets(): void {
		wp_register_style( 'gii-saas-admin', PLUGIN_URL . 'assets/css/admin.css', [], PLUGIN_VERSION );
		wp_register_script( 'gii-saas-dashboard', PLUGIN_URL . 'assets/js/dashboard.js', [ 'wp-element', 'wp-api-fetch' ], PLUGIN_VERSION, true );
	}
}
