<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class cem_database {

    public static function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        // Logs table.
        $logs_table = $wpdb->prefix . 'cem_logs';
        $sql_logs = "CREATE TABLE $logs_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            campaign_title varchar(255) NOT NULL,
            company_name varchar(255) NOT NULL,
            sent_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        // Templates table.
        $templates_table = $wpdb->prefix . 'cem_templates';
        $sql_templates = "CREATE TABLE $templates_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            template_title varchar(255) NOT NULL,
            event_name varchar(255) NOT NULL,
            request_type varchar(50) NOT NULL, -- Partnership or Sponsorship
            template_content text NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        // Campaign Tracker table.
        $tracker_table = $wpdb->prefix . 'cem_campaign_tracker';
        $sql_tracker = "CREATE TABLE $tracker_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            campaign_id mediumint(9) NOT NULL,
            company_name varchar(255) NOT NULL,
            company_email varchar(255) NOT NULL,
            stage varchar(50) NOT NULL, -- Contacted, Negotiation: Responded, Negotiation: Email Sent, Accepted, Rejected
            updated_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql_logs );
        dbDelta( $sql_templates );
        dbDelta( $sql_tracker );
    }
}
