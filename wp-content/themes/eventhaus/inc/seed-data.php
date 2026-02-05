<?php
/**
 * Seed Data — populates demo catalogue on theme activation
 */

defined('ABSPATH') || exit;

add_action('after_switch_theme', 'eventhaus_seed_data');

function eventhaus_seed_data() {
    // Don't run if we already have items
    $existing = get_posts(['post_type' => 'rental_item', 'numberposts' => 1]);
    if (!empty($existing)) return;

    // Flush rewrite rules for CPTs
    flush_rewrite_rules();

    // ─── Create Categories ─────────────────────────────────────
    $categories = [
        'Seating'     => 'Chairs, sofas, benches, and lounge seating for every occasion.',
        'Tables'      => 'Dining tables, cocktail tables, conference tables, and accent pieces.',
        'Lighting'    => 'Chandeliers, floor lamps, uplighting, and atmospheric lighting solutions.',
        'Lounge'      => 'Complete lounge setups, modular sofas, and relaxation areas.',
        'Decor'       => 'Centerpieces, vases, textiles, rugs, and decorative accessories.',
        'Staging'     => 'Stages, platforms, backdrops, and structural event elements.',
    ];

    $cat_ids = [];
    foreach ($categories as $name => $desc) {
        $term = wp_insert_term($name, 'rental_category', ['description' => $desc]);
        if (!is_wp_error($term)) {
            $cat_ids[$name] = $term['term_id'];
        }
    }

    // ─── Seed Items ────────────────────────────────────────────
    $items = [
        // Seating
        [
            'title'      => 'Chiavari Gold Chair',
            'excerpt'    => 'The timeless classic. Gold-finished chiavari with ivory cushion.',
            'content'    => 'Our signature Chiavari chairs bring timeless elegance to any event. The gold-finished hardwood frame pairs with a plush ivory cushion for comfort and sophistication. Perfect for formal dinners, weddings, and corporate galas. Available in quantities up to 500.',
            'category'   => 'Seating',
            'dimensions' => '40 × 42 × 92 cm',
            'material'   => 'Hardwood, gold leaf finish',
            'color'      => 'Gold / Ivory cushion',
            'min_qty'    => '10',
        ],
        [
            'title'      => 'Ghost Chair — Crystal',
            'excerpt'    => 'Transparent polycarbonate. Modern minimalism that disappears into any setting.',
            'content'    => 'The Ghost Chair makes a statement by nearly disappearing. Crafted from crystal-clear polycarbonate, it adds modern sophistication without visual weight. UV-resistant and incredibly durable — suitable for both indoor and outdoor events.',
            'category'   => 'Seating',
            'dimensions' => '38 × 40 × 90 cm',
            'material'   => 'Polycarbonate',
            'color'      => 'Crystal clear',
            'min_qty'    => '10',
        ],
        [
            'title'      => 'Velvet Lounge Armchair',
            'excerpt'    => 'Deep emerald velvet with brass legs. Statement seating for VIP areas.',
            'content'    => 'Rich emerald green velvet upholstery over a solid frame with brushed brass legs. This armchair defines VIP. Use it in lounge areas, cocktail zones, or as accent seating at head tables. Deep seat, generous proportions, unforgettable presence.',
            'category'   => 'Seating',
            'dimensions' => '75 × 80 × 85 cm',
            'material'   => 'Velvet upholstery, brass legs',
            'color'      => 'Emerald green',
            'min_qty'    => '2',
        ],
        [
            'title'      => 'Cross-Back Vineyard Chair',
            'excerpt'    => 'Rustic charm in dark walnut. Built for outdoor elegance.',
            'content'    => 'Our cross-back chairs evoke the warmth of countryside gatherings. The dark walnut finish and sturdy construction make them ideal for vineyard weddings, garden parties, and rustic-themed events. Stackable for efficient logistics.',
            'category'   => 'Seating',
            'dimensions' => '43 × 44 × 88 cm',
            'material'   => 'Solid beech, walnut stain',
            'color'      => 'Dark walnut',
            'min_qty'    => '10',
        ],

        // Tables
        [
            'title'      => 'Marble Banquet Table — 240cm',
            'excerpt'    => 'White Carrara-effect top on matte black steel legs. Seats 10.',
            'content'    => 'A centrepiece that commands attention. The 240cm banquet table features a premium Carrara marble-effect top paired with architectural matte black steel legs. Seats 8–10 guests comfortably. Perfect for formal dinners and gala events.',
            'category'   => 'Tables',
            'dimensions' => '240 × 100 × 75 cm',
            'material'   => 'Marble-effect composite, powder-coated steel',
            'color'      => 'White marble / Matte black',
            'min_qty'    => '1',
        ],
        [
            'title'      => 'Brass Cocktail Table — Round',
            'excerpt'    => 'Brushed brass base with tempered glass top. The definitive cocktail hour.',
            'content'    => 'Elevate your cocktail hour with our brushed brass cocktail tables. The tempered glass top floats above a sculptural brass base, creating an effect that is both substantial and refined. Available in two heights: standard (75cm) and bar height (110cm).',
            'category'   => 'Tables',
            'dimensions' => '60 × 60 × 110 cm',
            'material'   => 'Brushed brass, tempered glass',
            'color'      => 'Brass / Clear glass',
            'min_qty'    => '2',
        ],
        [
            'title'      => 'Farmhouse Trestle Table — 300cm',
            'excerpt'    => 'Solid reclaimed oak. Rustic grandeur for long-table dining.',
            'content'    => 'Our farmhouse trestle tables are crafted from genuine reclaimed oak, each bearing unique character marks that tell a story. At 300cm, they create the perfect setting for communal dining experiences. The trestle base allows unobstructed seating on all sides.',
            'category'   => 'Tables',
            'dimensions' => '300 × 110 × 76 cm',
            'material'   => 'Reclaimed oak',
            'color'      => 'Natural aged oak',
            'min_qty'    => '1',
        ],

        // Lighting
        [
            'title'      => 'Crystal Cascade Chandelier',
            'excerpt'    => 'Hundreds of hand-cut crystals. Five tiers of cascading light.',
            'content'    => 'The Crystal Cascade is our most requested lighting piece. Five tiers of hand-cut crystal pendants create a waterfall of light that transforms any space into something extraordinary. Requires rigging support — our team handles installation and removal.',
            'category'   => 'Lighting',
            'dimensions' => '120 × 120 × 180 cm (hanging)',
            'material'   => 'Hand-cut crystal, chrome frame',
            'color'      => 'Crystal / Chrome',
            'min_qty'    => '1',
        ],
        [
            'title'      => 'Edison Bulb Canopy — 50m²',
            'excerpt'    => 'Warm-white Edison strings. Creates an intimate canopy of golden light.',
            'content'    => 'Transform outdoor spaces with our Edison bulb canopy system. Warm-white vintage bulbs strung in a geometric pattern create a magical golden ceiling above your guests. Available in modular sections to cover any area. Weather-resistant for outdoor use.',
            'category'   => 'Lighting',
            'dimensions' => 'Covers approx. 50 m²',
            'material'   => 'Glass Edison bulbs, rubber cable',
            'color'      => 'Warm white (2700K)',
            'min_qty'    => '1',
        ],
        [
            'title'      => 'Wireless LED Uplighters — Set of 8',
            'excerpt'    => 'Battery-powered. Full RGB. Transform walls and architecture with light.',
            'content'    => 'Our wireless LED uplighters require no cables and no setup — just place and activate via remote. Full RGB colour spectrum allows you to match any event theme. 12-hour battery life ensures they last the entire event. Includes wireless DMX controller.',
            'category'   => 'Lighting',
            'dimensions' => '15 × 15 × 20 cm each',
            'material'   => 'Aluminium housing, LED',
            'color'      => 'Full RGB spectrum',
            'min_qty'    => '1',
        ],

        // Lounge
        [
            'title'      => 'Modular Lounge Set — Midnight',
            'excerpt'    => 'Dark velvet modular sofa with gold accent tables. Complete VIP zone.',
            'content'    => 'Our Midnight lounge set creates an instant VIP area. Deep navy velvet modular pieces can be configured as L-shape, U-shape, or facing sofas. Includes matching gold-finished accent tables. Accommodates 8–12 guests in luxurious comfort.',
            'category'   => 'Lounge',
            'dimensions' => 'Variable — up to 400 × 300 cm',
            'material'   => 'Velvet upholstery, solid wood frame',
            'color'      => 'Midnight navy / Gold accents',
            'min_qty'    => '1',
        ],
        [
            'title'      => 'Rattan Daybed — Riviera',
            'excerpt'    => 'Outdoor luxury. Natural rattan with white cushions and canopy.',
            'content'    => 'Bring resort luxury to any outdoor event. The Riviera daybed features a natural rattan frame with plush white all-weather cushions and an adjustable canopy. Fits two guests comfortably. A statement piece for garden parties and poolside events.',
            'category'   => 'Lounge',
            'dimensions' => '200 × 150 × 180 cm',
            'material'   => 'Natural rattan, all-weather fabric',
            'color'      => 'Natural / White',
            'min_qty'    => '1',
        ],

        // Decor
        [
            'title'      => 'Brass Arch — 280cm',
            'excerpt'    => 'Freestanding ceremony arch in brushed brass. A frame for unforgettable moments.',
            'content'    => 'Our signature brass arch stands 280cm tall with a gentle curve that frames ceremonies, photo opportunities, or stage backdrops beautifully. Freestanding with weighted base — no rigging required. Often dressed with florals by the client\'s florist.',
            'category'   => 'Decor',
            'dimensions' => '200 × 40 × 280 cm',
            'material'   => 'Brushed brass tubular steel',
            'color'      => 'Brushed brass',
            'min_qty'    => '1',
        ],
        [
            'title'      => 'Silk Table Runner Collection',
            'excerpt'    => 'Hand-dyed silk runners in 12 colourways. Drapes like water.',
            'content'    => 'Our hand-dyed silk table runners add a layer of organic texture and colour to any table setting. Each runner is 400cm long to allow generous draping over table edges. Available in: Champagne, Dusty Rose, Terracotta, Sage, Ivory, Slate, Burgundy, Navy, Forest, Blush, Copper, and Pearl.',
            'category'   => 'Decor',
            'dimensions' => '400 × 40 cm',
            'material'   => 'Hand-dyed natural silk',
            'color'      => '12 colourways available',
            'min_qty'    => '5',
        ],

        // Staging
        [
            'title'      => 'Modular Stage Platform — Black',
            'excerpt'    => 'Professional stage system. Adjustable height, configurable size.',
            'content'    => 'Our modular stage system uses interlocking 2m × 1m platforms with adjustable legs (40–100cm height). Black carpet finish. Includes skirting and safety rails. Can be configured to any rectangular shape. Professional installation included.',
            'category'   => 'Staging',
            'dimensions' => '200 × 100 × 40–100 cm per module',
            'material'   => 'Aluminium frame, plywood deck, carpet finish',
            'color'      => 'Black',
            'min_qty'    => '4',
        ],
        [
            'title'      => 'Greenery Wall — 3m × 2.4m',
            'excerpt'    => 'Lush artificial greenery wall panel. Photo-ready backdrop.',
            'content'    => 'Our premium greenery walls use high-quality artificial foliage that looks real in photos and on camera. Each panel is 3m × 2.4m and freestanding with an aluminium frame. Can be combined for larger installations. Custom branding inserts available.',
            'category'   => 'Staging',
            'dimensions' => '300 × 30 × 240 cm',
            'material'   => 'Premium artificial foliage, aluminium frame',
            'color'      => 'Mixed greens',
            'min_qty'    => '1',
        ],
    ];

    foreach ($items as $item) {
        $post_id = wp_insert_post([
            'post_type'    => 'rental_item',
            'post_title'   => $item['title'],
            'post_excerpt' => $item['excerpt'],
            'post_content' => $item['content'],
            'post_status'  => 'publish',
        ]);

        if (is_wp_error($post_id)) continue;

        // Assign category
        if (isset($cat_ids[$item['category']])) {
            wp_set_object_terms($post_id, [$cat_ids[$item['category']]], 'rental_category');
        }

        // Set meta
        update_post_meta($post_id, '_item_dimensions', $item['dimensions']);
        update_post_meta($post_id, '_item_material', $item['material']);
        update_post_meta($post_id, '_item_color', $item['color']);
        update_post_meta($post_id, '_item_min_qty', $item['min_qty']);
    }

    // ─── Create Primary Menu ───────────────────────────────────
    $menu_id = wp_create_nav_menu('Primary');
    if (!is_wp_error($menu_id)) {
        wp_update_nav_menu_item($menu_id, 0, [
            'menu-item-title'   => 'Catalogue',
            'menu-item-url'     => home_url('/catalogue/'),
            'menu-item-status'  => 'publish',
            'menu-item-type'    => 'custom',
        ]);

        // Add category links
        foreach ($cat_ids as $name => $tid) {
            $link = get_term_link($tid, 'rental_category');
            if (!is_wp_error($link)) {
                wp_update_nav_menu_item($menu_id, 0, [
                    'menu-item-title'  => $name,
                    'menu-item-url'    => $link,
                    'menu-item-status' => 'publish',
                    'menu-item-type'   => 'custom',
                ]);
            }
        }

        wp_update_nav_menu_item($menu_id, 0, [
            'menu-item-title'   => 'Request Quote',
            'menu-item-url'     => home_url('/request-quote/'),
            'menu-item-status'  => 'publish',
            'menu-item-type'    => 'custom',
            'menu-item-classes' => 'menu-cta',
        ]);

        $locations = get_theme_mod('nav_menu_locations', []);
        $locations['primary'] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }

    // ─── Set Permalink Structure ───────────────────────────────
    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure('/%postname%/');
    $wp_rewrite->flush_rules();
}
