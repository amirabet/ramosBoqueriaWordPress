<?php

// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

include_once( 'setup-tool-catalog.php' );
include_once( 'install-config.php' );
include_once( 'theme-install-config.php' );

class One_And_One_Setup_Installer_Dispatcher {

	public function dispatcher_installer_action() {
		if ( isset( $_GET[ 'setup_action' ] ) && 'installation-progress' == $_GET[ 'setup_action' ] ) {

			wp_enqueue_script( 'jquery' );

			if ( isset( $_GET[ 'theme' ] ) ) {
				$theme_id = $_GET[ 'theme' ];
			}

			//check nonce
			check_admin_referer( 'installation-progress' );

			if ( isset( $_GET[ 'plugins' ] ) ) {
				$plugin_ids = explode( ',', $_GET[ 'plugins' ] );
			} else {
				$plugin_ids = array();
			}

			include_once( One_And_One_Wizard::get_views_dir_path() . 'installer-progress.php' );

			One_And_One_Plugin_Installation_Progress::get_progress_page( $theme_id, $plugin_ids );

			die();
		}
	}

}