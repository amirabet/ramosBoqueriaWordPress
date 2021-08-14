<?php
if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
}

// $GETSOCIAL_URL = 'http://getsocial.io';
// $GETSOCIAL_URL = 'http://localhost:3000';

$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$GS = new GS(   get_option('gs-api-key'),
                get_option('gs-identifier'),
                get_option('gs-lang'));

$site_info = $GS->refreshSite();

wp_register_style( 'getsocial-style', plugins_url('../css/getsocial-style.css?v=2.7', __FILE__) );
wp_enqueue_style( 'getsocial-style' );

wp_register_script( 'plugin', plugins_url('../js/plugin.js', __FILE__) );

wp_enqueue_script( 'plugin' );

?>


<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400italic,600,700|Raleway:800">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" media="all">

<script type="text/javascript">
    function toggle(id) {
        var e = document.getElementById(id);
        e.style.display = (e.style.display == 'block' ? 'none' : 'block');
    }
</script>

<header>
    <div id="main-subnav" class="gs-clearfix">
        <a id="logo-wrapper" href="http://getsocial.io" target="_blank">
            <img src="<?php echo plugins_url('../img/getsocial-app-logo.png', __FILE__ ) ?>" alt="GetSocial">
        </a>
        <div>
            <ul class="gs-clearfix">
                <?php if(get_option('gs-api-key') != ''): ?>

                    <!-- <li class="nav-submenu-link">
                        <a id="api-key" href="javascript:void(0)">
                            <span class="idle">API Key</span><span class="active"><i class="fa fa-pencil"></i> Edit</span> <?php echo get_option('gs-api-key'); ?>
                        </a>
                    </li> -->

                    <li class="nav-submenu-link">
                        <a href="<?php echo $GS->gs_account().'/sites/gs-wordpress/analytics/dashboard?api_key='.$GS->api_key.'&amp;source=wordpress' ?>" target="_blank"><i class="fa fa-bar-chart"></i> Social Analytics</i></a>
                    </li>

                    <li class="nav-submenu-link">
                        <a id="settings" href="javascript:void(0)">
                            <i class="fa fa-cogs"></i> Settings
                        </a>
                    </li>

                <?php endif; ?>
                <li class="nav-submenu-link">
                    <a href="javascript:void(0)"><i class="fa fa-question-circle"></i> Help & Support <i class="fa fa-angle-down"></i></a>
                    <div class="nav-submenu-wrapper">
                        <ul class="nav-submenu">
                            <li>
                                <a href="#" id="support">Email Support</a>
                            </li>
                            <li>
                                <a href="http://feedback.getsocial.io/" target="_blank"><i class="fa fa-life-bouy"></i> Documentation</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <?php if(get_option('gs-api-key') != '' && !$GS->is_pro()): ?>
                    <li id="user-nav" class="nav-submenu-link">
                        <?php if(get_option('gs-ask-review')): ?>
                            <a href="https://wordpress.org/support/view/plugin-reviews/wp-share-buttons-analytics-by-getsocial" target="_blank" class="gs-cta gs-button trans special">Support us with a 5 <i class="fa fa-star"></i> Review!</a>
                        <?php endif; ?>
                        <a href="<?php echo $GS->gs_account() ?>/sites/gs-wordpress/billing/select_tier?api_key=<?php echo $GS->api_key ?>&amp;source=wordpress<?php echo $GS->utms('pro_header') ?>" target="_blank" class="gs-button plan-pro">Upgrade to PRO</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</header>
