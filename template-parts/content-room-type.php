<?php
/**
 * Template part for displaying mphb_room_type
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package BookInn
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php book_inn_render_booking_hero( get_the_ID(), array( 'subtitle' => __( 'Accommodation details', 'book-inn' ) ) ); ?>

    <div class="single-room-type-wrapper">

        <div class="entry-content">
			<?php if (has_excerpt()): ?>
                <div class="room-excerpt">
					<?php
					$excerpt = get_the_excerpt();
					?>
                    <span class="first-letter">
                    <?php
					echo esc_html($excerpt[0]);
					?>
                </span>
                    <p>
						<?php echo wp_kses_post(substr($excerpt, 1)); ?>
                    </p>
                </div>
			<?php endif; ?>
			<?php
			the_content(
				sprintf(
					wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
						__('Continue reading<span class="screen-reader-text"> "%s"</span>', 'book-inn'),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post(get_the_title())
				)
			);

			// In MotoPress "plugin template mode", this theme template won't get
			// pseudo-template metas injected via "the_content", so render them here.
			$should_render_single_metas = function_exists( 'MPHB' ) && MPHB()->settings()->main()->isPluginTemplateMode();
			if ( $should_render_single_metas ) {
				do_action( 'mphb_render_single_room_type_metas' );
			}
			?>
        </div><!-- .entry-content -->
        <div class="single-room-type-sidebar">
			<?php
			book_inn_single_room_type_sidebar();
			?>
        </div>
    </div>

</article><!-- #post-<?php the_ID(); ?> -->
