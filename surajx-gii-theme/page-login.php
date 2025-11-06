<?php
/**
 * Login page template.
 *
 * @package SurajxGiiTheme
 */

get_header();
?>
<section class="section" style="display:flex;justify-content:center;">
	<div class="card" style="max-width:420px;width:100%;">
		<h1 class="section-title" style="font-size:2rem;">
			<?php esc_html_e( 'Sign in to Continue', 'surajx-gii-theme' ); ?>
		</h1>
		<?php
		echo wp_login_form(
			[
				'echo'        => false,
				'redirect'    => home_url( 'account' ),
				'label_log_in' => __( 'Login', 'surajx-gii-theme' ),
			]
		);
		?>
		<a class="btn btn-outline" style="margin-top:1rem;" href="<?php echo esc_url( rest_url( 'gii-saas/v1/oauth/google' ) ); ?>">
			<?php esc_html_e( 'Continue with Google', 'surajx-gii-theme' ); ?>
		</a>
		<p style="margin-top:1rem;">
			<a href="<?php echo esc_url( home_url( 'register' ) ); ?>"><?php esc_html_e( 'Create account', 'surajx-gii-theme' ); ?></a>
			·
			<a href="<?php echo esc_url( home_url( 'forgot-password' ) ); ?>"><?php esc_html_e( 'Forgot password?', 'surajx-gii-theme' ); ?></a>
		</p>
	</div>
</section>
<?php
get_footer();
