<?php
/**
 * Default Index — fallback template
 */
get_header();
?>

<div class="archive-header">
    <div class="container">
        <h1 class="archive-title"><?php wp_title(''); ?></h1>
    </div>
</div>

<section class="catalogue-section">
    <div class="container">
        <?php if (have_posts()) : ?>
            <div class="catalogue-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <article class="product-card">
                        <div class="product-card-body">
                            <h3 class="product-card-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <p class="product-card-excerpt"><?php echo get_the_excerpt(); ?></p>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <div class="no-results">
                <h2>Nothing here yet</h2>
                <p><a href="<?php echo home_url('/catalogue/'); ?>">Browse our catalogue</a></p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
