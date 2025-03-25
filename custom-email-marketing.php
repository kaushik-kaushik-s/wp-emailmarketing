<?php
/**
 * Plugin Name: Custom Email Marketing
 * Description: A custom email marketing solution with dashboard, logs, email sender, campaign tracker, and templates management.
 * Version: 1.0
 * Author: Kaushik Sannidhi
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

define( 'CEM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// Include required files.
require_once CEM_PLUGIN_DIR . 'includes/class-cem-database.php';
require_once CEM_PLUGIN_DIR . 'includes/admin-dashboard.php';
require_once CEM_PLUGIN_DIR . 'includes/admin-logs.php';
require_once CEM_PLUGIN_DIR . 'includes/admin-email-sender.php';
require_once CEM_PLUGIN_DIR . 'includes/admin-campaign-tracker.php';
require_once CEM_PLUGIN_DIR . 'includes/admin-templates.php';
require_once CEM_PLUGIN_DIR . 'includes/ajax-handlers.php';

// Activation hook to create necessary tables.
register_activation_hook( __FILE__, 'cem_activate_plugin' );
function cem_activate_plugin() {
    cem_database::create_tables();
}

// Register admin menus.
add_action( 'admin_menu', 'cem_register_admin_menu' );
function cem_register_admin_menu() {
    add_menu_page( 'Email Marketing', 'Email Marketing', 'manage_options', 'cem-dashboard', 'cem_dashboard_page', 'dashicons-email-alt', 6 );
    add_submenu_page( 'cem-dashboard', 'Dashboard', 'Dashboard', 'manage_options', 'cem-dashboard', 'cem_dashboard_page' );
    add_submenu_page( 'cem-dashboard', 'Logs', 'Logs', 'manage_options', 'cem-logs', 'cem_logs_page' );
    add_submenu_page( 'cem-dashboard', 'Email Sender', 'Email Sender', 'manage_options', 'cem-email-sender', 'cem_email_sender_page' );
    add_submenu_page( 'cem-dashboard', 'Campaign Tracker', 'Campaign Tracker', 'manage_options', 'cem-campaign-tracker', 'cem_campaign_tracker_page' );
    add_submenu_page( 'cem-dashboard', 'Templates', 'Templates', 'manage_options', 'cem-templates', 'cem_templates_page' );
}

// Enqueue admin scripts and styles.
add_action( 'admin_enqueue_scripts', 'cem_admin_enqueue_scripts' );
function cem_admin_enqueue_scripts( $hook ) {
    // Load only on our plugin pages.
    if ( strpos( $hook, 'cem-' ) !== false ) {
        wp_enqueue_style( 'cem-admin-css', plugin_dir_url( __FILE__ ) . 'assets/css/admin.css' );
        wp_enqueue_script( 'cem-admin-js', plugin_dir_url( __FILE__ ) . 'assets/js/admin.js', array('jquery'), false, true );
        wp_localize_script( 'cem-admin-js', 'cem_ajax_obj', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    }
}

// On first open, check if user job title is set.
add_action( 'admin_init', 'cem_check_user_job_title' );
function cem_check_user_job_title() {
    $user_id = get_current_user_id();
    if ( ! get_user_meta( $user_id, 'cem_job_title', true ) ) {
        // Show a notice prompting the user to set their job title.
        add_action( 'admin_notices', 'cem_job_title_notice' );
    }
}
function cem_job_title_notice() {
    ?>
    <div class="notice notice-warning">
        <p><?php _e( 'Please set your job title for the Email Marketing plugin. <a href="' . admin_url( 'admin.php?page=cem-dashboard&set_job_title=1' ) . '">Click here to set it</a>.', 'cem' ); ?></p>
    </div>
    <?php
}

// Handle job title form submission.
add_action( 'admin_post_cem_set_job_title', 'cem_set_job_title' );
function cem_set_job_title() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'Unauthorized user', 'cem' ) );
    }
    if ( isset( $_POST['cem_job_title'] ) ) {
        update_user_meta( get_current_user_id(), 'cem_job_title', sanitize_text_field( $_POST['cem_job_title'] ) );
    }
    wp_redirect( admin_url( 'admin.php?page=cem-dashboard' ) );
    exit;
}
