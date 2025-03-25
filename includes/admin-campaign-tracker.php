<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function cem_campaign_tracker_page() {
    global $wpdb;
    // Get distinct campaign IDs from the tracker table.
    $tracker_table = $wpdb->prefix . 'cem_campaign_tracker';
    $campaigns = $wpdb->get_results( "SELECT DISTINCT campaign_id FROM $tracker_table" );
    ?>
    <div class="wrap">
        <h1><?php _e( 'Campaign Tracker', 'cem' ); ?></h1>
        <?php foreach ( $campaigns as $campaign ) : 
            // Fetch companies for this campaign.
            $companies = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $tracker_table WHERE campaign_id = %d", $campaign->campaign_id ) );
            ?>
            <h2><?php _e( 'Campaign ID: ', 'cem' ); echo esc_html( $campaign->campaign_id ); ?></h2>
            <div class="cem-kanban-board">
                <?php 
                $stages = array( 'Contacted', 'Negotiation: Responded', 'Negotiation: Email Sent', 'Accepted', 'Rejected' );
                foreach ( $stages as $stage ) :
                ?>
                <div class="cem-kanban-column" data-stage="<?php echo esc_attr( $stage ); ?>">
                    <h3><?php echo esc_html( $stage ); ?></h3>
                    <ul>
                    <?php foreach ( $companies as $company ) : 
                        if ( $company->stage == $stage ) : ?>
                            <li data-id="<?php echo esc_attr( $company->id ); ?>">
                                <?php echo esc_html( $company->company_name . ' (' . $company->company_email . ')' ); ?>
                            </li>
                        <?php endif; 
                    endforeach; ?>
                    </ul>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
}
