<?php
/**
 * Custom post type registration.
 *
 * @package GII_SaaS
 */

namespace GII_SaaS;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cpt {
	/**
	 * Hook registration.
	 */
	public function register_hooks(): void {
		add_action( 'init', [ $this, 'register_product_cpt' ] );
		add_action( 'init', [ $this, 'register_invoice_cpt' ] );
	}

	/**
	 * Register product CPT.
	 */
	public function register_product_cpt(): void {
		$labels = [
			'name'          => __( 'Products', 'gst-invoice-inventory-saas' ),
			'singular_name' => __( 'Product', 'gst-invoice-inventory-saas' ),
		];

		$args = [
			'labels'             => $labels,
			'public'             => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_rest'       => true,
			'rest_base'          => 'gii-products',
			'supports'           => [ 'title', 'editor' ],
			'capability_type'    => 'post',
			'map_meta_cap'       => true,
		];

		register_post_type( 'gii_product', $args );
	}

	/**
	 * Register invoice CPT.
	 */
	public function register_invoice_cpt(): void {
		$labels = [
			'name'          => __( 'Invoices', 'gst-invoice-inventory-saas' ),
			'singular_name' => __( 'Invoice', 'gst-invoice-inventory-saas' ),
		];

		$args = [
			'labels'             => $labels,
			'public'             => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_rest'       => true,
			'rest_base'          => 'gii-invoices',
			'supports'           => [ 'title', 'editor' ],
			'capability_type'    => 'post',
			'map_meta_cap'       => true,
		];

		register_post_type( 'gii_invoice', $args );
	}
}
