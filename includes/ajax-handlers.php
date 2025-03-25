<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// AJAX handler to update a campaign tracker item’s stage.
add_action( 'wp_ajax_cem_update_campaign_stage', 'cem_update_campaign_stage' );
function cem_update_campaign_stage() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Unauthorized' );
    }
    global $wpdb;
    $tracker_table = $wpdb->prefix . 'cem_campaign_tracker';
    $item_id = intval( $_POST['item_id'] );
    $new_stage = sanitize_text_field( $_POST['new_stage'] );

    $updated = $wpdb->update( $tracker_table, array(
        'stage'      => $new_stage,
        'updated_at' => current_time( 'mysql' )
    ), array( 'id' => $item_id ) );

    if ( $updated !== false ) {
        wp_send_json_success( 'Updated' );
    } else {
        wp_send_json_error( 'Error updating stage' );
    }
}

// AJAX handler to get template details for the Email Sender preview.
add_action( 'wp_ajax_cem_get_template_details', 'cem_get_template_details' );
function cem_get_template_details() {
    global $wpdb;
    $template_id = intval( $_POST['template_id'] );
    $template = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}cem_templates WHERE id = %d", $template_id ) );
    if ( $template ) {
        wp_send_json_success( $template );
    } else {
        wp_send_json_error( 'Template not found' );
    }
}
