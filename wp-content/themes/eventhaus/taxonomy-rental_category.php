<?php
/**
 * Category Archive
 */
get_header();

$term = get_queried_object();
?>

<div class="archive-header">
    <div class="container">
        <span class="section-label"><?php echo esc_html($term->name); ?></span>
        <h1 class="archive-title"><?php echo esc_html($term->name); ?></h1>
        <?php if ($term->description) : ?>
            <p class="archive-desc"><?php echo esc_html($term->description); ?></p>
        <?php endif; ?>
    </div>
</div>

<section class="catalogue-section">
    <div class="container">
        <div class="catalogue-grid">
            <?php
            if (have_posts()) :
                while (have_posts()) : the_post();
                    get_template_part('template-parts/product-card');
                endwhile;
            else :
            ?>
                <div class="no-results">
                    <h2>No items in this category</h2>
                    <p><a href="<?php echo home_url('/catalogue/'); ?>">Browse the full catalogue</a></p>
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
