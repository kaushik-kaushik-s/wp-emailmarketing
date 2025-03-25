<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function cem_email_sender_page() {
    global $wpdb;
    // Fetch templates.
    $templates_table = $wpdb->prefix . 'cem_templates';
    $templates = $wpdb->get_results( "SELECT * FROM $templates_table ORDER BY id DESC" );
    
    // Fetch WordPress users for sender selection.
    $users = get_users( array( 'fields' => array( 'ID', 'display_name', 'user_email' ) ) );
    ?>
    <div class="wrap">
        <h1><?php _e( 'Email Sender', 'cem' ); ?></h1>
        <form id="cem-email-sender-form" method="post" action="">
            <table class="form-table">
                <tr>
                    <th><?php _e( 'Select Template', 'cem' ); ?></th>
                    <td>
                        <select id="cem_template_select" name="template_id">
                            <option value=""><?php _e( 'Select a template', 'cem' ); ?></option>
                            <?php foreach ( $templates as $template ) : ?>
                                <option value="<?php echo esc_attr( $template->id ); ?>" data-event="<?php echo esc_attr( $template->event_name ); ?>" data-type="<?php echo esc_attr( $template->request_type ); ?>">
                                    <?php echo esc_html( $template->template_title ); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><?php _e( 'Select Sender', 'cem' ); ?></th>
                    <td>
                        <select id="cem_sender_select" name="sender_id">
                            <option value=""><?php _e( 'Select a sender', 'cem' ); ?></option>
                            <?php foreach ( $users as $user ) : 
                                $job_title = get_user_meta( $user->ID, 'cem_job_title', true );
                                ?>
                                <option value="<?php echo esc_attr( $user->ID ); ?>" data-jobtitle="<?php echo esc_attr( $job_title ); ?>">
                                    <?php echo esc_html( $user->display_name ); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            </table>
            <h2><?php _e( 'Email Preview', 'cem' ); ?></h2>
            <div id="cem_email_preview" style="border: 1px solid #ccc; padding: 10px;">
                <h3 id="cem_email_title"><?php _e( 'Email Title', 'cem' ); ?></h3>
                <div id="cem_email_content"><?php _e( 'Email content will be shown here.', 'cem' ); ?></div>
            </div>
            <h2><?php _e( 'Recipient Details', 'cem' ); ?></h2>
            <table class="form-table">
                <tr>
                    <th><?php _e( 'Company Name', 'cem' ); ?></th>
                    <td><input type="text" name="company_name" required /></td>
                </tr>
                <tr>
                    <th><?php _e( 'Company Email', 'cem' ); ?></th>
                    <td><input type="email" name="company_email" required /></td>
                </tr>
            </table>
            <?php submit_button( __( 'Send Email', 'cem' ) ); ?>
        </form>
    </div>
    <?php
}
