<?php
/**
 * Front Page Template
 */
get_header();
?>

<!-- Hero -->
<section class="hero">
    <div class="hero-bg">
        <div class="hero-pattern"></div>
    </div>
    <div class="hero-line"></div>

    <div class="container hero-content">
        <span class="section-label">Premium Event Rentals</span>
        <h1 class="hero-title">Furnishing<br><em>Extraordinary</em><br>Moments</h1>
        <p class="hero-subtitle">Curated furniture, lighting, and décor for events that leave a lasting impression. Browse our collection, build your selection, and receive a tailored proposal within 24 hours.</p>
        <div class="hero-actions">
            <a href="<?php echo home_url('/catalogue/'); ?>" class="btn btn--solid">
                <span>Browse Catalogue</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="<?php echo home_url('/request-quote/'); ?>" class="btn">
                <span>Request a Quote</span>
            </a>
        </div>
    </div>

    <div class="hero-stats">
        <?php
        $item_count = wp_count_posts('rental_item')->publish;
        $cat_count = wp_count_terms(['taxonomy' => 'rental_category', 'hide_empty' => true]);
        ?>
        <div class="hero-stat">
            <div class="hero-stat-number"><?php echo $item_count; ?>+</div>
            <div class="hero-stat-label">Rental Pieces</div>
        </div>
        <div class="hero-stat">
            <div class="hero-stat-number"><?php echo $cat_count; ?></div>
            <div class="hero-stat-label">Categories</div>
        </div>
        <div class="hero-stat">
            <div class="hero-stat-number">24h</div>
            <div class="hero-stat-label">Quote Response</div>
        </div>
    </div>
</section>

<!-- Categories -->
<section class="categories-section">
    <div class="container container--wide">
        <span class="section-label reveal">Our Collection</span>
        <h2 class="section-title reveal">Browse by Category</h2>

        <div class="categories-grid">
            <?php
            $categories = get_terms([
                'taxonomy'   => 'rental_category',
                'hide_empty' => false,
                'orderby'    => 'name',
            ]);

            if ($categories && !is_wp_error($categories)) :
                foreach ($categories as $cat) :
                    $count = $cat->count;

                    // Get featured image from first item in this category
                    $cat_items = new WP_Query([
                        'post_type'      => 'rental_item',
                        'posts_per_page' => 1,
                        'tax_query'      => [[
                            'taxonomy' => 'rental_category',
                            'field'    => 'term_id',
                            'terms'    => $cat->term_id,
                        ]],
                        'meta_key'       => '_thumbnail_id',
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                    ]);
                    $cat_image = '';
                    if ($cat_items->have_posts()) {
                        $cat_image = get_the_post_thumbnail_url($cat_items->posts[0]->ID, 'catalogue-card');
                    }
                    wp_reset_postdata();
            ?>
                <a href="<?php echo get_term_link($cat); ?>" class="category-card">
                    <?php if ($cat_image) : ?>
                        <div class="category-card-bg" style="background-image:url('<?php echo esc_url($cat_image); ?>');"></div>
                    <?php else : ?>
                        <div class="category-card-bg"></div>
                    <?php endif; ?>
                    <div class="category-card-overlay"></div>
                    <div class="category-card-content">
                        <h3 class="category-card-name"><?php echo esc_html($cat->name); ?></h3>
                        <span class="category-card-count"><?php echo $count; ?> <?php echo _n('item', 'items', $count, 'eventhaus'); ?></span>
                    </div>
                    <div class="category-card-arrow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 17L17 7M17 7H7M17 7v10"/></svg>
                    </div>
                </a>
            <?php
                endforeach;
            endif;
            ?>
        </div>
    </div>
</section>

<!-- Featured Items -->
<section class="catalogue-section">
    <div class="container">
        <div class="catalogue-header">
            <div>
                <span class="section-label reveal">Featured Pieces</span>
                <h2 class="section-title reveal">Recently Added</h2>
            </div>
            <a href="<?php echo home_url('/catalogue/'); ?>" class="btn btn--sm reveal">
                <span>View All</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="catalogue-grid">
            <?php
            $featured = new WP_Query([
                'post_type'      => 'rental_item',
                'posts_per_page' => 6,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ]);

            if ($featured->have_posts()) :
                while ($featured->have_posts()) : $featured->the_post();
                    get_template_part('template-parts/product-card');
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
