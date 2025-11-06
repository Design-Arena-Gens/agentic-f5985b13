<?php
/**
 * Account page template.
 *
 * @package SurajxGiiTheme
 */

get_header();
?>
<section class="section">
	<header style="display:flex;flex-direction:column;gap:0.5rem;margin-bottom:2rem;">
		<h1 class="section-title"><?php esc_html_e( 'Customer Dashboard', 'surajx-gii-theme' ); ?></h1>
		<p class="section-subtitle"><?php esc_html_e( 'Manage products, generate invoices, and update account settings.', 'surajx-gii-theme' ); ?></p>
	</header>
	<?php echo do_shortcode( '[gii_customer_dashboard]' ); ?>
	<section style="margin-top:3rem;">
		<h2><?php esc_html_e( 'Build Invoice', 'surajx-gii-theme' ); ?></h2>
		<?php echo do_shortcode( '[gii_invoice_builder]' ); ?>
	</section>
</section>
<?php
get_footer();
