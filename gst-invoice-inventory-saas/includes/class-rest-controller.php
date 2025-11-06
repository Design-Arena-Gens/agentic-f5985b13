<?php
/**
 * REST controller.
 *
 * @package GII_SaaS
 */

namespace GII_SaaS;

use WP_REST_Server;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Rest_Controller {
	/**
	 * Hook into REST API.
	 */
	public function register_hooks(): void {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	/**
	 * Register plugin routes.
	 */
	public function register_routes(): void {
		register_rest_route(
			'gii-saas/v1',
			'/products',
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'list_products' ],
				'permission_callback' => [ $this, 'ensure_authenticated' ],
			]
		);

		register_rest_route(
			'gii-saas/v1',
			'/invoices',
			[
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'list_invoices' ],
					'permission_callback' => [ $this, 'ensure_authenticated' ],
				],
				[
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'create_invoice' ],
					'permission_callback' => [ $this, 'ensure_authenticated' ],
					'args'                => [
						'customer_name' => [
							'required'          => true,
							'sanitize_callback' => 'sanitize_text_field',
						],
						'gst_number'   => [
							'required'          => true,
							'sanitize_callback' => 'sanitize_text_field',
						],
						'line_items'   => [
							'required'          => true,
						],
					],
				],
			]
		);

		register_rest_route(
			'gii-saas/v1',
			'/account',
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'get_account' ],
				'permission_callback' => [ $this, 'ensure_authenticated' ],
			]
		);
	}

	/**
	 * Ensure user is authenticated.
	 */
	public function ensure_authenticated(): bool|WP_Error {
		if ( is_user_logged_in() ) {
			return true;
		}

		return new WP_Error( 'gii_not_logged_in', __( 'Authentication required.', 'gst-invoice-inventory-saas' ), [ 'status' => 401 ] );
	}

	/**
	 * List products for user.
	 */
	public function list_products(): WP_REST_Response {
		$query = new \WP_Query(
			[
				'post_type'      => 'gii_product',
				'posts_per_page' => 100,
				'post_status'    => 'publish',
			]
		);

		$products = array_map(
			static function ( $post ) {
				return [
					'id'       => (int) $post->ID,
					'name'     => get_the_title( $post ),
					'price'    => (float) get_post_meta( $post->ID, '_gii_price', true ),
					'gst_rate' => (float) get_post_meta( $post->ID, '_gii_gst_rate', true ),
				];
			},
			$query->posts
		);

		return new WP_REST_Response( $products );
	}

	/**
	 * List invoices.
	 */
	public function list_invoices(): WP_REST_Response {
		$query = new \WP_Query(
			[
				'post_type'      => 'gii_invoice',
				'posts_per_page' => 50,
				'post_status'    => 'publish',
			]
		);

		$invoices = array_map(
			static function ( $post ) {
				return [
					'id'             => (int) $post->ID,
					'invoice_number' => get_post_meta( $post->ID, '_gii_invoice_number', true ),
					'customer_name'  => get_post_meta( $post->ID, '_gii_customer_name', true ),
					'total_amount'   => (float) get_post_meta( $post->ID, '_gii_total_amount', true ),
					'pdf_url'        => wp_get_attachment_url( get_post_meta( $post->ID, '_gii_pdf_attachment', true ) ) ?: '',
				];
			},
			$query->posts
		);

		return new WP_REST_Response( $invoices );
	}

	/**
	 * Create invoice.
	 */
	public function create_invoice( WP_REST_Request $request ): WP_REST_Response|WP_Error {
		$data       = $request->get_json_params();
		$line_items = sanitize_line_items( (array) ( $data['line_items'] ?? [] ) );

		if ( empty( $line_items ) ) {
			return new WP_Error( 'gii_invalid_items', __( 'Line items are required.', 'gst-invoice-inventory-saas' ), [ 'status' => 422 ] );
		}

		$total = array_reduce(
			$line_items,
			static function ( $carry, $item ) {
				$price    = (float) $item['price'];
				$gst_rate = (float) $item['gst_rate'];
				return $carry + $price + ( $price * $gst_rate / 100 );
			},
			0.0
		);

		$invoice_count = (int) wp_count_posts( 'gii_invoice' )->publish + 1;
		$invoice_no    = generate_invoice_number( $invoice_count );

		$post_id = wp_insert_post(
			[
				'post_type'   => 'gii_invoice',
				'post_title'  => sanitize_text_field( $invoice_no ),
				'post_status' => 'publish',
			]
		);

		if ( is_wp_error( $post_id ) ) {
			return $post_id;
		}

		update_post_meta( $post_id, '_gii_invoice_number', $invoice_no );
		update_post_meta( $post_id, '_gii_customer_name', sanitize_text_field( $data['customer_name'] ?? '' ) );
		update_post_meta( $post_id, '_gii_gst_number', sanitize_text_field( $data['gst_number'] ?? '' ) );
		update_post_meta( $post_id, '_gii_total_amount', $total );
		update_post_meta( $post_id, '_gii_line_items', wp_json_encode( $line_items ) );

		$response = [
			'invoice_number' => $invoice_no,
			'total_amount'   => round( $total, 2 ),
			'pdf_url'        => '',
		];

		return new WP_REST_Response( $response, 201 );
	}

	/**
	 * Account summary for user.
	 */
	public function get_account(): WP_REST_Response {
		$user_id   = get_current_user_id();
		$plan      = get_user_meta( $user_id, '_gii_plan', true ) ?: __( 'Starter', 'gst-invoice-inventory-saas' );
		$usage     = (int) get_user_meta( $user_id, '_gii_invoice_usage', true );
		$response = [
			'plan'          => $plan,
			'invoice_usage' => $usage,
		];

		return new WP_REST_Response( $response );
	}
}
