<?php
/**
 * Single Rental Item
 */
get_header();

$post_id    = get_the_ID();
$categories = get_the_terms($post_id, 'rental_category');
$cat_name   = $categories ? $categories[0]->name : '';
$cat_link   = $categories ? get_term_link($categories[0]) : '';
$dimensions = get_post_meta($post_id, '_item_dimensions', true);
$material   = get_post_meta($post_id, '_item_material', true);
$color      = get_post_meta($post_id, '_item_color', true);
$min_qty    = get_post_meta($post_id, '_item_min_qty', true) ?: '1';
$gallery    = get_post_meta($post_id, '_item_gallery', true);
$thumb_url  = get_the_post_thumbnail_url($post_id, 'catalogue-card');
$title      = get_the_title();
?>

<article class="single-product">
    <div class="container">
        <div class="product-layout">
            <!-- Gallery -->
            <div class="product-gallery" x-data="{ activeImage: '<?php echo esc_url(get_the_post_thumbnail_url($post_id, 'catalogue-hero')); ?>' }">
                <div class="product-gallery-main">
                    <?php if (has_post_thumbnail()) : ?>
                        <img :src="activeImage" alt="<?php echo esc_attr($title); ?>">
                    <?php else : ?>
                        <div class="product-card-placeholder" style="height:100%; font-size:6rem;">◆</div>
                    <?php endif; ?>
                </div>
                <?php if ($gallery) :
                    $gallery_ids = array_filter(explode(',', $gallery));
                    if (!empty($gallery_ids)) :
                ?>
                <div class="product-gallery-thumbs">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="product-gallery-thumb active"
                             @click="activeImage = '<?php echo esc_url(get_the_post_thumbnail_url($post_id, 'catalogue-hero')); ?>'">
                            <?php the_post_thumbnail('gallery-thumb'); ?>
                        </div>
                    <?php endif; ?>
                    <?php foreach ($gallery_ids as $gid) :
                        $gurl = wp_get_attachment_image_url($gid, 'catalogue-hero');
                    ?>
                        <div class="product-gallery-thumb"
                             @click="activeImage = '<?php echo esc_url($gurl); ?>'">
                            <?php echo wp_get_attachment_image($gid, 'gallery-thumb'); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; endif; ?>
            </div>

            <!-- Info -->
            <div class="product-info">
                <div class="product-breadcrumb">
                    <a href="<?php echo home_url('/catalogue/'); ?>">Catalogue</a>
                    <?php if ($cat_name && $cat_link) : ?>
                        <span class="sep">/</span>
                        <a href="<?php echo esc_url($cat_link); ?>"><?php echo esc_html($cat_name); ?></a>
                    <?php endif; ?>
                    <span class="sep">/</span>
                    <span style="color:var(--text-secondary);"><?php the_title(); ?></span>
                </div>

                <h1 class="product-title"><?php the_title(); ?></h1>

                <?php if (has_excerpt()) : ?>
                    <p class="product-excerpt"><?php echo get_the_excerpt(); ?></p>
                <?php endif; ?>

                <div class="product-description">
                    <?php the_content(); ?>
                </div>

                <!-- Specs -->
                <div class="product-specs">
                    <?php if ($dimensions) : ?>
                    <div class="product-spec">
                        <span class="product-spec-label">Dimensions</span>
                        <span class="product-spec-value"><?php echo esc_html($dimensions); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($material) : ?>
                    <div class="product-spec">
                        <span class="product-spec-label">Material</span>
                        <span class="product-spec-value"><?php echo esc_html($material); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($color) : ?>
                    <div class="product-spec">
                        <span class="product-spec-label">Color / Finish</span>
                        <span class="product-spec-value"><?php echo esc_html($color); ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="product-spec">
                        <span class="product-spec-label">Minimum Quantity</span>
                        <span class="product-spec-value"><?php echo esc_html($min_qty); ?></span>
                    </div>
                </div>

                <!-- Add to Selection -->
                <div class="product-add-section" x-data="{ qty: <?php echo intval($min_qty); ?> }">
                    <div class="product-qty-row">
                        <label class="qty-label" for="product-qty">Quantity</label>
                        <input type="number" id="product-qty" class="qty-input" x-model.number="qty" min="<?php echo intval($min_qty); ?>">
                    </div>
                    <button class="btn btn--solid" style="width:100%; justify-content:center;"
                            @click="$store.quote.addItem(<?php echo $post_id; ?>, '<?php echo esc_js($title); ?>', '<?php echo esc_js($thumb_url); ?>', qty)">
                        <span>Add to Selection</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</article>

<?php get_footer(); ?>
