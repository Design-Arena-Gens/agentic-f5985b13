<?php
/**
 * Front page template.
 *
 * @package SurajxGiiTheme
 */

global $post;
get_header();
?>
<section class="hero section">
	<div class="card" style="display:grid;gap:1.5rem;">
		<h1 class="section-title"><?php esc_html_e( 'GST Invoicing Simplified for Indian SaaS Businesses', 'surajx-gii-theme' ); ?></h1>
		<p class="section-subtitle"><?php esc_html_e( 'Generate compliant GST invoices, manage products, and track taxes in real time.', 'surajx-gii-theme' ); ?></p>
		<div style="display:flex;gap:1rem;flex-wrap:wrap;">
			<a class="btn btn-primary" href="<?php echo esc_url( get_permalink( get_page_by_path( 'pricing' ) ) ); ?>"><?php esc_html_e( 'View Pricing', 'surajx-gii-theme' ); ?></a>
			<a class="btn btn-outline" href="<?php echo esc_url( get_permalink( get_page_by_path( 'account' ) ) ); ?>"><?php esc_html_e( 'Launch Dashboard', 'surajx-gii-theme' ); ?></a>
		</div>
	</div>
</section>
<section class="section">
	<div class="section-header">
		<h2 class="section-title"><?php esc_html_e( 'Why SurajX GII?', 'surajx-gii-theme' ); ?></h2>
		<p class="section-subtitle"><?php esc_html_e( 'Purpose-built tooling for Indian SaaS founders managing GST compliance.', 'surajx-gii-theme' ); ?></p>
	</div>
	<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1.5rem;">
		<article class="card">
			<h3><?php esc_html_e( 'Automated Invoice Generation', 'surajx-gii-theme' ); ?></h3>
			<p><?php esc_html_e( 'Draft GST-compliant invoices instantly with validated GSTIN and dynamic tax allocation.', 'surajx-gii-theme' ); ?></p>
		</article>
		<article class="card">
			<h3><?php esc_html_e( 'Inventory Intelligence', 'surajx-gii-theme' ); ?></h3>
			<p><?php esc_html_e( 'Manage plan catalogs, itemized billing, and stock levels with ease.', 'surajx-gii-theme' ); ?></p>
		</article>
		<article class="card">
			<h3><?php esc_html_e( 'GST Return Ready', 'surajx-gii-theme' ); ?></h3>
			<p><?php esc_html_e( 'Export data purpose-built for GSTR-1 and GSTR-3B filings.', 'surajx-gii-theme' ); ?></p>
		</article>
	</div>
</section>
<section class="section" style="background:#fff;border-radius:1.5rem;padding:2.5rem;">
	<h2 class="section-title" style="margin-bottom:1rem;">
		<?php esc_html_e( 'Unified Portal for Your Customers', 'surajx-gii-theme' ); ?>
	</h2>
	<?php echo do_shortcode( '[gii_customer_dashboard]' ); ?>
</section>
<?php
get_footer();
