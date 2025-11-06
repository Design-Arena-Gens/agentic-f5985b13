<?php
/**
 * Footer template.
 *
 * @package SurajxGiiTheme
 */
?>
	</div>
</main>
<footer class="site-footer" role="contentinfo">
	<div class="container" style="display:flex;flex-direction:column;gap:1rem;">
		<div style="display:flex;justify-content:space-between;gap:2rem;flex-wrap:wrap;align-items:flex-start;">
			<div>
				<strong><?php bloginfo( 'name' ); ?></strong>
				<p><?php bloginfo( 'description' ); ?></p>
			</div>
			<div>
				<?php
				if ( is_active_sidebar( 'footer-widgets' ) ) {
					dynamic_sidebar( 'footer-widgets' );
				}
				?>
			</div>
			<nav aria-label="<?php esc_attr_e( 'Footer', 'surajx-gii-theme' ); ?>">
				<?php
				wp_nav_menu(
					[
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'      => 'menu',
						'fallback_cb'    => '__return_empty_string',
					]
				);
				?>
			</nav>
		</div>
		<p style="color:var(--gii-muted);font-size:0.875rem;">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'surajx-gii-theme' ); ?></p>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
