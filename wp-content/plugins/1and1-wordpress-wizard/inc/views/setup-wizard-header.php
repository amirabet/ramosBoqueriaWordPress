<?php
// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}


class One_And_One_Wizard_Header {

	static public function get_wizard_header ( $wizard_step ) {
		?>

		<h2><?php esc_html_e( '1&1 WP Wizard', '1and1-wordpress-wizard' )?></h2>

		<div class="clear oneandone-setup-progress">
			<ul>
				<li><span class="<?php echo ( $wizard_step == 1 ) ? 'active' : ''; ?> oneandone-progress-step-number">1</span><span
						class="oneandone-progress-step-title"><?php esc_html_e( 'Website Type', '1and1-wordpress-wizard' ); ?></span>
					<hr class="oneandone-horizontal-line"/>
				</li>
				<li><span class="<?php echo ( $wizard_step == 2 ) ? 'active' : ''; ?> oneandone-progress-step-number">2</span><span
						class="oneandone-progress-step-title"><?php esc_html_e( 'Appearance', '1and1-wordpress-wizard' ); ?></span>
					<hr class="oneandone-horizontal-line"/>
				</li>
				<li><span class="<?php echo ( $wizard_step == 3 ) ? 'active' : ''; ?> oneandone-progress-step-number">3</span><span
						class="oneandone-progress-step-title"><?php esc_html_e( 'Plugins', '1and1-wordpress-wizard' ); ?></span>
				</li>
			</ul>
		</div>

	<?php
	}
}