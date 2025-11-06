<?php
/**
 * Header template.
 *
 * @package SurajxGiiTheme
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="site-header">
	<div class="container" role="banner">
		<div class="header-inner" style="display:flex;align-items:center;justify-content:space-between;gap:2rem;">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-brand">
				<strong><?php bloginfo( 'name' ); ?></strong>
			</a>
			<nav class="site-nav" aria-label="<?php esc_attr_e( 'Primary', 'surajx-gii-theme' ); ?>">
				<?php
				wp_nav_menu(
					[
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'      => 'menu',
						'fallback_cb'    => '__return_empty_string',
					]
				);
				?>
			</nav>
			<div class="header-actions" style="display:flex;gap:1rem;align-items:center;">
				<?php if ( is_user_logged_in() ) : ?>
					<a class="btn btn-outline" href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>"><?php esc_html_e( 'Logout', 'surajx-gii-theme' ); ?></a>
				<?php else : ?>
					<a class="btn btn-outline" href="<?php echo esc_url( wp_login_url() ); ?>"><?php esc_html_e( 'Login', 'surajx-gii-theme' ); ?></a>
					<a class="btn btn-primary" href="<?php echo esc_url( rest_url( 'gii-saas/v1/oauth/google' ) ); ?>">
						<span class="dashicons dashicons-google"></span>
						<?php esc_html_e( 'Sign in with Google', 'surajx-gii-theme' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</header>
<main class="site-main">
	<div class="container">
