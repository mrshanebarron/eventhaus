<?php
/**
 * Catalogue Archive — All Items
 */
get_header();

$categories = get_terms(['taxonomy' => 'rental_category', 'hide_empty' => true]);
?>

<div class="archive-header">
    <div class="container">
        <span class="section-label">Full Collection</span>
        <h1 class="archive-title">Our Catalogue</h1>
        <p class="archive-desc">Every piece in our collection, ready for your next event. Add items to your selection and request a quote.</p>
    </div>
</div>

<section class="catalogue-section">
    <div class="container">
        <div class="catalogue-header">
            <div></div>
            <?php if ($categories && !is_wp_error($categories)) : ?>
            <div class="catalogue-filters" x-data="catalogueFilter()">
                <button class="filter-btn" :class="{ 'active': active === 'all' }" @click="filter('all')">All</button>
                <?php foreach ($categories as $cat) : ?>
                    <button class="filter-btn"
                            :class="{ 'active': active === '<?php echo esc_js(strtolower($cat->name)); ?>' }"
                            @click="filter('<?php echo esc_js(strtolower($cat->name)); ?>')">
                        <?php echo esc_html($cat->name); ?>
                    </button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="catalogue-grid">
            <?php
            if (have_posts()) :
                while (have_posts()) : the_post();
                    get_template_part('template-parts/product-card');
                endwhile;
            else :
            ?>
                <div class="no-results">
                    <h2>No items found</h2>
                    <p>Check back soon — we're always adding to our collection.</p>
                </div>
            <?php endif; ?>
        </div>

        <?php
        the_posts_pagination([
            'prev_text' => '&larr; Previous',
            'next_text' => 'Next &rarr;',
        ]);
        ?>
    </div>
</section>

<?php get_footer(); ?>
