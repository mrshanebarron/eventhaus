<?php
/**
 * Custom Post Types & Taxonomies
 */

defined('ABSPATH') || exit;

add_action('init', function () {

    // ─── Rental Item CPT ───────────────────────────────────────
    register_post_type('rental_item', [
        'labels' => [
            'name'               => __('Rental Items', 'eventhaus'),
            'singular_name'      => __('Rental Item', 'eventhaus'),
            'add_new_item'       => __('Add New Item', 'eventhaus'),
            'edit_item'          => __('Edit Item', 'eventhaus'),
            'search_items'       => __('Search Items', 'eventhaus'),
            'not_found'          => __('No items found', 'eventhaus'),
        ],
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => ['slug' => 'catalogue', 'with_front' => false],
        'menu_icon'          => 'dashicons-archive',
        'supports'           => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest'       => true,
        'publicly_queryable' => true,
    ]);

    // ─── Rental Category Taxonomy ──────────────────────────────
    register_taxonomy('rental_category', 'rental_item', [
        'labels' => [
            'name'          => __('Categories', 'eventhaus'),
            'singular_name' => __('Category', 'eventhaus'),
            'add_new_item'  => __('Add New Category', 'eventhaus'),
        ],
        'public'       => true,
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'catalogue/category', 'with_front' => false],
        'show_in_rest' => true,
    ]);

    // ─── Quote Request CPT (admin only) ────────────────────────
    register_post_type('quote_request', [
        'labels' => [
            'name'          => __('Quote Requests', 'eventhaus'),
            'singular_name' => __('Quote Request', 'eventhaus'),
            'search_items'  => __('Search Requests', 'eventhaus'),
            'not_found'     => __('No requests found', 'eventhaus'),
        ],
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_icon'           => 'dashicons-clipboard',
        'supports'            => ['title'],
        'capability_type'     => 'post',
        'capabilities'        => [
            'create_posts' => 'do_not_allow',
        ],
        'map_meta_cap'        => true,
    ]);
});
