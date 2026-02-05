<?php
/**
 * Meta Boxes for Rental Items & Quote Requests
 */

defined('ABSPATH') || exit;

// ─── Rental Item Meta Box ──────────────────────────────────────
add_action('add_meta_boxes', function () {
    add_meta_box(
        'rental_item_details',
        __('Item Details', 'eventhaus'),
        'eventhaus_render_item_meta',
        'rental_item',
        'normal',
        'high'
    );

    add_meta_box(
        'rental_item_gallery',
        __('Gallery Images', 'eventhaus'),
        'eventhaus_render_gallery_meta',
        'rental_item',
        'normal',
        'default'
    );

    add_meta_box(
        'quote_request_details',
        __('Request Details', 'eventhaus'),
        'eventhaus_render_quote_meta',
        'quote_request',
        'normal',
        'high'
    );
});

function eventhaus_render_item_meta($post) {
    wp_nonce_field('eventhaus_item_meta', 'eventhaus_item_nonce');
    $meta = get_post_meta($post->ID);
    $dimensions = $meta['_item_dimensions'][0] ?? '';
    $material   = $meta['_item_material'][0] ?? '';
    $color      = $meta['_item_color'][0] ?? '';
    $min_qty    = $meta['_item_min_qty'][0] ?? '1';
    ?>
    <table class="form-table">
        <tr>
            <th><label for="item_dimensions"><?php _e('Dimensions', 'eventhaus'); ?></label></th>
            <td><input type="text" id="item_dimensions" name="item_dimensions" value="<?php echo esc_attr($dimensions); ?>" class="regular-text" placeholder="e.g. 80 × 80 × 75 cm"></td>
        </tr>
        <tr>
            <th><label for="item_material"><?php _e('Material', 'eventhaus'); ?></label></th>
            <td><input type="text" id="item_material" name="item_material" value="<?php echo esc_attr($material); ?>" class="regular-text" placeholder="e.g. Solid oak, brass accents"></td>
        </tr>
        <tr>
            <th><label for="item_color"><?php _e('Color / Finish', 'eventhaus'); ?></label></th>
            <td><input type="text" id="item_color" name="item_color" value="<?php echo esc_attr($color); ?>" class="regular-text" placeholder="e.g. Matte black, Natural walnut"></td>
        </tr>
        <tr>
            <th><label for="item_min_qty"><?php _e('Minimum Quantity', 'eventhaus'); ?></label></th>
            <td><input type="number" id="item_min_qty" name="item_min_qty" value="<?php echo esc_attr($min_qty); ?>" class="small-text" min="1"></td>
        </tr>
    </table>
    <?php
}

function eventhaus_render_gallery_meta($post) {
    wp_nonce_field('eventhaus_gallery_meta', 'eventhaus_gallery_nonce');
    $gallery_ids = get_post_meta($post->ID, '_item_gallery', true) ?: '';
    ?>
    <div id="eventhaus-gallery-wrap">
        <input type="hidden" name="item_gallery" id="item_gallery" value="<?php echo esc_attr($gallery_ids); ?>">
        <div id="eventhaus-gallery-preview" style="display:flex; flex-wrap:wrap; gap:8px; margin-bottom:12px;">
            <?php
            if ($gallery_ids) {
                foreach (explode(',', $gallery_ids) as $id) {
                    $img = wp_get_attachment_image($id, 'thumbnail');
                    if ($img) {
                        echo '<div class="gallery-thumb" style="position:relative;">' . $img . '<button type="button" class="remove-gallery-img" data-id="' . $id . '" style="position:absolute;top:-5px;right:-5px;background:red;color:#fff;border:none;border-radius:50%;width:20px;height:20px;cursor:pointer;font-size:12px;line-height:1;">&times;</button></div>';
                    }
                }
            }
            ?>
        </div>
        <button type="button" id="eventhaus-add-gallery" class="button"><?php _e('Add Gallery Images', 'eventhaus'); ?></button>
    </div>
    <script>
    jQuery(function($){
        var frame;
        $('#eventhaus-add-gallery').on('click', function(e){
            e.preventDefault();
            if (frame) { frame.open(); return; }
            frame = wp.media({
                title: 'Select Gallery Images',
                button: { text: 'Add to Gallery' },
                multiple: true
            });
            frame.on('select', function(){
                var selection = frame.state().get('selection');
                var ids = $('#item_gallery').val() ? $('#item_gallery').val().split(',') : [];
                selection.each(function(att){
                    ids.push(att.id);
                    var url = att.attributes.sizes && att.attributes.sizes.thumbnail ? att.attributes.sizes.thumbnail.url : att.attributes.url;
                    $('#eventhaus-gallery-preview').append('<div class="gallery-thumb" style="position:relative;"><img src="'+url+'" style="width:80px;height:80px;object-fit:cover;"><button type="button" class="remove-gallery-img" data-id="'+att.id+'" style="position:absolute;top:-5px;right:-5px;background:red;color:#fff;border:none;border-radius:50%;width:20px;height:20px;cursor:pointer;font-size:12px;line-height:1;">&times;</button></div>');
                });
                $('#item_gallery').val(ids.join(','));
            });
            frame.open();
        });
        $(document).on('click', '.remove-gallery-img', function(){
            var id = $(this).data('id');
            var ids = $('#item_gallery').val().split(',').filter(function(v){ return v != id; });
            $('#item_gallery').val(ids.join(','));
            $(this).parent().remove();
        });
    });
    </script>
    <?php
}

function eventhaus_render_quote_meta($post) {
    $meta = get_post_meta($post->ID);
    $items = json_decode($meta['_selected_items'][0] ?? '[]', true);
    ?>
    <table class="form-table">
        <tr><th><?php _e('Client Name', 'eventhaus'); ?></th><td><?php echo esc_html($meta['_client_name'][0] ?? '—'); ?></td></tr>
        <tr><th><?php _e('Email', 'eventhaus'); ?></th><td><a href="mailto:<?php echo esc_attr($meta['_client_email'][0] ?? ''); ?>"><?php echo esc_html($meta['_client_email'][0] ?? '—'); ?></a></td></tr>
        <tr><th><?php _e('Phone', 'eventhaus'); ?></th><td><?php echo esc_html($meta['_client_phone'][0] ?? '—'); ?></td></tr>
        <tr><th><?php _e('Company', 'eventhaus'); ?></th><td><?php echo esc_html($meta['_client_company'][0] ?? '—'); ?></td></tr>
        <tr><th><?php _e('Event Date', 'eventhaus'); ?></th><td><?php echo esc_html($meta['_event_date'][0] ?? '—'); ?></td></tr>
        <tr><th><?php _e('Event Venue', 'eventhaus'); ?></th><td><?php echo esc_html($meta['_event_venue'][0] ?? '—'); ?></td></tr>
        <tr><th><?php _e('Notes', 'eventhaus'); ?></th><td><?php echo nl2br(esc_html($meta['_client_notes'][0] ?? '—')); ?></td></tr>
        <tr>
            <th><?php _e('Selected Items', 'eventhaus'); ?></th>
            <td>
                <?php if ($items): ?>
                <table class="widefat striped" style="max-width:600px;">
                    <thead><tr><th>Item</th><th>Qty</th></tr></thead>
                    <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <?php
                                $linked_post = get_post($item['id'] ?? 0);
                                if ($linked_post) {
                                    echo '<a href="' . get_edit_post_link($linked_post->ID) . '">' . esc_html($linked_post->post_title) . '</a>';
                                } else {
                                    echo esc_html($item['name'] ?? 'Unknown');
                                }
                                ?>
                            </td>
                            <td><?php echo intval($item['qty'] ?? 1); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <em><?php _e('No items in this request.', 'eventhaus'); ?></em>
                <?php endif; ?>
            </td>
        </tr>
    </table>
    <?php
}

// ─── Save Meta ─────────────────────────────────────────────────
add_action('save_post_rental_item', function ($post_id) {
    if (!isset($_POST['eventhaus_item_nonce']) || !wp_verify_nonce($_POST['eventhaus_item_nonce'], 'eventhaus_item_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    $fields = ['item_dimensions', 'item_material', 'item_color', 'item_min_qty'];
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }

    if (!isset($_POST['eventhaus_gallery_nonce']) || !wp_verify_nonce($_POST['eventhaus_gallery_nonce'], 'eventhaus_gallery_meta')) return;
    if (isset($_POST['item_gallery'])) {
        $ids = array_filter(array_map('intval', explode(',', $_POST['item_gallery'])));
        update_post_meta($post_id, '_item_gallery', implode(',', $ids));
    }
});
