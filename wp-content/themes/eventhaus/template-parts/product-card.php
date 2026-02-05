<?php
/**
 * Product Card Template Part
 */

$post_id    = get_the_ID();
$categories = get_the_terms($post_id, 'rental_category');
$cat_name   = $categories ? $categories[0]->name : '';
$dimensions = get_post_meta($post_id, '_item_dimensions', true);
$material   = get_post_meta($post_id, '_item_material', true);
$thumb_url  = get_the_post_thumbnail_url($post_id, 'catalogue-card');
$title      = get_the_title();
?>
<div class="product-card" data-category="<?php echo esc_attr($cat_name); ?>">
    <a href="<?php the_permalink(); ?>" class="product-card-image">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('catalogue-card'); ?>
        <?php else : ?>
            <div class="product-card-placeholder">◆</div>
        <?php endif; ?>
        <?php if ($cat_name) : ?>
            <span class="product-card-category"><?php echo esc_html($cat_name); ?></span>
        <?php endif; ?>
    </a>

    <div class="product-card-body">
        <h3 class="product-card-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <?php if (has_excerpt()) : ?>
            <p class="product-card-excerpt"><?php echo get_the_excerpt(); ?></p>
        <?php endif; ?>

        <div class="product-card-meta">
            <?php if ($dimensions) : ?>
                <span class="product-card-meta-item"><strong>Size:</strong> <?php echo esc_html($dimensions); ?></span>
            <?php endif; ?>
            <?php if ($material) : ?>
                <span class="product-card-meta-item"><strong>Material:</strong> <?php echo esc_html($material); ?></span>
            <?php endif; ?>
        </div>

        <div class="product-card-actions" x-data>
            <button class="btn btn--sm"
                    @click="$store.quote.addItem(<?php echo $post_id; ?>, '<?php echo esc_js($title); ?>', '<?php echo esc_js($thumb_url); ?>')">
                <span>Add to Selection</span>
            </button>
        </div>
    </div>
</div>
