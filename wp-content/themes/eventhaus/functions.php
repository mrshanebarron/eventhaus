<?php
/**
 * EventHaus Theme Functions
 * Premium event furniture rental catalogue
 */

defined('ABSPATH') || exit;

define('EVENTHAUS_VERSION', '1.0.0');
define('EVENTHAUS_DIR', get_template_directory());
define('EVENTHAUS_URI', get_template_directory_uri());

// ─── Theme Setup ───────────────────────────────────────────────
add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);

    set_post_thumbnail_size(800, 600, true);
    add_image_size('catalogue-card', 600, 450, true);
    add_image_size('catalogue-hero', 1600, 900, true);
    add_image_size('gallery-thumb', 400, 400, true);

    register_nav_menus([
        'primary' => __('Primary Navigation', 'eventhaus'),
    ]);
});

// ─── Enqueue Assets ────────────────────────────────────────────
add_action('wp_enqueue_scripts', function () {
    // Fonts
    wp_enqueue_style('eventhaus-fonts', 'https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Inter:wght@300;400;500;600&display=swap', [], null);

    // Main stylesheet
    wp_enqueue_style('eventhaus-style', EVENTHAUS_URI . '/assets/css/main.css', [], EVENTHAUS_VERSION);

    // GSAP
    wp_enqueue_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js', [], '3.12.5', true);
    wp_enqueue_script('gsap-scroll', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js', ['gsap'], '3.12.5', true);

    // Alpine.js
    wp_enqueue_script('alpinejs', 'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js', [], '3.0', true);
    // Alpine must be deferred
    add_filter('script_loader_tag', function ($tag, $handle) {
        if ($handle === 'alpinejs') {
            return str_replace(' src', ' defer src', $tag);
        }
        return $tag;
    }, 10, 2);

    // Theme JS (loaded before Alpine defer, so component functions are defined)
    wp_enqueue_script('eventhaus-app', EVENTHAUS_URI . '/assets/js/app.js', ['gsap', 'gsap-scroll'], EVENTHAUS_VERSION, true);
    wp_localize_script('eventhaus-app', 'eventhaus', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('eventhaus_nonce'),
    ]);
});

// ─── Include Modules ───────────────────────────────────────────
require_once EVENTHAUS_DIR . '/inc/post-types.php';
require_once EVENTHAUS_DIR . '/inc/meta-boxes.php';
require_once EVENTHAUS_DIR . '/inc/quote-handler.php';
require_once EVENTHAUS_DIR . '/inc/seed-data.php';

// ─── Admin Columns for Rental Items ───────────────────────────
add_filter('manage_rental_item_posts_columns', function ($columns) {
    $new = [];
    foreach ($columns as $key => $val) {
        $new[$key] = $val;
        if ($key === 'title') {
            $new['rental_category'] = __('Category', 'eventhaus');
            $new['rental_image'] = __('Image', 'eventhaus');
        }
    }
    return $new;
});

add_action('manage_rental_item_posts_custom_column', function ($column, $post_id) {
    if ($column === 'rental_category') {
        $terms = get_the_terms($post_id, 'rental_category');
        echo $terms ? implode(', ', wp_list_pluck($terms, 'name')) : '—';
    }
    if ($column === 'rental_image') {
        $thumb = get_the_post_thumbnail($post_id, [60, 60]);
        echo $thumb ?: '—';
    }
}, 10, 2);

// ─── Admin Columns for Quote Requests ─────────────────────────
add_filter('manage_quote_request_posts_columns', function ($columns) {
    return [
        'cb'          => $columns['cb'],
        'title'       => __('Request', 'eventhaus'),
        'client_name' => __('Client', 'eventhaus'),
        'client_email'=> __('Email', 'eventhaus'),
        'event_date'  => __('Event Date', 'eventhaus'),
        'item_count'  => __('Items', 'eventhaus'),
        'date'        => __('Submitted', 'eventhaus'),
    ];
});

add_action('manage_quote_request_posts_custom_column', function ($column, $post_id) {
    $meta = get_post_meta($post_id);
    switch ($column) {
        case 'client_name':
            echo esc_html($meta['_client_name'][0] ?? '—');
            break;
        case 'client_email':
            echo esc_html($meta['_client_email'][0] ?? '—');
            break;
        case 'event_date':
            echo esc_html($meta['_event_date'][0] ?? '—');
            break;
        case 'item_count':
            $items = json_decode($meta['_selected_items'][0] ?? '[]', true);
            echo count($items);
            break;
    }
}, 10, 2);

// ─── Disable Gutenberg for our CPTs ───────────────────────────
add_filter('use_block_editor_for_post_type', function ($use, $post_type) {
    if (in_array($post_type, ['rental_item', 'quote_request'])) {
        return false;
    }
    return $use;
}, 10, 2);

// ─── Pretty Permalinks Flush ──────────────────────────────────
add_action('after_switch_theme', function () {
    flush_rewrite_rules();
});
