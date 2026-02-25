<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @link https://developer.wordpress.org/reference/functions/comment_form/
 *
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required() || !mphbr_is_reviews_enabled_for_post()) {
	return;
}

do_action('mphbr_reviews_content_before');

?>

    <div id="comments" class="comments-area mphb-reviews">

		<?php
		$book_inn_rating_count = MPHBR()->getRatingManager()->getGlobalRatingsCount(get_the_ID());
		?>
        <h3 class="comments-title">
			<?php
			echo esc_html(sprintf(_n('%d Review', '%d Reviews', $book_inn_rating_count, 'book-inn'), $book_inn_rating_count));
			?>
        </h3><!-- .comments-title -->

		<?php if (have_comments()) { ?>

            <ol class="comment-list">
				<?php
				$list_args = apply_filters(
					'mphbr_list_comments_args',
					array(
						'style' => 'ol',
						'short_ping' => true,
						'avatar_size' => 88,
						'callback' => 'book_inn_review_callback'
					)
				);

				wp_list_comments($list_args);

				?>
            </ol><!-- .comment-list -->

			<?php

			$comments_navigation_args = apply_filters(
				'mphbr_comments_navigation_args',
				array(
					'prev_text' => esc_html__('Older reviews', 'book-inn'),
					'next_text' => esc_html__('Newer reviews', 'book-inn'),
				)
			);

			the_comments_navigation($comments_navigation_args);

			?>

			<?php // If comments are closed and there are comments, let's leave a little note, shall we? ?>

		<?php } else if (comments_open()) { ?>
            <p class="no-comments"><?php esc_html_e('There are no reviews yet.', 'book-inn'); ?></p>
		<?php } ?>


        <div class="mphbr-new-review-box <?php echo comments_open() ? '' : esc_attr( 'reviews-closed' ); ?>">
            <div class="mphbr-new-review-box-wrapper">
				<?php

				$comments_args = apply_filters(
					'mphbr_comment_form_args',
					array(
						'class_form' => 'mphbr-review-form comment-form',
						'label_submit' => esc_html__('Post review', 'book-inn'), // Change the title of send button
						'title_reply' => sprintf(esc_html__('Review "%s"', 'book-inn'), get_the_title()), // Change the title of the reply section
						'comment_field' => '<p class="comment-form-comment"><label for="comment">' .
							esc_html__('Your review', 'book-inn') .
							'</label> <textarea id="comment" name="comment" cols="45" rows="4" maxlength="65525" required="required"></textarea></p>'
					)
				);

				comment_form($comments_args);

				?>
            </div>
        </div>

    </div><!-- #comments -->

<?php

do_action('mphbr_reviews_content_after');
