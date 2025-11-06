<?php
/**
 * Default index template.
 *
 * @package SurajxGiiTheme
 */

get_header();
?>
<section class="section">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<article class="card" id="post-<?php the_ID(); ?>">
				<h1><?php the_title(); ?></h1>
				<div><?php the_content(); ?></div>
			</article>
		<?php endwhile; ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Nothing found.', 'surajx-gii-theme' ); ?></p>
	<?php endif; ?>
</section>
<?php
get_footer();
