<h2><i class="fa fa-cog"></i>Settings</h2>

<form id="config-form" method="post" action="options.php">
    <?php settings_fields( 'getsocial-gs-settings' ); ?>
    <?php do_settings_sections( 'getsocial-gs-settings' ); ?>
    <div class="form-content">
        <label class="control">GetSocial API KEY</label>

        <div class="api-key-field">
            <input type="text" name="gs-api-key" size="60" value="<?php echo get_option('gs-api-key'); ?>" />
        </div>
    </div>

    <?php if(get_option('gs-api-key') != ''): ?>

    <!-- <div class="form-content">
        <label class="control">Language</label>
        <div class="languages">
            <select name="gs-lang">
                <option value="en" <?php //echo (get_option('gs-lang') == 'en' ? 'selected="selected"' : '') ?>>English</option>
                <option value="es" <?php //echo (get_option('gs-lang') == 'es' ? 'selected="selected"' : '') ?>>Spanish</option>
                <option value="pt_PT" <?php //echo (get_option('gs-lang') == 'pt_PT' ? 'selected="selected"' : '') ?>>Portuguese (Portugal)</option>
                <option value="pt_BR" <?php //echo (get_option('gs-lang') == 'pt_BR' ? 'selected="selected"' : '') ?>>Portuguese (Brazil)</option>
            </select>
        </div>
    </div> -->

    <?php endif; ?>

    <div class="bar-active">
        <?php submit_button('Save Changes'); ?>
        <div class="cl"></div>
    </div>
</form>
