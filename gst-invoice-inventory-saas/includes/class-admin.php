<?php
/**
 * Admin interface for plugin options.
 *
 * @package GII_SaaS
 */

namespace GII_SaaS;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Admin {
	/**
	 * Hook registrations.
	 */
	public function register_hooks(): void {
		add_action( 'admin_menu', [ $this, 'register_settings_page' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
	}

	/**
	 * Register settings page.
	 */
	public function register_settings_page(): void {
		add_options_page(
			__( 'GII SaaS Settings', 'gst-invoice-inventory-saas' ),
			__( 'GII SaaS', 'gst-invoice-inventory-saas' ),
			'manage_options',
			'gii-saas-settings',
			[ $this, 'render_settings_page' ]
		);
	}

	/**
	 * Register settings + fields.
	 */
	public function register_settings(): void {
		register_setting( 'gii_saas', 'gii_google_client_id', [
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
		] );

		add_settings_section(
			'gii_saas_oauth',
			__( 'OAuth', 'gst-invoice-inventory-saas' ),
			fn () => print '<p>' . esc_html__( 'Configure Google OAuth credentials used for customer sign-in.', 'gst-invoice-inventory-saas' ) . '</p>',
			'gii-saas-settings'
		);

		add_settings_field(
			'gii_google_client_id',
			__( 'Google Client ID', 'gst-invoice-inventory-saas' ),
			fn () => $this->render_input( 'gii_google_client_id' ),
			'gii-saas-settings',
			'gii_saas_oauth'
		);
	}

	/**
	 * Render settings page.
	 */
	public function render_settings_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'GII SaaS Settings', 'gst-invoice-inventory-saas' ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'gii_saas' );
				do_settings_sections( 'gii-saas-settings' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Render text input field.
	 */
	private function render_input( string $option ): void {
		$value = esc_attr( get_option( $option, '' ) );
		printf( '<input type="text" class="regular-text" name="%1$s" value="%2$s" />', esc_attr( $option ), $value );
	}
}
