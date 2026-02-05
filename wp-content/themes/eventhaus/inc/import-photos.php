<?php
/**
 * Import Unsplash photos as featured images for rental items.
 * Run via WP-CLI: wp eval-file wp-content/themes/eventhaus/inc/import-photos.php
 */

if (!defined('ABSPATH')) {
    // If run from WP-CLI, ABSPATH is defined
    echo "Must be run via WP-CLI\n";
    exit(1);
}

require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

$photos = [
    'Chiavari Gold Chair' => 'https://images.unsplash.com/photo-1723832348140-a2d9eb1753b1?w=1200&q=80',
    'Ghost Chair — Crystal' => 'https://images.unsplash.com/photo-1633937356638-833796fa3215?w=1200&q=80',
    'Velvet Lounge Armchair' => 'https://images.unsplash.com/photo-1571977796766-578d484a6c25?w=1200&q=80',
    'Cross-Back Vineyard Chair' => 'https://images.unsplash.com/photo-1648784755336-f6b2f6cb1024?w=1200&q=80',
    'Marble Banquet Table — 240cm' => 'https://plus.unsplash.com/premium_photo-1673972358996-3747c0ba31b6?w=1200&q=80',
    'Brass Cocktail Table — Round' => 'https://images.unsplash.com/photo-1661385643838-d616a1ec8805?w=1200&q=80',
    'Farmhouse Trestle Table — 300cm' => 'https://images.unsplash.com/photo-1664790560217-f9d1b375b7eb?w=1200&q=80',
    'Crystal Cascade Chandelier' => 'https://plus.unsplash.com/premium_photo-1761033811768-ca65b391de85?w=1200&q=80',
    'Edison Bulb Canopy — 50m²' => 'https://images.unsplash.com/photo-1681841703443-53de247ce32b?w=1200&q=80',
    'Wireless LED Uplighters — Set of 8' => 'https://plus.unsplash.com/premium_photo-1739199644949-e65901cfbd3a?w=1200&q=80',
    'Modular Lounge Set — Midnight' => 'https://plus.unsplash.com/premium_photo-1740413439525-2c595afcf240?w=1200&q=80',
    'Rattan Daybed — Riviera' => 'https://images.unsplash.com/photo-1657447512847-67f28a297ef9?w=1200&q=80',
    'Brass Arch — 280cm' => 'https://images.unsplash.com/photo-1747115275519-e9b20470ac8e?w=1200&q=80',
    'Silk Table Runner Collection' => 'https://plus.unsplash.com/premium_photo-1729162588392-bb95433cbb01?w=1200&q=80',
    'Modular Stage Platform — Black' => 'https://plus.unsplash.com/premium_photo-1714618976010-a5b5c6dc1d32?w=1200&q=80',
    'Greenery Wall — 3m × 2.4m' => 'https://plus.unsplash.com/premium_photo-1675623968528-464c2c49cafb?w=1200&q=80',
];

$imported = 0;
$skipped = 0;
$errors = 0;

foreach ($photos as $title => $url) {
    $post = get_page_by_title($title, OBJECT, 'rental_item');

    if (!$post) {
        // Try WP_Query as fallback
        $query = new WP_Query([
            'post_type' => 'rental_item',
            'title' => $title,
            'posts_per_page' => 1,
        ]);
        if ($query->have_posts()) {
            $post = $query->posts[0];
        }
    }

    if (!$post) {
        echo "  SKIP: No post found for '{$title}'\n";
        $skipped++;
        continue;
    }

    // Skip if already has a featured image
    if (has_post_thumbnail($post->ID)) {
        echo "  SKIP: '{$title}' already has a featured image\n";
        $skipped++;
        continue;
    }

    echo "  Downloading image for '{$title}'...\n";

    // Download the image to a temp file
    $tmp = download_url($url, 30);
    if (is_wp_error($tmp)) {
        echo "  ERROR: Download failed for '{$title}': " . $tmp->get_error_message() . "\n";
        $errors++;
        continue;
    }

    // Prepare file array for media_handle_sideload
    $file_array = [
        'name'     => sanitize_file_name($title) . '.jpg',
        'tmp_name' => $tmp,
    ];

    // Sideload the image into the media library
    $attachment_id = media_handle_sideload($file_array, $post->ID, $title);

    if (is_wp_error($attachment_id)) {
        echo "  ERROR: Sideload failed for '{$title}': " . $attachment_id->get_error_message() . "\n";
        @unlink($tmp);
        $errors++;
        continue;
    }

    // Set as featured image
    set_post_thumbnail($post->ID, $attachment_id);
    echo "  OK: Set featured image for '{$title}' (attachment #{$attachment_id})\n";
    $imported++;
}

echo "\nDone! Imported: {$imported}, Skipped: {$skipped}, Errors: {$errors}\n";
