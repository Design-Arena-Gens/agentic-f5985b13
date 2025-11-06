<?php
/**
 * Pricing page template.
 *
 * @package SurajxGiiTheme
 */

get_header();
?>
<section class="section">
	<header style="text-align:center;margin-bottom:3rem;">
		<h1 class="section-title"><?php esc_html_e( 'Transparent Pricing', 'surajx-gii-theme' ); ?></h1>
		<p class="section-subtitle"><?php esc_html_e( 'Choose the plan that suits your customer volume.', 'surajx-gii-theme' ); ?></p>
	</header>
	<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1.5rem;">
		<article class="card">
			<h2><?php esc_html_e( 'Starter', 'surajx-gii-theme' ); ?></h2>
			<p class="section-subtitle"><?php esc_html_e( 'Perfect for early-stage SaaS founders.', 'surajx-gii-theme' ); ?></p>
			<ul>
				<li><?php esc_html_e( 'Up to 500 invoices / month', 'surajx-gii-theme' ); ?></li>
				<li><?php esc_html_e( 'GST compliant PDFs', 'surajx-gii-theme' ); ?></li>
				<li><?php esc_html_e( 'Email support', 'surajx-gii-theme' ); ?></li>
			</ul>
			<a class="btn btn-primary" href="<?php echo esc_url( add_query_arg( 'plan', 'starter', get_permalink( get_page_by_path( 'account' ) ) ) ); ?>"><?php esc_html_e( 'Start Free Trial', 'surajx-gii-theme' ); ?></a>
		</article>
		<article class="card" style="border:2px solid var(--gii-primary);">
			<h2><?php esc_html_e( 'Growth', 'surajx-gii-theme' ); ?></h2>
			<p class="section-subtitle"><?php esc_html_e( 'Scale with audit-ready compliance.', 'surajx-gii-theme' ); ?></p>
			<ul>
				<li><?php esc_html_e( 'Up to 5,000 invoices / month', 'surajx-gii-theme' ); ?></li>
				<li><?php esc_html_e( 'Multi-GSTIN support', 'surajx-gii-theme' ); ?></li>
				<li><?php esc_html_e( 'Priority support', 'surajx-gii-theme' ); ?></li>
			</ul>
			<a class="btn btn-primary" href="<?php echo esc_url( add_query_arg( 'plan', 'growth', get_permalink( get_page_by_path( 'account' ) ) ) ); ?>"><?php esc_html_e( 'Upgrade Now', 'surajx-gii-theme' ); ?></a>
		</article>
		<article class="card">
			<h2><?php esc_html_e( 'Enterprise', 'surajx-gii-theme' ); ?></h2>
			<p class="section-subtitle"><?php esc_html_e( 'Custom workflows, sandbox, and API SLAs.', 'surajx-gii-theme' ); ?></p>
			<ul>
				<li><?php esc_html_e( 'Unlimited invoices', 'surajx-gii-theme' ); ?></li>
				<li><?php esc_html_e( 'Dedicated success manager', 'surajx-gii-theme' ); ?></li>
				<li><?php esc_html_e( 'Custom integrations', 'surajx-gii-theme' ); ?></li>
			</ul>
			<a class="btn btn-outline" href="mailto:sales@example.com?subject=GII%20Enterprise"><?php esc_html_e( 'Talk to Sales', 'surajx-gii-theme' ); ?></a>
		</article>
	</div>
</section>
<?php
get_footer();
