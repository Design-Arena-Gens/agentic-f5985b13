<?php
/**
 * Forgot password page template.
 *
 * @package SurajxGiiTheme
 */

$messages = [];
if ( 'POST' === $_SERVER['REQUEST_METHOD'] ) {
	check_admin_referer( 'gii_reset_action', 'gii_reset_nonce' );

	$username_or_email = sanitize_text_field( wp_unslash( $_POST['user_login'] ?? '' ) );

	if ( empty( $username_or_email ) ) {
		$messages[] = [ 'type' => 'error', 'text' => __( 'Please enter your username or email.', 'surajx-gii-theme' ) ];
	} else {
		$result = retrieve_password( $username_or_email );
		if ( true === $result ) {
			$messages[] = [ 'type' => 'success', 'text' => __( 'Check your email for the confirmation link.', 'surajx-gii-theme' ) ];
		} elseif ( is_wp_error( $result ) ) {
			$messages[] = [ 'type' => 'error', 'text' => $result->get_error_message() ];
		}
	}
}

get_header();
?>
<section class="section" style="display:flex;justify-content:center;">
	<div class="card" style="max-width:420px;width:100%;">
		<h1 class="section-title" style="font-size:2rem;">
			<?php esc_html_e( 'Reset your password', 'surajx-gii-theme' ); ?>
		</h1>
		<?php foreach ( $messages as $message ) : ?>
			<p style="color:<?php echo 'success' === $message['type'] ? '#047857' : '#b91c1c'; ?>;" role="alert">
				<?php echo esc_html( $message['text'] ); ?>
			</p>
		<?php endforeach; ?>
		<form method="post">
			<?php wp_nonce_field( 'gii_reset_action', 'gii_reset_nonce' ); ?>
			<label>
				<span><?php esc_html_e( 'Username or Email', 'surajx-gii-theme' ); ?></span>
				<input type="text" name="user_login" required />
			</label>
			<button type="submit" class="btn btn-primary" style="margin-top:1rem;">
				<?php esc_html_e( 'Send Reset Link', 'surajx-gii-theme' ); ?>
			</button>
		</form>
		<p style="margin-top:1rem;">
			<a href="<?php echo esc_url( home_url( 'login' ) ); ?>"><?php esc_html_e( 'Back to login', 'surajx-gii-theme' ); ?></a>
		</p>
	</div>
</section>
<?php
get_footer();
