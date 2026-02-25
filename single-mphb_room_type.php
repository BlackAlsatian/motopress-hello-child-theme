<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package BookInn
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) : ?>
			<?php
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content-room-type' );

				book_inn_post_navigation('room-types-navigation');

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>
		<?php endif; ?>

	</main><!-- #main -->

<?php
get_footer();
