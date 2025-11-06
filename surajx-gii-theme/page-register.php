<?php
/**
 * Registration page template.
 *
 * @package SurajxGiiTheme
 */

if ( is_user_logged_in() ) {
	wp_safe_redirect( home_url( 'account' ) );
	exit;
}

$errors = [];
if ( 'POST' === $_SERVER['REQUEST_METHOD'] ) {
	check_admin_referer( 'gii_register_action', 'gii_register_nonce' );

	$username = sanitize_user( wp_unslash( $_POST['username'] ?? '' ) );
	$email    = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$password = $_POST['password'] ?? '';

	if ( empty( $username ) || username_exists( $username ) ) {
		$errors[] = __( 'Please choose a different username.', 'surajx-gii-theme' );
	}

	if ( empty( $email ) || ! is_email( $email ) || email_exists( $email ) ) {
		$errors[] = __( 'Please provide a valid email address.', 'surajx-gii-theme' );
	}

	if ( empty( $password ) || strlen( $password ) < 8 ) {
		$errors[] = __( 'Password must be at least 8 characters.', 'surajx-gii-theme' );
	}

	if ( empty( $errors ) ) {
		$user_id = wp_create_user( $username, $password, $email );
		if ( is_wp_error( $user_id ) ) {
			$errors[] = $user_id->get_error_message();
		} else {
			wp_set_current_user( $user_id );
			wp_set_auth_cookie( $user_id );
			wp_safe_redirect( home_url( 'account' ) );
			exit;
		}
	}
}

get_header();
?>
<section class="section" style="display:flex;justify-content:center;">
	<div class="card" style="max-width:480px;width:100%;">
		<h1 class="section-title" style="font-size:2rem;">
			<?php esc_html_e( 'Create your account', 'surajx-gii-theme' ); ?>
		</h1>
		<?php if ( ! empty( $errors ) ) : ?>
			<div class="form-errors" role="alert" style="color:#b91c1c;margin-bottom:1rem;">
				<?php foreach ( $errors as $error ) : ?>
					<p><?php echo esc_html( $error ); ?></p>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<form method="post">
			<?php wp_nonce_field( 'gii_register_action', 'gii_register_nonce' ); ?>
			<label>
				<span><?php esc_html_e( 'Username', 'surajx-gii-theme' ); ?></span>
				<input type="text" name="username" required />
			</label>
			<label>
				<span><?php esc_html_e( 'Email', 'surajx-gii-theme' ); ?></span>
				<input type="email" name="email" required />
			</label>
			<label>
				<span><?php esc_html_e( 'Password', 'surajx-gii-theme' ); ?></span>
				<input type="password" name="password" required />
			</label>
			<button type="submit" class="btn btn-primary" style="margin-top:1rem;">
				<?php esc_html_e( 'Register', 'surajx-gii-theme' ); ?>
			</button>
		</form>
		<p style="margin-top:1rem;">
			<?php esc_html_e( 'Already have an account?', 'surajx-gii-theme' ); ?>
			<a href="<?php echo esc_url( home_url( 'login' ) ); ?>"><?php esc_html_e( 'Login', 'surajx-gii-theme' ); ?></a>
		</p>
	</div>
</section>
<?php
get_footer();
