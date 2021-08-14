<?php
// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

class One_And_One_Plugin_Installation_Progress {

	static public function get_progress_page ( $theme_id, $plugin_ids ) {
		?>

		<!DOCTYPE html>
		<html>
		<head>
			<?php
			wp_print_styles('colors');
			wp_print_scripts( 'jquery' );
			?>

			<link rel="stylesheet" href="<?php echo One_And_One_Wizard::get_css_url() . 'welcome-panel.css'; ?>" type="text/css" media="all">
			<link rel="stylesheet" href="<?php echo One_And_One_Wizard::get_css_url() . 'install-progress.css'; ?>" type="text/css" media="all">
		</head>

		<body class="oneandone-install-progress wp-core-ui ">

		<?php
		include_once( One_And_One_Wizard::get_inc_dir_path() . 'batch-installer.php' );

		$catalog   = new One_And_One_Catalog();
		$site_type = sanitize_text_field( $_GET[ 'sitetype' ] );

		if ( isset( $theme_id ) ) {
			$theme = $catalog->get_theme_by_id( $site_type, $theme_id );
		} else {
			$theme = null;
		}

		$plugins      = $catalog->get_plugins_by_ids( $site_type, array_filter( $plugin_ids ) );
		$callback_url = wp_nonce_url( $_SERVER[ 'REQUEST_URI' ], 'installation-progress' );

		$batch_installer = new One_And_One_Batch_Installer( $theme, $plugins, $callback_url, null );
		$batch_installer->setup_plugins_and_theme();

		?>

		<script>
		jQuery("html, body").scrollTop(jQuery(document).height());
		//jQuery("html, body").animate({ scrollTop: jQuery(document).height() }, "slow");
		</script

		</body>
		</html>

	<?php
	}
}