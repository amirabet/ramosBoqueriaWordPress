<?php

// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

include_once( 'setup-tool-catalog.php' );
include_once( 'install-config.php' );
include_once( 'theme-install-config.php' );

class One_And_One_Setup_Wizard_Dispatcher {

	public function dispatch_wizard_actions() {

		// Check user permissions
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Sorry, you do not have permission to access the 1&1 WP Wizard.', '1and1-wordpress-wizard' ) );
		}

		// Load styling
		wp_enqueue_style('1and1-wp-wizard');

		// Before the use can choose the appearance, a sitetype needs to be selected!
		if ( $this->is_action( 'choose_appearance' ) && isset( $_POST[ 'sitetype' ] ) ) {

			$site_type = key( $_POST[ 'sitetype' ] );

			//check nonce
			check_admin_referer( 'choose_appearance' );

			include_once( One_And_One_Wizard::get_views_dir_path() . 'setup-wizard-theme-select-step.php' );

			One_And_One_Theme_Selection_Step::get_theme_selection( $site_type );
		}
		// Before the use can choose the functionality, a sitetype and theme needs to be selected!
		elseif ( $this->is_action( 'choose_functionality' ) && isset( $_POST[ 'sitetype' ] ) ) {

			$site_type = $_POST[ 'sitetype' ];

			//check nonce
			check_admin_referer( 'choose_functionality' );

			if ( isset( $_POST[ 'theme' ] ) ) {
				$theme_id = key( $_POST[ 'theme' ] );
			} else {
				$theme_id = '';
			}

			include_once( One_And_One_Wizard::get_views_dir_path() . 'setup-wizard-plugins-select-step.php' );

			One_And_One_Plugin_Select_Step::get_plugin_selection( $site_type, $theme_id );
		}
		// Before the use can choose to install the selection, a sitetype and theme needs to be selected!
		elseif ( $this->is_action( 'install' ) && isset( $_POST[ 'sitetype' ] ) && isset( $_POST[ 'theme' ] ) ) {

			$site_type = $_POST[ 'sitetype' ];
			$theme_id  = $_POST[ 'theme' ];

			//check nonce
			check_admin_referer( 'install' );

			if ( isset( $_POST[ 'plugins' ] ) ) {
				$plugin_ids = join( ',', $_POST[ 'plugins' ] );
			} else {
				$plugin_ids = '';
			}

			include_once( One_And_One_Wizard::get_views_dir_path() . 'setup-wizard-installation-step.php' );

			One_And_One_Plugin_Installation_Step::get_installation( $site_type, $theme_id, $plugin_ids );
		}
		// If something is missing show the start of the wizard
		else {
			include( One_And_One_Wizard::get_views_dir_path() . 'setup-wizard-site-select-step.php' );

			One_And_One_Site_Selection_Step::get_site_selection();
		}
	}

	private	function is_action( $action ) {
		if ( isset( $_GET[ 'setup_action' ] ) && $action == $_GET[ 'setup_action' ] ) {
			return true;
		} else {
			return false;
		}
	}

}