<?php

if (! defined('ABSPATH')) {
	exit;
}

function book_inn_register_booking_image_sizes()
{
	add_image_size('book-inn-large', 920, 650, true);
	add_image_size('book-inn-small', 500, 300, true);
}
add_action('after_setup_theme', 'book_inn_register_booking_image_sizes');

add_filter('mphb_loop_room_type_gallery_main_slider_image_size', 'book_inn_loop_room_type_gallery_image_size');
function book_inn_loop_room_type_gallery_image_size()
{
	return 'book-inn-large';
}

add_filter('mphb_widget_rooms_thumbnail_size', 'book_inn_widget_rooms_thumbnail_size');
function book_inn_widget_rooms_thumbnail_size()
{
	return 'book-inn-large';
}

add_filter('mphb_loop_room_type_gallery_use_nav_slider', '__return_false');

add_filter('shortcode_atts_mphb_rooms', 'book_inn_mphb_rooms_shortcode_atts', 10, 3);
function book_inn_mphb_rooms_shortcode_atts($out, $pairs, $atts)
{
	$hasSliderClass = false;
	if (!empty($atts['class'])) {
		$hasSliderClass = (strpos(' ' . $atts['class'] . ' ', ' slider ') !== false || strpos(' ' . $atts['class'] . ' ', ' slide ') !== false);
	}

	if (is_front_page() || $hasSliderClass) {
		$out['details'] = 'true';
	}

	return $out;
}

add_filter('widget_display_callback', 'book_inn_mphb_widget_force_details', 10, 3);
function book_inn_mphb_widget_force_details($instance, $widget, $args)
{
	if (! is_array($instance) || empty($widget->id_base) || $widget->id_base !== 'mphb_rooms_widget') {
		return $instance;
	}

	$instance['show_details'] = true;

	return $instance;
}

add_filter('mphb_loop_room_type_gallery_main_slider_flexslider_options', 'book_inn_mphb_flexslider_options');
function book_inn_mphb_flexslider_options($options)
{
	$options['prevText'] = '<svg width="18" height="15" viewBox="0 0 18 15" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M0.292893 8.23396C-0.0976311 7.84344 -0.0976311 7.21027 0.292893 6.81975L6.65685
							0.455787C7.04738 0.0652627 7.68054 0.0652627 8.07107 0.455787C8.46159 0.846311 8.46159
							1.47948 8.07107 1.87L2.41421 7.52686L8.07107 13.1837C8.46159 13.5742 8.46159 14.2074
							8.07107 14.5979C7.68054 14.9884 7.04738 14.9884 6.65685 14.5979L0.292893 8.23396ZM1
							6.52686L18 6.52685L18 8.52685L1 8.52686L1 6.52686Z"/></svg>';
	$options['nextText'] = '<svg width="19" height="15" viewBox="0 0 19 15" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M17.7608 8.23396C18.1513 7.84344 18.1513 7.21027 17.7608 6.81975L11.3969
							0.455787C11.0063 0.0652627 10.3732 0.0652627 9.98264 0.455787C9.59212 0.846311 9.59212
							1.47948 9.98264 1.87L15.6395 7.52686L9.98264 13.1837C9.59212 13.5742 9.59212 14.2074
							9.98264 14.5979C10.3732 14.9884 11.0063 14.9884 11.3969 14.5979L17.7608 8.23396ZM17.0537
							6.52686L0.053711 6.52685L0.0537109 8.52685L17.0537 8.52686L17.0537 6.52686Z"/></svg>';

	return $options;
}

remove_action('mphb_render_loop_room_type_after_book_button', array('\\MPHB\\Views\\LoopRoomTypeView', '_renderBookButtonBr'), 10);

add_filter('mphb_pagination_args', 'book_inn_mphb_pagination_args');
function book_inn_mphb_pagination_args($args)
{
	$svg = '<svg width="26" height="16" viewBox="0 0 26 16" xmlns="http://www.w3.org/2000/svg">
			<path d="M25.7071 7.29289C26.0976 7.68342 26.0976 8.31658 25.7071 8.70711L19.3431 15.0711C18.9526
			5.4616 18.3195 15.4616 17.9289 15.0711C17.5384 14.6805 17.5384 14.0474 17.9289 13.6569L23.5858 8L17.9289
			2.34315C17.5384 1.95262 17.5384 1.31946 17.9289 0.928932C18.3195 0.538408 18.9526 0.538408 19.3431
			0.928932L25.7071 7.29289ZM25 9H0V7H25V9Z"/></svg>';

	$new_args = array(
		'mid_size'  => 1,
		'prev_text' => $svg . esc_html__('Previous', 'book-inn'),
		'next_text' => esc_html__('Next', 'book-inn') . $svg,
	);

	return array_merge($args, $new_args);
}

remove_action('mphb_render_single_room_type_metas', array('\\MPHB\\Views\\SingleRoomTypeView', 'renderAttributes'), 20);
remove_action('mphb_render_single_room_type_metas', array('\\MPHB\\Views\\SingleRoomTypeView', 'renderDefaultOrForDatesPrice'), 30);
remove_action('mphb_render_single_room_type_metas', array('\\MPHB\\Views\\SingleRoomTypeView', 'renderReservationForm'), 50);

add_action('book_inn_single_room_type_metas', array('\\MPHB\\Views\\SingleRoomTypeView', 'renderDefaultOrForDatesPrice'), 10);
add_action('book_inn_single_room_type_metas', array('\\MPHB\\Views\\SingleRoomTypeView', 'renderAttributes'), 20);
add_action('book_inn_single_room_type_form', array('\\MPHB\\Views\\SingleRoomTypeView', 'renderReservationForm'), 10);

function book_inn_single_room_type_sidebar()
{
?>
	<div class="room-type-meta room-type-sidebar-block">
		<?php do_action('book_inn_single_room_type_metas'); ?>
	</div>
	<div class="room-type-form room-type-sidebar-block">
		<?php do_action('book_inn_single_room_type_form'); ?>
	</div>
<?php
}

add_filter('mphb_single_room_type_gallery_columns', 'book_inn_mphb_single_room_type_gallery_columns');
function book_inn_mphb_single_room_type_gallery_columns()
{
	return 2;
}

add_filter('mphb_single_room_type_gallery_image_size', 'book_inn_mphb_single_room_type_gallery_image_size');
function book_inn_mphb_single_room_type_gallery_image_size()
{
	return 'book-inn-small';
}

remove_action('mphb_render_single_room_type_before_attributes', array('\\MPHB\\Views\\SingleRoomTypeView', '_renderAttributesTitle'), 10);
remove_action('mphb_render_single_room_type_before_reservation_form', array('\\MPHB\\Views\\SingleRoomTypeView', '_renderReservationFormTitle'), 10);

add_filter('mphbr_reviews_template', 'book_inn_mphbr_reviews_template');
function book_inn_mphbr_reviews_template($path)
{
	return get_stylesheet_directory() . '/mphb-reviews/reviews.php';
}

function book_inn_review_callback($comment, $args, $depth)
{
	$tag = ('div' === $args['style']) ? 'div' : 'li';

	$commenter = wp_get_current_commenter();
	if ($commenter['comment_author_email']) {
		$moderation_note = esc_html__('Your comment is awaiting moderation.', 'book-inn');
	} else {
		$moderation_note = esc_html__('Your comment is awaiting moderation. This is a preview, your comment will be visible after it has been approved.', 'book-inn');
	}
?>
	<<?php echo esc_attr($tag); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class(empty($args['has_children']) ? 'parent' : '', $comment); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<?php
			if (0 != $args['avatar_size']) {
				echo get_avatar($comment, $args['avatar_size']);
			}
			?>
			<div class="review-comment-wrapper">
				<footer class="comment-meta">
					<div class="comment-author vcard">
						<?php printf('<span class="fn">%s</span>', get_comment_author_link($comment)); ?>
					</div>
					<div class="comment-metadata">
						<a href="<?php echo esc_url(get_comment_link($comment, $args)); ?>">
							<time datetime="<?php comment_time('c'); ?>">
								<?php echo esc_html(get_comment_date('', $comment)); ?>
							</time>
						</a>
						<?php edit_comment_link(__('Edit', 'book-inn'), '<span class="edit-link">', '</span>'); ?>
					</div>

					<?php if ('0' == $comment->comment_approved) : ?>
						<em class="comment-awaiting-moderation"><?php echo wp_kses_post($moderation_note); ?></em>
					<?php endif; ?>
				</footer>

				<div class="comment-content">
					<?php comment_text(); ?>
				</div>
			</div>
		</article>
	<?php
}

add_action('mphb_sc_checkout_room_details', 'book_inn_mphb_sc_checkout_room_details_before', 15);
function book_inn_mphb_sc_checkout_room_details_before()
{
	?>
		<div class="guest-chooser-wrapper">
		<?php
	}

	add_action('mphb_sc_checkout_room_details', 'book_inn_mphb_sc_checkout_room_details_after', 25);
	function book_inn_mphb_sc_checkout_room_details_after()
	{
		?>
		</div>
	<?php
	}

	add_action('mphb_sc_rooms_before_loop', 'book_inn_mphb_sc_rooms_before_loop');
	function book_inn_mphb_sc_rooms_before_loop()
	{
	?>
		<div class="rooms-wrapper">
		<?php
	}

	add_filter('mphb_sc_rooms_wrapper_class', 'book_inn_mphb_sc_rooms_wrapper_class');
	function book_inn_mphb_sc_rooms_wrapper_class($class)
	{
		// Track slider shortcodes so we can append a "Show all" card inside the wrapper.
		global $book_inn_mphb_rooms_has_slider_wrapper;
		$book_inn_mphb_rooms_has_slider_wrapper = (strpos(' ' . $class . ' ', ' slider ') !== false);
		return $class;
	}

	add_filter('mphb_widget_rooms_wrapper-class', 'book_inn_mphb_widget_rooms_wrapper_class');
	function book_inn_mphb_widget_rooms_wrapper_class($class)
	{
		// Track widget sliders so we can append a "Show all" card inside the wrapper.
		global $book_inn_mphb_widget_has_slider_wrapper;
		$book_inn_mphb_widget_has_slider_wrapper = (strpos(' ' . $class . ' ', ' slider ') !== false);
		return $class;
	}

	add_action('mphb_sc_rooms_after_loop', 'book_inn_mphb_sc_rooms_show_all_card', 5);
	function book_inn_mphb_sc_rooms_show_all_card($roomTypesQuery)
	{
		if (! ($roomTypesQuery instanceof WP_Query)) {
			return;
		}

		global $book_inn_mphb_rooms_has_slider_wrapper;
		if (empty($book_inn_mphb_rooms_has_slider_wrapper) && ! is_front_page()) {
			return;
		}

		$showAllUrl = home_url('/accommodations/');
		?>
			<div class="mphb-room-type book-inn-room-card book-inn-show-all-card">
				<div class="mphb-room-type-content-wrapper">
					<div class="mphb-room-type-content">
						<div class="mphb-room-type-description">
							<div class="mphb-room-type-title">Show all accommodations</div>
							<div class="mphb-room-type-excerpt">Browse the full collection.</div>
						</div>
						<div class="mphb-room-type-buttons">
							<div class="mphb-view-details-button-wrapper">
								<a class="mphb-view-details-button" href="<?php echo esc_url($showAllUrl); ?>">Show all -&gt;</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php

		$book_inn_mphb_rooms_has_slider_wrapper = false;
	}

	add_action('mphb_widget_rooms_after_loop', 'book_inn_mphb_widget_rooms_show_all_card', 5);
	function book_inn_mphb_widget_rooms_show_all_card()
	{
		global $book_inn_mphb_widget_has_slider_wrapper;
		if (empty($book_inn_mphb_widget_has_slider_wrapper) && ! is_front_page()) {
			return;
		}

		$showAllUrl = home_url('/accommodations/');
		?>
			<div class="mphb-room-type book-inn-room-card book-inn-show-all-card">
				<div class="mphb-room-type-content-wrapper">
					<div class="mphb-room-type-content">
						<div class="mphb-room-type-description">
							<div class="mphb-room-type-title">Show all accommodations</div>
							<div class="mphb-room-type-excerpt">Browse the full collection.</div>
						</div>
						<div class="mphb-room-type-buttons">
							<div class="mphb-view-details-button-wrapper">
								<a class="mphb-view-details-button" href="<?php echo esc_url($showAllUrl); ?>">Show all -&gt;</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php

		$book_inn_mphb_widget_has_slider_wrapper = false;
	}

	add_action('mphb_sc_rooms_after_loop', 'book_inn_mphb_sc_rooms_after_loop');
	function book_inn_mphb_sc_rooms_after_loop()
	{
		?>
		</div>
	<?php
	}

	add_filter('body_class', 'book_inn_mphb_search_results_body_class');
	function book_inn_mphb_search_results_body_class($classes)
	{
		if (function_exists('mphb_is_search_results_page') && mphb_is_search_results_page()) {
			$classes[] = 'book-inn-search-loading';
		}

		return $classes;
	}

	add_action('pre_get_posts', 'book_inn_mphb_search_results_limit');
	function book_inn_mphb_search_results_limit($query)
	{
		if (is_admin() || ! ($query instanceof WP_Query)) {
			return;
		}

		if (! function_exists('mphb_is_search_results_page') || ! mphb_is_search_results_page()) {
			return;
		}

		if (! function_exists('MPHB')) {
			return;
		}

		if ($query->get('post_type') !== MPHB()->postTypes()->roomType()->getPostType()) {
			return;
		}

		if (empty($query->get('post__in'))) {
			return;
		}

		if ((int) $query->get('posts_per_page') !== -1) {
			return;
		}

		$per_page = (int) apply_filters('book_inn_mphb_search_results_per_page', 12);
		if ($per_page <= 0) {
			return;
		}

		$paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
		$query->set('posts_per_page', $per_page);
		$query->set('paged', $paged);
	}

	add_action('mphb_sc_search_results_before_loop', 'book_inn_mphb_sc_search_results_info', 5);
	function book_inn_mphb_sc_search_results_info($roomTypesQuery)
	{
		if (! ($roomTypesQuery instanceof WP_Query)) {
			return;
		}

		$roomTypesCount = (int) $roomTypesQuery->found_posts;
		$checkInDate = '';
		$checkOutDate = '';

		if (function_exists('MPHB') && MPHB()->searchParametersStorage()->hasStored()) {
			$stored = MPHB()->searchParametersStorage()->get();
			$format = MPHB()->settings()->dateTime()->getDateTransferFormat();
			if (! empty($stored['mphb_check_in_date'])) {
				$dateObj = \MPHB\Utils\DateUtils::createCheckInDate($format, $stored['mphb_check_in_date']);
				if ($dateObj) {
					$checkInDate = \MPHB\Utils\DateUtils::formatDateWPFront($dateObj);
				}
			}
			if (! empty($stored['mphb_check_out_date'])) {
				$dateObj = \MPHB\Utils\DateUtils::createCheckOutDate($format, $stored['mphb_check_out_date']);
				if ($dateObj) {
					$checkOutDate = \MPHB\Utils\DateUtils::formatDateWPFront($dateObj);
				}
			}
		}
	?>
		<p class="book-inn-search-results-info">
			<?php
			if ($roomTypesCount > 0) {
				echo esc_html(sprintf(_n('%s accommodation found', '%s accommodations found', $roomTypesCount, 'motopress-hotel-booking'), $roomTypesCount));
			} else {
				esc_html_e('No accommodations found', 'motopress-hotel-booking');
			}
			if ($checkInDate && $checkOutDate) {
				echo esc_html(sprintf(__(' from %s - till %s', 'motopress-hotel-booking'), $checkInDate, $checkOutDate));
			}
			?>
		</p>
	<?php
	}

	add_action('mphb_sc_search_results_before_loop', 'book_inn_mphb_sc_search_results_before_loop', 20);
	function book_inn_mphb_sc_search_results_before_loop()
	{
	?>
		<div class="rooms-wrapper">
		<?php
	}

	add_action('mphb_sc_search_results_after_loop', 'book_inn_mphb_sc_search_results_after_loop', 5);
	function book_inn_mphb_sc_search_results_after_loop()
	{
		?>
		</div>
	<?php
	}

	add_action('mphb_sc_search_results_after_loop', 'book_inn_mphb_sc_search_results_pagination', 20);
	function book_inn_mphb_sc_search_results_pagination($roomTypesQuery)
	{
		if (! ($roomTypesQuery instanceof WP_Query)) {
			return;
		}

		if ($roomTypesQuery->max_num_pages < 2) {
			return;
		}

		$paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
		$args = array(
			'total'     => (int) $roomTypesQuery->max_num_pages,
			'current'   => $paged,
			'mid_size'  => 1,
			'prev_text' => esc_html__('Previous', 'book-inn'),
			'next_text' => esc_html__('Next', 'book-inn'),
			'type'      => 'list',
		);
		$args = apply_filters('mphb_pagination_args', $args);
		$links = paginate_links($args);
		if (empty($links)) {
			return;
		}
	?>
		<nav class="mphb-pagination" aria-label="<?php echo esc_attr__('Search results pages', 'book-inn'); ?>">
			<?php echo wp_kses_post($links); ?>
		</nav>
	<?php
	}

	add_filter('mphbr_list_comments_args', 'book_inn_mphbr_list_comments_args');
	function book_inn_mphbr_list_comments_args($args)
	{
		$args['callback'] = 'book_inn_review_callback';
		return $args;
	}

	/**
	 * Prevent third-party template hijacks (e.g. MPHB Styles Canvas) from stripping
	 * the theme shell on accommodation singles.
	 */
	add_filter('single_template', 'book_inn_force_room_type_single_template', 100);
	function book_inn_force_room_type_single_template($template)
	{
		if (! is_singular('mphb_room_type')) {
			return $template;
		}

		$theme_template = locate_template('single-mphb_room_type.php');
		if (! empty($theme_template)) {
			return $theme_template;
		}

		return $template;
	}

	function book_inn_get_mphb_system_page_role($page_id)
	{
		$page_id = (int) $page_id;
		if ($page_id <= 0) {
			return '';
		}

		$option_map = array(
			'mphb_search_results_page'       => 'search_results',
			'mphb_checkout_page'             => 'checkout',
			'mphb_booking_confirmation_page' => 'confirmation',
			'mphb_payment_success_page'      => 'payment_success',
			'mphb_payment_failed_page'       => 'payment_failed',
			'mphb_booking_cancellation_page' => 'cancellation',
			'mphb_my_account_page'           => 'account',
		);

		foreach ($option_map as $option_name => $role) {
			if ((int) get_option($option_name) === $page_id) {
				return $role;
			}
		}

		return '';
	}

	function book_inn_get_booking_hero_subtitle($post_id)
	{
		$role = book_inn_get_mphb_system_page_role($post_id);
		$map  = array(
			'search_results' => __('Find your perfect stay', 'book-inn'),
			'checkout'       => __('Secure your reservation', 'book-inn'),
			'confirmation'   => __('Booking confirmation', 'book-inn'),
			'payment_success' => __('Payment successful', 'book-inn'),
			'payment_failed' => __('Payment update required', 'book-inn'),
			'cancellation'   => __('Booking update', 'book-inn'),
			'account'        => __('Manage your bookings', 'book-inn'),
		);

		return isset($map[$role]) ? $map[$role] : '';
	}

	function book_inn_render_booking_hero($post_id = 0, $args = array())
	{
		$post_id = $post_id ? (int) $post_id : get_the_ID();
		if ($post_id <= 0) {
			return;
		}

		$defaults = array(
			'title'    => get_the_title($post_id),
			'subtitle' => '',
		);
		$args = wp_parse_args($args, $defaults);

		$title = isset($args['title']) ? trim((string) $args['title']) : '';
		if ('' === $title) {
			$title = get_the_title($post_id);
		}
		$subtitle = isset($args['subtitle']) ? trim((string) $args['subtitle']) : '';

		$image_url = get_the_post_thumbnail_url($post_id, 'full');
		$has_image = ! empty($image_url);
	?>
		<section class="book-inn-booking-hero<?php echo $has_image ? '' : ' is-no-image'; ?>">
			<?php if ($has_image) : ?>
				<div class="book-inn-booking-hero__media">
					<img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" loading="eager" decoding="async" />
				</div>
			<?php endif; ?>
			<div class="book-inn-booking-hero__overlay"></div>
			<div class="book-inn-booking-hero__content">
				<?php if ('' !== $subtitle) : ?>
					<p class="book-inn-booking-hero__subtitle"><?php echo esc_html($subtitle); ?></p>
				<?php endif; ?>
				<h1 class="book-inn-booking-hero__title"><?php echo esc_html($title); ?></h1>
			</div>
		</section>
	<?php
	}

	add_filter('the_content', 'book_inn_prepend_booking_system_page_hero', 8);
	function book_inn_prepend_booking_system_page_hero($content)
	{
		if (is_admin() || ! is_singular('page') || ! in_the_loop() || ! is_main_query()) {
			return $content;
		}

		if (! function_exists('book_inn_get_mphb_system_page_ids')) {
			return $content;
		}

		$page_id = get_the_ID();
		if (! $page_id || ! in_array((int) $page_id, book_inn_get_mphb_system_page_ids(), true)) {
			return $content;
		}

		ob_start();
		book_inn_render_booking_hero(
			$page_id,
			array(
				'subtitle' => book_inn_get_booking_hero_subtitle($page_id),
			)
		);

		return ob_get_clean() . $content;
	}
