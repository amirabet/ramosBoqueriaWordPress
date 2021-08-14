<h1>Install your apps below</h1>

<div class="app-grid gs-clearfix">
    <?php
        $apps = array(
            'Horizontal Sharing Bar' => array(
                'file' => 'sharing-bar',
                'active' => $GS->is_active('sharing_bar'),
                'href' => $GS->gs_account().'/sites/gs-wordpress/groups/new?api_key='.$GS->api_key.'&amp;source=wordpress'.$GS->utms('sharing-bar'),
                'desc' => "Use one of our templates or design your own social sharing bar. Customize size, shape & colour and pick from 15 social networks."
            ),
            'Mobile Sharing Bar' => array(
                'file' => 'mobile-bar',
                'active' => $GS->is_active('mobile_bar'),
                'pro' => true,
                'href' => $GS->gs_account().'/sites/gs-wordpress/mobile_bar/new?api_key='.$GS->api_key.'&amp;source=wordpress'.$GS->utms('mobile-bar'),
                "desc" => "Mobile Web is one of the fastest growing platform both in traffic and shares. Don't miss out on the opportunity to boost your traffic with our slick mobile web sharing interface. No code needed."
            ),
            'Floating Sharing Bar' => array(
                'file' => 'floating-bar',
                'active' => $GS->is_active('floating_bar'),
                'href' => $GS->gs_account().'/sites/gs-wordpress/floating_bar/new?api_key='.$GS->api_key.'&amp;source=wordpress'.$GS->utms('floating-bar'),
                "desc" => "Use one of our templates or design your own floating sharing bar. Customize size, shape & placement and pick from 15 social networks."
            ),
            'Image Sharing' => array(
                'file' => 'image-sharing',
                'active' => $GS->is_active('image_sharing'),
                'pro' => true,
                'href' => $GS->gs_account().'/sites/gs-wordpress/image_sharing/new?api_key='.$GS->api_key.'&amp;source=wordpress'.$GS->utms('image-sharing'),
                "desc" => "Increase shares on images on your website. Great for media-based websites."
            ),
            'Copy Paste Share Tracking' => array(
                'file' => 'address-tracker',
                'active' => $GS->is_active('address_tracking'),
                'only_activate' => true,
                'href' => $GS->api_url('sites/activate/'.get_option('gs-api-key').'/address-tracker'),
                "desc" => "Don't lose track of shares made through copying and pasting an URL on the address bar to social networks, email or other platforms."
            ),
            'Native Sharing Bar' => array(
                'file' => 'native-bar',
                'active' => $GS->is_active('native_bar'),
                'href' => $GS->gs_account().'/sites/gs-wordpress/native_bar/new?api_key='.$GS->api_key.'&amp;source=wordpress'.$GS->utms('native-bar'),
                'desc' => "It doesn't get much more classic than this. Your native sharing buttons with tracking abilities. Great for those who want to keep it real."
            ),
            'Horizontal Follow Bar' => array(
                'file' => 'follow-bar',
                'new' => true,
                'active' => $GS->is_active('follow_bar'),
                'href' => $GS->gs_account().'/sites/gs-wordpress/follow_bar/new?api_key='.$GS->api_key.'&amp;source=wordpress'.$GS->utms('follow-bar'),
                'desc' => "Grow your follower base in Facebook, Twitter, Pinterest and more with these beautiful free follow buttons."
            ),
            'Floating Follow Bar' => array(
                'file' => 'floating-follow-bar',
                'new' => true,
                'active' => $GS->is_active('floating_follow_bar'),
                'href' => $GS->gs_account().'/sites/gs-wordpress/follow_floating_bar/new?api_key='.$GS->api_key.'&amp;source=wordpress'.$GS->utms('floating-follow-bar'),
                'desc' => "Grow your follower base in Facebook, Twitter, Pinterest and more with these beautiful free follow buttons."
            ),
            'Mobile Follow Bar' => array(
                'file' => 'mobile-follow-bar',
                'pro' => true,
                'new' => true,
                'active' => $GS->is_active('mobile_follow_bar'),
                'href' => $GS->gs_account().'/sites/gs-wordpress/follow_mobile_bar/new?api_key='.$GS->api_key.'&amp;source=wordpress'.$GS->utms('mobile-follow-bar'),
                'desc' => "Don't miss out on the opportunity to convert mobile visitors into brand followers with our mobile follow buttons."
            ),
            'Welcome Bar' => array(
                'file' => 'welcome-bar',
                'active' => $GS->is_active('welcome_bar'),
                'href' => $GS->gs_account().'/sites/gs-wordpress/welcome_bars/new?api_key='.$GS->api_key.'&amp;source=wordpress'.$GS->utms('welcome-bar'),
                "desc" => "Easily lead your visitors to a specific link. Great to generate conversions, engage with promotions and increase traffic. No code needed."
            ),
            'Subscriber Bar' => array(
                'file' => 'subscriber-bar',
                'active' => $GS->is_active('subscriber_bar'),
                'pro' => true,
                'href' => $GS->gs_account().'/sites/gs-wordpress/subscribe_bars/new?api_key='.$GS->api_key.'&amp;source=wordpress'.$GS->utms('subscriber-bar'),
                "desc" => "Easily capture emails from your visitors by providing them with an engaging top bar. Export data to your favorite CRM or e-Mail marketing software."
            ),
            'Big Total Shares Horizontal' => array(
                'file' => 'social-bar-big-counter',
                'active' => $GS->is_active('social_bar_big_counter'),
                'href' => $GS->gs_account().'/sites/gs-wordpress/big_counter_sharing_bar/new?api_key='.$GS->api_key.'&amp;source=wordpress'.$GS->utms('social-bar-big-counter'),
                'pro' => true,
                'desc' => "Increase engagement by showing the total number of shares in a big counter on the left of your horizontal share bar."
            ),
            'Big Total Shares Floating' => array(
                'file' => 'floating-bar-big-counter',
                'active' => $GS->is_active('floating_bar_big_counter'),
                'href' => $GS->gs_account().'/sites/gs-wordpress/big_counter_floating_bar/new?api_key='.$GS->api_key.'&amp;source=wordpress'.$GS->utms('floating-bar-big-counter'),
                'pro' => true,
                'desc' => "Increase engagement by showing the total number of shares in a big counter on top of your floating share bar."
            ),
            'Custom Sharing Actions' => array(
                'file' => 'custom-actions',
                'active' => $GS->is_active('custom_actions'),
                'href' => $GS->gs_account().'/sites/gs-wordpress/elements/new?api_key='.$GS->api_key.'&amp;source=wordpress'.$GS->utms('custom-actions'),
                'desc' => "Sometimes we need to say more than a simple 'Like'. Here you'll find more than 50 custom stories such as Awesome, Wish or Love."
            ),
            'Price Alert' => array(
                'file' => 'price-alert',
                'active' => $GS->is_active('price_alert'),
                'pro' => true,
                'href' => $GS->gs_account().'/sites/gs-wordpress/price_alerts/new?api_key='.$GS->api_key.'&amp;source=wordpress'.$GS->utms('price-alert'),
                'desc' => "Allow your visitors to get notified when a price drop occurs on a product they want to purchase. Increase sales and fight cart abandonment."
            ),
        );

        foreach($apps as $app => $settings):
    ?>

    <div class="app-link-wrapper">
        <?php if($settings['active']): ?>
            <div class="app-status gs-tooltip">
                <i class="fa fa-check"></i>
                <div>This app is installed</div>
            </div>
        <?php else: ?>
            <?php if( isset($settings['pro']) && $settings['pro'] ): ?>
                <a href="<?php echo $GS->gs_account() ?>/sites/gs-wordpress/billing/select_tier?api_key=<?php echo $GS->api_key ?>&amp;source=wordpress" target="_blank" class="app-link-plan gs-clearfix text-right gs-tooltip plan-pro">
                    Pro <div>This app is only available in the <strong>Pro plan</strong></div>
                </a>
            <?php endif; ?>
        <?php endif; ?>

        <div class="app-link">
            <?php if(isset($settings['new'])): ?>
                <div class="app-badge badge-special">New</div>
            <?php endif; ?>
            <div class="app-image">
                <img src="<?php echo plugins_url( '../img/apps/'.$settings['file'].'.png', __FILE__ ) ?>" alt="">
            </div>
            <div class="app-link-info">
                <p class="app-title"><?php echo $app ?></p>
                <p><?php echo $settings['desc'] ?></p>
            </div>
            <div class="app-link-buttons app-type-<?php echo ($settings['active'] && !isset($settings['only_activate'])) ? 'double' : 'simple' ?>">
                <?php if(!isset($settings['only_activate']) || ( isset($settings['only_activate']) && !$settings['active'] )): ?>
                    <a href="<?php echo $settings['href'] ?>" target="<?php echo ($settings['only_activate'] ? '' : '_blank') ?>" class="getsocial-tab <?php echo ($settings['only_activate'] ? 'only-activate' : '') ?>">
                        <?php echo ($settings['active']) ? 'Edit App' : 'Install App' ?>

                        <?php if($app == 'Price Alert' && !$settings['active']): ?>
                            <span class="gs-tooltip">
                                <img src="<?php echo plugins_url('../img/woocommerce.png', __FILE__ ) ?>" alt="WooCommerce">
                                <div>This App requires WooCommerce to be installed</div>
                            </span>
                       <?php endif; ?>
                    </a>
                <?php endif; ?>
                <?php if($settings['active']): ?>
                    <a href="#" class="stop deactivate" data-disable-app="<?php echo $GS->api_url('sites/disable/'.get_option('gs-api-key').'/'.$settings['file']) ?>">
                        Deactivate
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php endforeach; ?>
</div>

<footer>
    <a id="backToTop" href="javascript:void(0)" class="gs-button primary"><i class="fa fa-angle-up"></i> Back to Top</a>
    <?php if(!$GS->is_pro()): ?>
    &nbsp;&nbsp;&nbsp;&nbsp;or&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="<?php echo $GS->gs_account() ?>/sites/gs-wordpress/billing/select_tier?api_key=<?php echo $GS->api_key ?>&amp;source=wordpress" target="_blank" class="gs-button plan-pro">Upgrade to Pro</a>
    <?php endif; ?>
</footer>
