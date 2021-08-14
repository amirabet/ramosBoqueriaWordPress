<?php

// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

class One_And_One_Persistence_Manager {

	const WELCOME_PANEL_DISMISS_KEY = 'oao_welcome_panel';

	public function store( $key, $value ) {
		update_option( $key, $value );
	}

	public function get( $key ) {
		return get_option( $key );
	}

	public function erase_persistent_data() {
		delete_option( self::WELCOME_PANEL_DISMISS_KEY );
	}

}