<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function cem_templates_page() {
    global $wpdb;
    $templates_table = $wpdb->prefix . 'cem_templates';

    // Process add/edit form submission.
    if ( isset( $_POST['cem_template_submit'] ) ) {
        $template_title   = sanitize_text_field( $_POST['template_title'] );
        $event_name       = sanitize_text_field( $_POST['event_name'] );
        $request_type     = sanitize_text_field( $_POST['request_type'] );
        $template_content = wp_kses_post( $_POST['template_content'] );

        if ( isset( $_POST['template_id'] ) && ! empty( $_POST['template_id'] ) ) {
            // Update existing template.
            $template_id = intval( $_POST['template_id'] );
            $wpdb->update( $templates_table, array(
                'template_title'   => $template_title,
                'event_name'       => $event_name,
                'request_type'     => $request_type,
                'template_content' => $template_content,
            ), array( 'id' => $template_id ) );
        } else {
            // Insert new template.
            $wpdb->insert( $templates_table, array(
                'template_title'   => $template_title,
                'event_name'       => $event_name,
                'request_type'     => $request_type,
                'template_content' => $template_content,
            ) );
        }
    }

    // Handle deletion.
    if ( isset( $_GET['delete_template'] ) ) {
        $template_id = intval( $_GET['delete_template'] );
        $wpdb->delete( $templates_table, array( 'id' => $template_id ) );
    }

    // Fetch all templates.
    $templates = $wpdb->get_results( "SELECT * FROM $templates_table ORDER BY id DESC" );
    ?>
    <div class="wrap">
        <h1><?php _e( 'Manage Templates', 'cem' ); ?></h1>
        <h2><?php _e( 'Add / Edit Template', 'cem' ); ?></h2>
        <form method="post" action="">
            <input type="hidden" name="template_id" value="<?php echo isset( $_GET['edit_template'] ) ? intval( $_GET['edit_template'] ) : ''; ?>">
            <table class="form-table">
                <tr>
                    <th><?php _e( 'Template Title', 'cem' ); ?></th>
                    <td><input type="text" name="template_title" required /></td>
                </tr>
                <tr>
                    <th><?php _e( 'Event Name', 'cem' ); ?></th>
                    <td><input type="text" name="event_name" required /></td>
                </tr>
                <tr>
                    <th><?php _e( 'Request Type', 'cem' ); ?></th>
                    <td>
                        <select name="request_type" required>
                            <option value="Partnership"><?php _e( 'Partnership', 'cem' ); ?></option>
                            <option value="Sponsorship"><?php _e( 'Sponsorship', 'cem' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><?php _e( 'Template Content', 'cem' ); ?></th>
                    <td>
                        <textarea name="template_content" rows="10" cols="50" required placeholder="[Company Name], [Placeholder 2], [Sender Name], [Sender Role]"></textarea>
                    </td>
                </tr>
            </table>
            <?php submit_button( __( 'Save Template', 'cem' ), 'primary', 'cem_template_submit' ); ?>
        </form>
        <h2><?php _e( 'Existing Templates', 'cem' ); ?></h2>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e( 'ID', 'cem' ); ?></th>
                    <th><?php _e( 'Title', 'cem' ); ?></th>
                    <th><?php _e( 'Event Name', 'cem' ); ?></th>
                    <th><?php _e( 'Request Type', 'cem' ); ?></th>
                    <th><?php _e( 'Actions', 'cem' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if ( $templates ) : ?>
                    <?php foreach ( $templates as $template ) : ?>
                        <tr>
                            <td><?php echo esc_html( $template->id ); ?></td>
                            <td><?php echo esc_html( $template->template_title ); ?></td>
                            <td><?php echo esc_html( $template->event_name ); ?></td>
                            <td><?php echo esc_html( $template->request_type ); ?></td>
                            <td>
                                <a href="<?php echo admin_url( 'admin.php?page=cem-templates&edit_template=' . $template->id ); ?>"><?php _e( 'Edit', 'cem' ); ?></a> |
                                <a href="<?php echo admin_url( 'admin.php?page=cem-templates&delete_template=' . $template->id ); ?>" onclick="return confirm('Are you sure?');"><?php _e( 'Delete', 'cem' ); ?></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5"><?php _e( 'No templates found.', 'cem' ); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}
