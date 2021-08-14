<?php

// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

class One_And_One_Utility {

	static public function check_credentials( $form_url, $form_fields ) {
		ob_start();
		$credentials = request_filesystem_credentials( $form_url, '', false, false, $form_fields );
		ob_end_clean();

		if ( false === $credentials ) {
			// Shows the filesystem credential form, which should stop her!
			return false;
		}

		if ( ! WP_Filesystem( $credentials ) ) {
			// We need to ask the user for credentials again.
			request_filesystem_credentials( $form_url, '', true, false, $form_fields );
			return false;
		}

		return true;
	}

	static public function get_remote_response_body( $url, $default = '' ) {
		$get_response = wp_remote_get( $url );

		if ( is_wp_error( $get_response ) || 200 != wp_remote_retrieve_response_code( $get_response ) ) {
			return $default;
		}

		return wp_remote_retrieve_body( $get_response );
	}

}