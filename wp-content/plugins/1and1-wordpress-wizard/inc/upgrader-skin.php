<?php

// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

abstract class One_And_One_Upgrader_Skin extends WP_Upgrader_Skin {

	function __construct( $args = array() ) {
		$defaults = array( 'url' => '', 'nonce' => '', 'title' => '', 'context' => false, 'theme' => '', 'plugin' => '' );
		$args = wp_parse_args( $args, $defaults );

		parent::__construct( $args );
	}

	function header() {
		if ( $this->done_header ) {
			return;
		}

		$this->done_header = true;
		echo '<div class="wrap">';
		echo '<h3>' . $this->options[ 'title' ] . '</h3>';
	}

	function error( $errors ) {
		$folder_exists = false;

		if ( is_wp_error( $errors ) ) {
			$error_codes = $errors->get_error_codes();

			if ( in_array( 'folder_exists', $error_codes ) ) {
				$folder_exists = true;
			}
		}

		parent::error( $errors );

		if ( $folder_exists ) {
			$this->feedback( $this->get_folder_exsists_message() );
		}
	}

	abstract function get_folder_exsists_message ();

}