<?php
/**
 * OAuth helper for Google sign-in.
 *
 * @package GII_SaaS
 */

namespace GII_SaaS;

use WP_REST_Request;
use WP_REST_Response;
use WP_Error;
use WP_User;
use WP_REST_Server;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Oauth {
	/**
	 * Hook dispatcher.
	 */
	public function register_hooks(): void {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	/**
	 * Register OAuth routes.
	 */
	public function register_routes(): void {
		register_rest_route(
			'gii-saas/v1',
			'/oauth/google',
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'redirect_google' ],
				'permission_callback' => '__return_true',
			]
		);

		register_rest_route(
			'gii-saas/v1',
			'/oauth/callback',
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'handle_callback' ],
				'permission_callback' => '__return_true',
			]
		);
	}

	/**
	 * Redirect user to Google consent screen.
	 */
	public function redirect_google(): void {
		$client_id    = trim( (string) get_option( 'gii_google_client_id', '' ) );
		$redirect_uri = esc_url_raw( rest_url( 'gii-saas/v1/oauth/callback' ) );

		if ( empty( $client_id ) ) {
			wp_safe_redirect( wp_login_url() );
			exit;
		}

		$state = wp_generate_uuid4();
		set_transient( 'gii_google_state_' . $state, [ 'timestamp' => time() ], 10 * MINUTE_IN_SECONDS );

		$params   = [
			'client_id'     => $client_id,
			'redirect_uri'  => $redirect_uri,
			'response_type' => 'code',
			'scope'         => 'openid email profile',
			'state'         => $state,
			'access_type'   => 'offline',
		];
		$auth_url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query( $params, '', '&', PHP_QUERY_RFC3986 );

		wp_safe_redirect( $auth_url );
		exit;
	}

	/**
	 * Handle OAuth callback.
	 */
	public function handle_callback( WP_REST_Request $request ): WP_REST_Response|WP_Error {
		$state = $request->get_param( 'state' );
		$code  = $request->get_param( 'code' );

		if ( empty( $state ) || ! get_transient( 'gii_google_state_' . $state ) ) {
			return new WP_Error( 'gii_invalid_state', __( 'Invalid state parameter.', 'gst-invoice-inventory-saas' ), [ 'status' => 400 ] );
		}

		delete_transient( 'gii_google_state_' . $state );

		/**
		 * In production, exchange the code with Google for profile information.
		 * This demo supports a mock email via the `email` query argument.
		 */
		$email = sanitize_email( (string) $request->get_param( 'email' ) );

		if ( empty( $code ) ) {
			return new WP_Error( 'gii_missing_code', __( 'Authorization code missing.', 'gst-invoice-inventory-saas' ), [ 'status' => 400 ] );
		}

		if ( empty( $email ) ) {
			// Without an email we cannot provision an account.
			return new WP_Error( 'gii_missing_email', __( 'Email is required to complete sign in.', 'gst-invoice-inventory-saas' ), [ 'status' => 400 ] );
		}

		$user = get_user_by( 'email', $email );

		if ( ! $user ) {
			$user_id = wp_insert_user(
				[
					'user_login' => sanitize_user( strstr( $email, '@', true ) . '_' . wp_generate_password( 4, false ) ),
					'user_email' => $email,
					'user_pass'  => wp_generate_password( 20 ),
				]
			);

			if ( is_wp_error( $user_id ) ) {
				return $user_id;
			}
			$user = get_user_by( 'id', $user_id );
		}

		if ( ! $user instanceof WP_User ) {
			return new WP_Error( 'gii_user_error', __( 'Unable to load user.', 'gst-invoice-inventory-saas' ), [ 'status' => 500 ] );
		}

		wp_set_current_user( $user->ID );
		wp_set_auth_cookie( $user->ID );

		$redirect = esc_url_raw( home_url( 'account' ) );

		return new WP_REST_Response(
			[
				'success'  => true,
				'redirect' => $redirect,
			]
		);
	}
}
