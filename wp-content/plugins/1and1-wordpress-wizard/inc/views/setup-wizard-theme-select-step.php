<?php
// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

class One_And_One_Theme_Selection_Step {

	static public function get_theme_selection( $site_type ) {

		add_thickbox();

		$current_theme_info = wp_get_theme();
		$current_theme_info->screenshot_url = $current_theme_info->get_screenshot();
		$current_theme_info->author = $current_theme_info->display( 'Author', false, true );

		$themes = self::get_available_themes( $site_type, $current_theme_info->slug );
		?>

		<script type="text/javascript">
			function showBox(id) {
				tb_show('', '#TB_inline?height=500&width=900&inlineId=' + id + '&modal=false', null);
			}
		</script>
		<form action="<?php echo esc_url( add_query_arg( array( 'setup_action' => 'choose_functionality' ) ) ); ?>" method="post">
			<!-- Add nonce-->
			<?php wp_nonce_field( 'choose_functionality' ) ?>
			<input type="hidden" name="sitetype" value="<?php echo esc_attr( $site_type ); ?>"/>

			<div class="wrap">

				<?php
				include( One_And_One_Wizard::get_views_dir_path() . 'setup-wizard-header.php' );
				One_And_One_Wizard_Header::get_wizard_header( 2 ); ?>

				<h3 class="clear"><?php esc_html_e( 'Step 2 - Selecting appearance', '1and1-wordpress-wizard' ) ?></h3>

				<p><?php esc_html_e( 'You can select the desired design here.', '1and1-wordpress-wizard' ) ?></p>
				<br/>

				<div>
					<div class="oneandone-theme-browser">

						<?php
						$popup_id = 'Popup';
						$theme_submit_name = 'theme[]';
						$theme_submit_value = esc_attr__( 'Keep Current Theme', '1and1-wordpress-wizard' );

						include_once( One_And_One_Wizard::get_views_dir_path() . 'setup-wizard-theme-item.php' );

						One_And_One_Theme_Item::get_theme_item( $current_theme_info, true, $popup_id, $theme_submit_name, $theme_submit_value );

						$index = 0;
						foreach ( $themes as $theme ) {
							$popup_id = 'Popup' . $index++;
							$theme_submit_name = 'theme[' . $theme->slug . ']';
							$theme_submit_value = esc_attr__( 'Select', '1and1-wordpress-wizard' );

							One_And_One_Theme_Item::get_theme_item( $theme, false, $popup_id, $theme_submit_name, $theme_submit_value );
						} ?>

						<br class="clear">
					</div>

					<br class="clear">
					<a href="<?php echo esc_url( admin_url( 'tools.php?page=1and1-wordpress-wizard' ) ); ?>"><?php esc_html_e( 'Back to the beginning', '1and1-wordpress-wizard' ); ?></a>
				</div>
			</div>
		</form>
	<?php
	}

	static private function get_available_themes( $site_type, $current_theme_slug ) {
		$catalog = new One_And_One_Catalog();
		$themes = $catalog->get_themes_by_site_type( $site_type );

		// remove current theme from theme list
		foreach ( $themes as $key => $theme ) {

			if ( $theme->slug == $current_theme_slug ) {
				unset( $themes->{ $key } );
				break;
			}
		}

		return $themes;
	}

}
