<?php
/**
 * Default Page Template
 */
get_header();
?>

<div class="archive-header">
    <div class="container">
        <h1 class="archive-title"><?php the_title(); ?></h1>
    </div>
</div>

<section class="catalogue-section">
    <div class="container" style="max-width:800px;">
        <?php while (have_posts()) : the_post(); ?>
            <div class="product-description">
                <?php the_content(); ?>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<?php get_footer(); ?>
