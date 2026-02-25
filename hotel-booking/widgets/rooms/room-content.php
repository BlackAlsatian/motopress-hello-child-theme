<?php

/**
 * Available varialbes
 * - bool $isShowTitle
 * - bool $isShowImage
 * - bool $isShowExcerpt
 * - bool $isShowDetails
 * - bool $isShowPrice
 * - bool $isShowBookButton
 * - string $price
 * - WP_Term[] $categories
 * - WP_Term[] $facilities
 * - array $attributes [%Attribute name% => [%ID% => %Term title%]]
 * - string $view
 * - string $size
 * - string $sizeNumber (since 3.6.1)
 * - string $bedType
 * - string $adults
 * - string $children
 * - int $totalCapacity (since 3.7.2)
 *
 * @version 2.0.0
 */
if (! defined('ABSPATH')) {
    exit;
}
if (post_password_required()) {
    $isShowImage = $isShowDetails = $isShowPrice = $isShowBookButton = false;
}
$wrapperClass = apply_filters('mphb_widget_rooms_item_class', join(' ', mphb_tmpl_get_filtered_post_class('mphb-room-type')));

if (!function_exists('book_inn_mphb_widget_attribute_match')) {
    function book_inn_mphb_widget_attribute_match($attributes, $keywords)
    {
        if (empty($attributes) || empty($keywords)) {
            return null;
        }

        foreach ($attributes as $attributeName => $terms) {
            $label = mphb_attribute_title($attributeName);
            $haystack = strtolower($attributeName . ' ' . $label);
            foreach ($keywords as $keyword) {
                if (strpos($haystack, $keyword) !== false) {
                    $values = array_values($terms);
                    return array(
                        'label' => $label,
                        'value' => join(', ', $values),
                    );
                }
            }
        }

        return null;
    }
}
?>
<div class="<?php echo esc_attr($wrapperClass); ?>">

    <?php do_action('mphb_widget_rooms_item_top'); ?>

    <?php if ($isShowImage && has_post_thumbnail()) : ?>
        <div class="mphb-widget-room-type-featured-image">
            <a href="<?php esc_url(the_permalink()); ?>">
                <?php
                the_post_thumbnail(
                    apply_filters('mphb_widget_rooms_thumbnail_size', 'post-thumbnail')
                );
                ?>
            </a>
        </div>
    <?php endif; ?>

    <?php if ($isShowTitle) : ?>
        <div class="mphb-widget-room-type-title">
            <a href="<?php esc_url(the_permalink()); ?>">
                <?php the_title(); ?>
            </a>
        </div>
    <?php endif; ?>

    <?php if ($isShowExcerpt && has_excerpt()) : ?>
        <div class="mphb-widget-room-type-description">
            <?php the_excerpt(); ?>
        </div>
    <?php endif; ?>

    <?php if ($isShowDetails) : ?>
        <?php
        $detailItems = array();

        if (! empty($sizeNumber)) {
            $detailItems[] = array(
                'class' => 'mphb-room-type-size',
                'label_class' => 'mphb-size-title',
                'label' => __('Size:', 'motopress-hotel-booking'),
                'value' => $size,
            );
        } else {
            $sizeFallback = book_inn_mphb_widget_attribute_match($attributes, array('size', 'area', 'sqm', 'sq', 'm2'));
            if (! empty($sizeFallback)) {
                $detailItems[] = array(
                    'class' => 'mphb-room-type-size',
                    'label_class' => 'mphb-size-title',
                    'label' => $sizeFallback['label'] . ':',
                    'value' => $sizeFallback['value'],
                );
            }
        }

        if (! empty($totalCapacity)) {
            $detailItems[] = array(
                'class' => 'mphb-room-type-total-capacity',
                'label_class' => 'mphb-total-capacity-title',
                'label' => __('Guests:', 'motopress-hotel-booking'),
                'value' => $totalCapacity,
            );
        } else {
            $guestsFallback = book_inn_mphb_widget_attribute_match($attributes, array('guest', 'capacity', 'people', 'sleeps'));
            if (! empty($guestsFallback)) {
                $detailItems[] = array(
                    'class' => 'mphb-room-type-total-capacity',
                    'label_class' => 'mphb-total-capacity-title',
                    'label' => __('Guests:', 'motopress-hotel-booking'),
                    'value' => $guestsFallback['value'],
                );
            }
        }

        if (! empty($bedType)) {
            $detailItems[] = array(
                'class' => 'mphb-room-type-bed-type',
                'label_class' => 'mphb-bed-type-title',
                'label' => __('Bed Type:', 'motopress-hotel-booking'),
                'value' => $bedType,
            );
        } else {
            $bedFallback = book_inn_mphb_widget_attribute_match($attributes, array('bed'));
            if (! empty($bedFallback)) {
                $detailItems[] = array(
                    'class' => 'mphb-room-type-bed-type',
                    'label_class' => 'mphb-bed-type-title',
                    'label' => $bedFallback['label'] . ':',
                    'value' => $bedFallback['value'],
                );
            }
        }
        ?>
        <?php if (! empty($detailItems)) : ?>
            <ul class="mphb-widget-room-type-attributes">
                <?php foreach ($detailItems as $item) : ?>
                    <li class="<?php echo esc_attr($item['class']); ?>">
                        <span class="mphb-attribute-title <?php echo esc_attr($item['label_class']); ?>"><?php echo esc_html($item['label']); ?></span>
                        <span class="mphb-attribute-value">
                            <?php echo esc_html($item['value']); ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($isShowPrice && mphb_tmpl_has_room_type_default_price()) : ?>
        <div class="mphb-widget-room-type-price">
            <span><?php esc_html_e('Prices start at:', 'motopress-hotel-booking'); ?></span>
            <?php mphb_tmpl_the_room_type_default_price(); ?>
        </div>
    <?php endif; ?>

    <?php if ($isShowBookButton) : ?>
        <div class="mphb-widget-room-type-book-button">
            <?php mphb_tmpl_the_loop_room_type_book_button_form(); ?>
        </div>
    <?php endif; ?>

    <?php do_action('mphb_widget_rooms_item_bottom'); ?>

</div>