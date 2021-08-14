<?php

// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

include_once 'transience-manager.php';

class One_And_One_Autoupdater {

	static private $latest_version;
	static private $check_rate_in_seconds = 43200;

	public function start_auto_update() {
		if ( $this->update_check_necessary() ) {
			$catalog        = new One_And_One_Catalog;
			$latest_version = $catalog->get_latest_version();

			if ( ! $latest_version ) {
				return;
			}

			self::$latest_version = $latest_version->version;

			if ( $this->newer_version_available() ) {
				$this->download_and_activate_new_version( $latest_version->zip );
			}
		}
	}

	private function update_check_necessary() {
		$transient_manager = new One_And_One_Transience_Manager();

		if ( $transient_manager->get( One_And_One_Transience_Manager::TRANSIENT_CHECK_FLAG ) ) {
			return false;
		}

		$transient_manager->store( One_And_One_Transience_Manager::TRANSIENT_CHECK_FLAG, 'set', self::$check_rate_in_seconds );

		return true;
	}

	private function newer_version_available() {
		$current_version = $this->get_current_version();
		$latest_version  = $this->get_latest_version();

		if ( -1 == ( version_compare( $current_version, $latest_version ) ) ) {
			return true;
		}

		return false;
	}


	private function get_current_version() {
		include_once ABSPATH . '/wp-admin/includes/plugin.php';

		$plugin_file_path = One_And_One_Wizard::get_plugin_file_path();
		$data = get_plugin_data( $plugin_file_path );

		return $data[ 'Version' ];
	}

	private function get_latest_version() {
		return self::$latest_version;
	}

	private function download_and_activate_new_version( $zip_url ) {
		if ( ! One_And_One_Utility::check_credentials( 'plugins.php', null ) ) {
			return false;
		}

		$response_body = One_And_One_Utility::get_remote_response_body( $zip_url );

		if ( empty( $response_body ) ) {
			return false;
		}

		$zip_file_path = One_And_One_Wizard::get_plugin_dir_path() . 'plugin.zip';

		global $wp_filesystem;
		$wp_filesystem->put_contents(
			$zip_file_path,
			$response_body,
			FS_CHMOD_FILE
		);

		// delete all transient data
		$transience_manager = new One_And_One_Transience_Manager();
		$transience_manager->erase_transient_data();

		$unzip = unzip_file( $zip_file_path, WP_PLUGIN_DIR );
		@unlink( $zip_file_path );

		activate_plugin( One_And_One_Wizard::get_plugin_file_path() );
	}

}
