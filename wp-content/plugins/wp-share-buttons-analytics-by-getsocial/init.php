<div id="gs-master-wrapper">
    <?php include('tmpl/header.php'); ?>

    <?php include('tmpl/alerts.php'); ?>

    <main data-href="<?php echo $GS->api_url ?>sites/<?php echo get_option('gs-api-key') ?>">

        <?php if( isset($_GET['settings-updated']) && $_GET['settings-updated'] == true): ?>

            <div class="notification-bar gs-alert success gs-clearfix gs-small">
                <div class="gs-col-16">
                    <p>Preferences updated successfully</p>
                </div>
            </div>

        <?php endif; ?>

        <?php if(get_option('gs-api-key') == ''): ?>
            <div class="title-wrapper">
                <h1>Welcome and thanks for downloading <span>GetSocial’s Share Buttons & Social Media App Store</span></h1>
                <p>To get started click on the button below to automatically activate your GetSocial account.</strong></p>
            </div>

            <div class="account-info gs-form gs-small">
                <div class="form-content">
                    <div class="field-group">
                        <div class="field-label no-desc">
                            <label for="site-name">URL</label>
                        </div>
                        <div class="field-input">
                            <?php echo get_option('siteurl') ?>
                        </div>
                    </div>
                    <div class="field-group">
                        <div class="field-label no-desc">
                            <label for="site-name">Email</label>
                        </div>
                        <div class="field-input">
                            <?php echo get_option('admin_email') ?>
                        </div>
                    </div>
                </div>
                <div class="form-button-group">
                    <a href="<?php echo $GS->gs_account() ?>/api/v1/sites/create?source=wordpress&amp;email=<?php echo get_option('admin_email') ?>&amp;url=<?php echo get_option('siteurl') ?>" class="gs-button gs-big success create-gs-account"><i class="fa fa-check"></i> Activate your account</a>
                    <span class="loading-create gs-button success trans border gs-big">
                        <i class="fa fa-refresh rotate"></i> Activating Account...
                    </span>
                </div>
            </div>

            <div class="notification-bar gs-alert success gs-clearfix gs-small hidden">
                <div class="gs-col-16">
                    <p><i class="fa fa-check"></i> <strong>Congratulations!</strong> You are ready to start in 3..2..1..</p>
                </div>
            </div>

            <div class="notification-bar gs-alert error gs-clearfix gs-small hidden">
                <div class="gs-col-16">
                    <p></p>
                </div>
            </div>

            <div class="gs-small">
                <form id="api-key-form" method="post" class="api-key gs-form hidden" action="options.php">
                    <?php settings_fields( 'getsocial-gs-settings' ); ?>
                    <?php do_settings_sections( 'getsocial-gs-settings' ); ?>

                    <div class="form-content">
                        <div class="field-clean">
                            <div class="field-input">
                                <p>Please go to your Getsocial Account and get your API KEY in the site options page.</p>
                                <input id="gs-api-key" type="text" name="gs-api-key" size="60" value="" />
                            </div>
                        </div>
                    </div>
                    <div class="form-button-group">
                        <input type="submit" class="gs-button success" value="Save Changes" />
                    </div>
                </form>
            </div>

        <?php else: ?>

            <?php if( !isset($_GET['tab']) ): ?>

                <?php if(!$GS->is_pro() && get_option('gs-alert-msg')): ?>
                <div class="gs-alert cta default gs-clearfix simple">
                    <div class="gs-col-16">
                        <p class="alert-title"><?php echo get_option('gs-alert-msg') ?></p>
                        <a href="<?php echo $GS->gs_account() ?>/sites/gs-wordpress/billing/select_tier?api_key=<?php echo $GS->api_key ?>&amp;source=wordpress<?php echo get_option('gs-alert-utm') ?>" target="_blank" class="gs-button cta pro"><?php echo get_option('gs-alert-cta') ?></a>
                    </div>
                    <!-- <a href="javascript:void(0)" class="close"><i class="fa fa-times"></i></a> -->
                </div>
                <?php endif; ?>

                <?php if( isset($_GET['update']) ||  isset($_GET['delete'])  ): ?>

                    <div class="notification-bar gs-alert global success gs-clearfix simple gs-small">
                        <div class="gs-col-16">
                            <p class="alert-title">App <?php echo isset($_GET['update']) ? 'updated' : 'deactivated' ?> with success</p>
                        </div>
                    </div>

                <?php endif; ?>

                <?php include('tmpl/apps.php') ?>

            <?php else: ?>


                <?php include('tmpl/apps/'.$_GET['tab'].'.php') ?>
            <?php endif; ?>


        <?php endif; ?>

    </main>

    <div id="settings-modal" class="modal-wrapper hide">
        <div class="gs-modal">
            <div class="modal-title">
                <p class="title">Settings</p>
            </div>
            <form id="config-form" method="post" action="options.php" class="gs-form">
                <?php settings_fields( 'getsocial-gs-settings' ); ?>
                <?php do_settings_sections( 'getsocial-gs-settings' ); ?>

                <!-- <div class="notification-bar gs-alert error gs-clearfix gs-small">
                    <div class="gs-col-16">
                        <p>Preferences updated successfully</p>
                    </div>
                </div> -->

                <div class="form-content">
                    <div class="field-group">
                        <div class="field-label no-desc">
                            <label for=""><br>API KEY</label>
                        </div>
                        <div class="field-input">
                            <input type="text" name="gs-api-key" size="60" value="<?php echo get_option('gs-api-key'); ?>" />
                        </div>
                    </div>
                    <div class="field-group">
                        <div class="field-label">
                            <label for="">Where to display sharing bars</label>
                        </div>
                        <div class="field-input">
                            <p>
                                Choose where to have your apps displayed. <strong>For now this is limited to Horizontal Sharing Bars</strong>
                            </p>

                            <div class="checkbox-list">
                                <label><input type="radio" name="gs-place" value="place-posts" <?php echo (get_option('gs-place') == 'place-posts') ? 'checked' : '' ?> />Only Posts</label>
                            </div>
                            <div class="checkbox-list">
                                <label><input type="radio" name="gs-place" value="place-pages" <?php echo (get_option('gs-place') == 'place-pages') ? 'checked' : '' ?>/>Only Pages</label>
                            </div>
                            <div class="checkbox-list">
                                <label><input type="radio" name="gs-place" value="place-all" <?php echo (get_option('gs-place') == 'place-all' || get_option('gs-place') == null) ? 'checked' : '' ?>/>Pages & Posts</label>
                            </div>
                            <div class="checkbox-list">
                                <label><input type="radio" name="gs-place" value="only-shortcodes" <?php echo (get_option('gs-place') == 'only-shortcodes') ? 'checked' : '' ?> />None. I will use shortcodes.</label>
                            </div>
                        </div>
                    </div>
                    <div class="field-group">
                        <div class="field-label">
                            <label for="">Where to display follow bar</label>
                        </div>
                        <div class="field-input">
                            <p>
                                Choose where to have your apps displayed.
                            </p>

                            <div class="checkbox-list">
                                <label><input type="radio" name="gs-place-follow" value="place-posts" <?php echo (get_option('gs-place-follow') == 'place-posts') ? 'checked' : '' ?> />Only Posts</label>
                            </div>
                            <div class="checkbox-list">
                                <label><input type="radio" name="gs-place-follow" value="place-pages" <?php echo (get_option('gs-place-follow') == 'place-pages') ? 'checked' : '' ?>/>Only Pages</label>
                            </div>
                            <div class="checkbox-list">
                                <label><input type="radio" name="gs-place-follow" value="place-all" <?php echo (get_option('gs-place-follow') == 'place-all' || get_option('gs-place-follow') == null) ? 'checked' : '' ?>/>Pages & Posts</label>
                            </div>
                            <div class="checkbox-list">
                                <label><input type="radio" name="gs-place-follow" value="only-shortcodes" <?php echo (get_option('gs-place-follow') == 'only-shortcodes') ? 'checked' : '' ?> />None. I will use shortcodes.</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-button-group">
                    <input type="submit" value="Save Settings" class="gs-button success">
                    <a href="javascript:void(0)" class="gs-button error trans modal-close">Cancel</a>
                </div>
            </form>
        </div>
        <div class="modal-cover modal-close"></div>
    </div>
</div>

<script>
// Include the UserVoice JavaScript SDK (only needed once on a page)
UserVoice=window.UserVoice||[];(function(){var uv=document.createElement('script');uv.type='text/javascript';uv.async=true;uv.src='//widget.uservoice.com/hizF2VOh3oNKSWQxuKYjg.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(uv,s)})();

// Set colors
UserVoice.push(['set', {
    accent_color: '#448dd6',
    trigger_color: 'white',
    trigger_background_color: '#e2753a'
}]);

// Identify the user and pass traits
// To enable, replace sample data with actual user traits and uncomment the line
UserVoice.push(['identify', {
    email: '<?php echo get_option('admin_email') ?>'
}]);

// Add default trigger to the bottom-right corner of the window:
UserVoice.push(['addTrigger', { trigger_position: 'bottom-right' }]);

UserVoice.push(['addTrigger', '#support']);

// Autoprompt for Satisfaction and SmartVote (only displayed under certain conditions)
// UserVoice.push(['autoprompt', {}]);
</script>
