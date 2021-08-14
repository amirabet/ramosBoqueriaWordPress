
<div class="title-wrapper">
    <!-- <div class="gs-alert global primary gs-clearfix simple">
        <div class="gs-col-16">
            <p class="alert-title">Use this COUPON <span class="success">33OFF_4LIFE</span> for a 33% discount forever!</p>
            <p><strong>This offer is limited to the first 100 (only 11 left)</strong></p>
            <a href="<?php echo $GS->gs_account() ?>/sites/gs-wordpress/billing/select_tier?api_key=<?= $GS->api_key ?>&amp;source=wordpress" class="gs-button cta pro" target="_blank">Upgrade to Pro</a>
        </div>
        <a href="javascript:void(0)" class="close"><i class="fa fa-times"></i></a>
    </div> -->

    <div id="dashboard-analytics" class="gs-clearfix" data-graph-api="<?php echo $GS->api_url('sites/analytics/'.get_option('gs-api-key')) ?>">
        <div class="dashboard-tile">
            <div class="visits">
                <a href="<?php echo $GS->gs_account().'/sites/gs-wordpress/analytics/dashboard?api_key='.$GS->api_key.'&amp;source=wordpress' ?>" target="_blank" class="overlay tile-overlay">
                    <span>View details</span>
                </a>
                Visits
                <span class="small total_visits">(No data to be displayed)</span>
            </div>
        </div>
        <div class="dashboard-tile">
            <div class="shares">
                <a href="<?php echo $GS->gs_account().'/sites/gs-wordpress/analytics/dashboard?api_key='.$GS->api_key.'&amp;source=wordpress' ?>" target="_blank" class="overlay tile-overlay">
                    <span>View details</span>
                </a>
                Shares
                <span class="small total_shares">(No data to be displayed)</span>
            </div>
        </div>
        <div class="dashboard-tile">
            <div class="leads">
                <a href="<?php echo $GS->gs_account().'/sites/gs-wordpress/analytics/dashboard?api_key='.$GS->api_key.'&amp;source=wordpress' ?>" target="_blank" class="overlay tile-overlay">
                    <span>View details</span>
                </a>
                Leads
                <span class="small total_leads">(No data to be displayed)</span>
            </div>
        </div>
    </div>
    <div class="dashboard-info">
        <?php $from = date('d-m-Y', strtotime('-8 days')) ?>
        <?php $to = date('d-n-Y', strtotime('-1 days')) ?>
        Stats collected from <strong><?php echo $from ?></strong> to <strong><?php echo $to ?></strong>
        <a href="<?php echo $GS->gs_account().'/sites/gs-wordpress/analytics/dashboard?api_key='.$GS->api_key.'&amp;source=wordpress' ?>" target="_blank">View detailed Stats</a>
    </div>
</div>
