<?php
/**
 * AJAX Quote Request Handler
 */

defined('ABSPATH') || exit;

add_action('wp_ajax_eventhaus_submit_quote', 'eventhaus_handle_quote');
add_action('wp_ajax_nopriv_eventhaus_submit_quote', 'eventhaus_handle_quote');

function eventhaus_handle_quote() {
    check_ajax_referer('eventhaus_nonce', 'nonce');

    $name    = sanitize_text_field($_POST['client_name'] ?? '');
    $email   = sanitize_email($_POST['client_email'] ?? '');
    $phone   = sanitize_text_field($_POST['client_phone'] ?? '');
    $company = sanitize_text_field($_POST['client_company'] ?? '');
    $date    = sanitize_text_field($_POST['event_date'] ?? '');
    $venue   = sanitize_text_field($_POST['event_venue'] ?? '');
    $notes   = sanitize_textarea_field($_POST['client_notes'] ?? '');
    $items   = json_decode(stripslashes($_POST['selected_items'] ?? '[]'), true);

    if (!$name || !$email) {
        wp_send_json_error(['message' => __('Name and email are required.', 'eventhaus')]);
    }

    if (!is_array($items) || empty($items)) {
        wp_send_json_error(['message' => __('Please add at least one item to your selection.', 'eventhaus')]);
    }

    // Sanitize items
    $clean_items = [];
    foreach ($items as $item) {
        $clean_items[] = [
            'id'   => intval($item['id'] ?? 0),
            'name' => sanitize_text_field($item['name'] ?? ''),
            'qty'  => max(1, intval($item['qty'] ?? 1)),
        ];
    }

    // Build title
    $item_count = count($clean_items);
    $title = sprintf('%s — %d %s — %s',
        $name,
        $item_count,
        _n('item', 'items', $item_count, 'eventhaus'),
        $date ?: 'No date'
    );

    $post_id = wp_insert_post([
        'post_type'   => 'quote_request',
        'post_title'  => $title,
        'post_status' => 'publish',
    ]);

    if (is_wp_error($post_id)) {
        wp_send_json_error(['message' => __('Could not save your request. Please try again.', 'eventhaus')]);
    }

    update_post_meta($post_id, '_client_name', $name);
    update_post_meta($post_id, '_client_email', $email);
    update_post_meta($post_id, '_client_phone', $phone);
    update_post_meta($post_id, '_client_company', $company);
    update_post_meta($post_id, '_event_date', $date);
    update_post_meta($post_id, '_event_venue', $venue);
    update_post_meta($post_id, '_client_notes', $notes);
    update_post_meta($post_id, '_selected_items', wp_json_encode($clean_items));

    // Send admin notification
    $admin_email = get_option('admin_email');
    $item_list = '';
    foreach ($clean_items as $ci) {
        $item_list .= sprintf("  • %s (×%d)\n", $ci['name'], $ci['qty']);
    }

    $message = sprintf(
        "New quote request from %s (%s)\n\nEvent Date: %s\nVenue: %s\nCompany: %s\nPhone: %s\n\nSelected Items:\n%s\nNotes:\n%s\n\nView in admin: %s",
        $name, $email, $date ?: 'Not specified', $venue ?: 'Not specified',
        $company ?: 'Not specified', $phone ?: 'Not specified',
        $item_list, $notes ?: 'None',
        admin_url('post.php?post=' . $post_id . '&action=edit')
    );

    wp_mail($admin_email, '[EventHaus] New Quote Request: ' . $title, $message);

    wp_send_json_success([
        'message' => __('Thank you! Your quote request has been submitted. We will be in touch within 24 hours.', 'eventhaus'),
    ]);
}
