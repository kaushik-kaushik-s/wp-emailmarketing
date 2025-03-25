<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function cem_dashboard_page() {
    ?>
    <div class="wrap">
        <?php
        // If user needs to set job title, show the form.
        if ( isset( $_GET['set_job_title'] ) ) : ?>
            <h1><?php _e( 'Set Your Job Title', 'cem' ); ?></h1>
            <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
                <input type="hidden" name="action" value="cem_set_job_title">
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php _e( 'Job Title', 'cem' ); ?></th>
                        <td><input type="text" name="cem_job_title" value="" required /></td>
                    </tr>
                </table>
                <?php submit_button( __( 'Save Job Title', 'cem' ) ); ?>
            </form>
        <?php else : ?>
            <h1><?php _e( 'Email Marketing Dashboard', 'cem' ); ?></h1>
            <p>Welcome to the Email Marketing plugin dashboard. Use the submenu items to navigate through Logs, Email Sender, Campaign Tracker, and Templates.</p>
        <?php endif; ?>
    </div>
    <?php
}
