<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function cem_logs_page() {
    global $wpdb;
    $logs_table = $wpdb->prefix . 'cem_logs';
    $logs = $wpdb->get_results( "SELECT * FROM $logs_table ORDER BY sent_at DESC" );
    ?>
    <div class="wrap">
        <h1><?php _e( 'Campaign Logs', 'cem' ); ?></h1>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e( 'ID', 'cem' ); ?></th>
                    <th><?php _e( 'Campaign Title', 'cem' ); ?></th>
                    <th><?php _e( 'Company Name', 'cem' ); ?></th>
                    <th><?php _e( 'Sent At', 'cem' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if ( $logs ) : ?>
                    <?php foreach ( $logs as $log ) : ?>
                        <tr>
                            <td><?php echo esc_html( $log->id ); ?></td>
                            <td><?php echo esc_html( $log->campaign_title ); ?></td>
                            <td><?php echo esc_html( $log->company_name ); ?></td>
                            <td><?php echo esc_html( $log->sent_at ); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4"><?php _e( 'No logs found.', 'cem' ); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}
