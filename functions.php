<?php

/**
 * Theme functions and definitions.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

define('HELLO_ELEMENTOR_CHILD_VERSION', '2.0.31');

// Toggle Elementor editing for MotoPress Hotel Booking system pages/CPTs.
// Define in wp-config.php to override per environment.
if (! defined('BOOK_INN_ALLOW_ELEMENTOR_ON_MPHB_PAGES')) {
	define('BOOK_INN_ALLOW_ELEMENTOR_ON_MPHB_PAGES', false);
}

function book_inn_allow_elementor_on_mphb_pages_toggle($allow)
{
	return (bool) BOOK_INN_ALLOW_ELEMENTOR_ON_MPHB_PAGES;
}
add_filter('book_inn_allow_elementor_on_mphb_pages', 'book_inn_allow_elementor_on_mphb_pages_toggle', 10, 1);

require_once get_stylesheet_directory() . '/includes/mphb-booking.php';

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function hello_elementor_child_scripts_styles()
{

	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		HELLO_ELEMENTOR_CHILD_VERSION
	);
}
add_action('wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20);

/**
 * Prevent accidental Elementor edits on Hotel Booking system pages and CPTs.
 * Theme Builder templates can still be used safely.
 */
function book_inn_get_mphb_system_page_ids()
{
	static $page_ids = null;

	if (null !== $page_ids) {
		return $page_ids;
	}

	$option_names = [
		'mphb_search_results_page',
		'mphb_checkout_page',
		'mphb_terms_and_conditions_page',
		'mphb_booking_confirmation_page',
		'mphb_user_cancel_redirect_page',
		'mphb_payment_success_page',
		'mphb_payment_failed_page',
		'mphb_booking_cancellation_page',
		'mphb_my_account_page',
	];

	$page_ids = [];

	foreach ($option_names as $option_name) {
		$page_id = (int) get_option($option_name);
		if ($page_id > 0) {
			$page_ids[] = $page_id;
		}
	}

	$page_ids = array_values(array_unique($page_ids));

	return $page_ids;
}


/**
 * Avoid a redirect loop on the homepage when canonical URLs differ only by a slash.
 */
function book_inn_disable_home_canonical_redirect($redirect_url, $requested_url)
{
	if (is_front_page() || is_home()) {
		return false;
	}

	return $redirect_url;
}
add_filter('redirect_canonical', 'book_inn_disable_home_canonical_redirect', 10, 2);

function book_inn_post_navigation($class = '')
{
?>
	<div class="post-navigation-wrapper <?php echo esc_attr($class); ?>">
		<?php
		the_post_navigation(
			array(
				'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous', 'book-inn') . '</span>
					<div class="title-wrapper">
					<span class="nav-title">%title</span>
					<svg width="26" height="16" viewBox="0 0 26 16" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M0.292893 8.67732C-0.0976311 8.2868 -0.0976311 7.65363 0.292893 7.26311L6.65685 0.899147C7.04738 
						0.508623 7.68054 0.508623 8.07107 0.899147C8.46159 1.28967 8.46159 1.92284 8.07107 2.31336L2.41421 
						7.97021L8.07107 13.6271C8.46159 14.0176 8.46159 14.6508 8.07107 15.0413C7.68054 15.4318 7.04738 
						15.4318 6.65685 15.0413L0.292893 8.67732ZM1 6.97021L26 6.97021V8.97021L1 8.97021L1 6.97021Z"/>
					</svg></div>',
				'next_text' => '<span class="nav-subtitle">' . esc_html__('Next', 'book-inn') . '</span>
					<div class="title-wrapper">
					<span class="nav-title">%title</span>
					<svg width="26" height="16" viewBox="0 0 26 16" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M25.7071 7.26311C26.0976 7.65363 26.0976 8.2868 25.7071 8.67732L19.3431 15.0413C18.9526 
						15.4318 18.3195 15.4318 17.9289 15.0413C17.5384 14.6508 17.5384 14.0176 17.9289 13.6271L23.5858 
						7.97021L17.9289 2.31336C17.5384 1.92284 17.5384 1.28967 17.9289 0.899148C18.3195 0.508623 18.9526 
						0.508623 19.3431 0.899148L25.7071 7.26311ZM25 8.97021L8.74228e-08 8.97022L-8.74228e-08 6.97022L25 
						6.97021L25 8.97021Z"/>
					</svg></div>',
			)
		);
		?>
	</div>
<?php
}

/**
 * Frontend assets for MotoPress Hotel Booking layouts.
 */
function book_inn_is_mphb_context()
{
	if (! function_exists('MPHB')) {
		return false;
	}

	if (function_exists('mphb_is_single_room_type_page') && mphb_is_single_room_type_page()) {
		return true;
	}

	if (function_exists('mphb_is_search_results_page') && mphb_is_search_results_page()) {
		return true;
	}

	if (function_exists('mphb_is_checkout_page') && mphb_is_checkout_page()) {
		return true;
	}

	if (is_singular('mphb_room_type')) {
		return true;
	}

	$post = get_post();
	if (! $post) {
		return false;
	}

	$shortcodes = array(
		'mphb_rooms',
		'mphb_room',
		'mphb_search_results',
		'mphb_availability',
		'mphb_checkout',
		'mphb_booking_confirmation',
		'mphb_rates',
		'mphb_services',
		'mphb_search',
		'mphb_accommodation_reviews',
	);

	foreach ($shortcodes as $shortcode) {
		if (has_shortcode($post->post_content, $shortcode)) {
			return true;
		}
	}

	if (false !== strpos($post->post_content, 'motopress-hotel-booking/')) {
		return true;
	}

	$elementor_data = get_post_meta($post->ID, '_elementor_data', true);
	if (is_string($elementor_data)) {
		$mphb_elementor_markers = array(
			'mphb_',
			'mphbe-',
			'"widgetType":"mphbe-',
			'"widgetType":"mphb-',
		);

		foreach ($mphb_elementor_markers as $marker) {
			if (false !== strpos($elementor_data, $marker)) {
				return true;
			}
		}
	}

	return false;
}

function book_inn_enqueue_mphb_assets()
{
	if (! book_inn_is_mphb_context()) {
		return;
	}

	wp_enqueue_style('book-inn-fontawesome', 'https://use.fontawesome.com/releases/v5.10.0/css/all.css', array(), '5.10.0');
	wp_enqueue_style('book-inn-slick', get_stylesheet_directory_uri() . '/assets/vendor/slick/slick.css', array(), '1.8.1');
	$mphb_css_path = get_stylesheet_directory() . '/assets/css/mphb-booking.css';
	$mphb_css_ver = file_exists($mphb_css_path) ? filemtime($mphb_css_path) : HELLO_ELEMENTOR_CHILD_VERSION;
	wp_enqueue_style('book-inn-mphb', get_stylesheet_directory_uri() . '/assets/css/mphb-booking.css', array('book-inn-slick'), $mphb_css_ver);

	wp_enqueue_script('book-inn-slick', get_stylesheet_directory_uri() . '/assets/vendor/slick/slick.js', array('jquery'), '1.8.1', true);
	$mphb_js_path = get_stylesheet_directory() . '/assets/js/mphb-booking.js';
	$mphb_js_ver = file_exists($mphb_js_path) ? filemtime($mphb_js_path) : HELLO_ELEMENTOR_CHILD_VERSION;
	wp_enqueue_script('book-inn-mphb', get_stylesheet_directory_uri() . '/assets/js/mphb-booking.js', array('jquery', 'book-inn-slick'), $mphb_js_ver, true);
}
add_action('wp_enqueue_scripts', 'book_inn_enqueue_mphb_assets', 30);
