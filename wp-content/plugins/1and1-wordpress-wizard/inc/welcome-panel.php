<?php

// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

class One_And_One_Setup_Welcome_Panel {

	public function welcome_panel_message() {
		// Load styling
		wp_enqueue_style('1and1-wizard-welcome');

		?>

		<div id="oneandone-welcome-panel" class="updated welcome-panel oneandone-setup-panel">
			<div class="welcome-panel-content">
				<div class="oneandone-setup-image"></div>
				<div class="oneandone-setup-message">
					<h3><?php esc_html_e( '1&1 WP Wizard', '1and1-wordpress-wizard' )?></h3>
					<h2><?php esc_html_e( 'Create your own WordPress website in just a few steps.', '1and1-wordpress-wizard' )?></h2>
					<p><?php esc_html_e( '1&1 WP Wizard enables you to get a quick start in WordPress with selected themes and plugins.', '1and1-wordpress-wizard' )?></p>

					<div class="oneandone-setup-links">
						<a href="<?php echo esc_url( admin_url( 'tools.php?page=1and1-wordpress-wizard' ) ); ?>" class="button button-primary button-hero oneandone-welcome-panel-get-started">
							<?php esc_html_e( 'Get Started', '1and1-wordpress-wizard' ); ?>
						</a>
						<a id="oneandone-welcome-panel-dismiss">
							<?php esc_html_e( 'Dismiss', '1and1-wordpress-wizard' ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			jQuery(document).ready(function ($) {
				$('#oneandone-welcome-panel-dismiss').on('click', function (evt) {
					evt.preventDefault();

					$.post(ajaxurl, {
						action: 'oao_dismiss_welcome_panel'
					});

					$("#oneandone-welcome-panel").fadeOut();
				});
			});
		</script>
	<?php

	}

}