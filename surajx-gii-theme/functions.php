<?php
/**
 * SurajX GII Theme bootstrap.
 *
 * @package SurajxGiiTheme
 */

define( 'SURAJX_GII_THEME_VERSION', '1.0.0' );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_setup_theme', function () {
	load_theme_textdomain( 'surajx-gii-theme', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	register_nav_menus(
		[
			'primary' => __( 'Primary Menu', 'surajx-gii-theme' ),
			'footer'  => __( 'Footer Menu', 'surajx-gii-theme' ),
		]
	);
} );

add_action( 'wp_enqueue_scripts', function () {
	$version = wp_get_theme()->get( 'Version' );
	wp_enqueue_style( 'surajx-gii-theme-style', get_template_directory_uri() . '/style.css', [], $version );
	wp_enqueue_script( 'surajx-gii-theme-app', get_template_directory_uri() . '/assets/js/app.js', [ 'wp-i18n', 'wp-api-fetch' ], $version, true );

	$rest_base = esc_url_raw( rest_url( 'gii-saas/v1/' ) );
	wp_localize_script(
		'surajx-gii-theme-app',
		'GIIThemeConfig',
		[
			'restBase'       => $rest_base,
			'nonce'          => wp_create_nonce( 'wp_rest' ),
			'strings'        => [
				'products'        => __( 'Products', 'surajx-gii-theme' ),
				'invoices'        => __( 'Invoices', 'surajx-gii-theme' ),
				'account'         => __( 'Account', 'surajx-gii-theme' ),
				'noProducts'      => __( 'No products found.', 'surajx-gii-theme' ),
				'noInvoices'      => __( 'No invoices generated yet.', 'surajx-gii-theme' ),
				'loading'         => __( 'Loading...', 'surajx-gii-theme' ),
				'oauthButtonText' => __( 'Sign in with Google', 'surajx-gii-theme' ),
			],
			'oauthUrl'       => esc_url_raw( rest_url( 'gii-saas/v1/oauth/google' ) ),
		]
	);
} );

add_shortcode( 'gii_customer_dashboard', function () {
	if ( ! is_user_logged_in() ) {
		return sprintf(
			'<div class="gii-dashboard-login"><p>%s</p><a class="btn btn-primary" href="%s">%s</a></div>',
			esc_html__( 'Please log in to access your dashboard.', 'surajx-gii-theme' ),
			esc_url( wp_login_url( get_permalink() ) ),
			esc_html__( 'Login', 'surajx-gii-theme' )
		);
	}

	ob_start();
	?>
	<div id="gii-dashboard" class="dashboard" data-gii-rest="<?php echo esc_url( rest_url( 'gii-saas/v1' ) ); ?>">
		<nav class="dashboard-nav">
			<button type="button" data-tab="products" class="active"><?php esc_html_e( 'Products', 'surajx-gii-theme' ); ?></button>
			<button type="button" data-tab="invoices"><?php esc_html_e( 'Invoices', 'surajx-gii-theme' ); ?></button>
			<button type="button" data-tab="account"><?php esc_html_e( 'Account', 'surajx-gii-theme' ); ?></button>
		</nav>
		<section class="dashboard-content">
			<div class="dashboard-panel" data-panel="products"></div>
			<div class="dashboard-panel" data-panel="invoices" hidden></div>
			<div class="dashboard-panel" data-panel="account" hidden></div>
		</section>
	</div>
	<?php
	return ob_get_clean();
} );

add_shortcode( 'gii_invoice_builder', function () {
	if ( ! is_user_logged_in() ) {
		return sprintf(
			'<div class="gii-dashboard-login"><p>%s</p><a class="btn btn-primary" href="%s">%s</a></div>',
			esc_html__( 'Please log in to build invoices.', 'surajx-gii-theme' ),
			esc_url( wp_login_url( get_permalink() ) ),
			esc_html__( 'Login', 'surajx-gii-theme' )
		);
	}

	ob_start();
	?>
	<form class="gii-invoice-builder" id="gii-invoice-builder">
		<div class="card">
			<h2><?php esc_html_e( 'Create Invoice', 'surajx-gii-theme' ); ?></h2>
			<label>
				<span><?php esc_html_e( 'Customer Name', 'surajx-gii-theme' ); ?></span>
				<input type="text" name="customer_name" required />
			</label>
			<label>
				<span><?php esc_html_e( 'GST Number', 'surajx-gii-theme' ); ?></span>
				<input type="text" name="gst_number" required />
			</label>
			<div id="gii-product-lines"></div>
			<button type="button" class="btn btn-outline" id="gii-add-line"><?php esc_html_e( 'Add Product Line', 'surajx-gii-theme' ); ?></button>
			<button type="submit" class="btn btn-primary"><?php esc_html_e( 'Generate Invoice', 'surajx-gii-theme' ); ?></button>
		</div>
	</form>
	<div class="gii-invoice-result" id="gii-invoice-result"></div>
	<?php
	return ob_get_clean();
} );

add_filter( 'template_include', function ( $template ) {
	if ( is_page_template() ) {
		return $template;
	}

	$page_template_map = [
		'login'           => 'page-login.php',
		'account'         => 'page-account.php',
		'forgot-password' => 'page-forgot-password.php',
		'pricing'         => 'page-pricing.php',
	];

	foreach ( $page_template_map as $slug => $file ) {
		$page = get_page_by_path( $slug );
		if ( $page && (int) $page->ID === get_queried_object_id() ) {
			$located = locate_template( $file );
			if ( $located ) {
				return $located;
			}
		}
	}

	return $template;
} );

add_action( 'widgets_init', function () {
	register_sidebar( [
		'name'          => __( 'Footer Widgets', 'surajx-gii-theme' ),
		'id'            => 'footer-widgets',
		'before_widget' => '<div class="footer-widget">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="footer-widget-title">',
		'after_title'   => '</h3>',
	] );
} );
