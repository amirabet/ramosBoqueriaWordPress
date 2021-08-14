<?php

// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

class One_And_One_Transience_Manager {

	const CATALOG_SITE_TYPES   = 'oao_catalog_site_types';
	const CATALOG_THEMES       = 'oao_catalog_themes';
	const CATALOG_PLUGINS      = 'oao_catalog_plugins';
	const TRANSIENT_CHECK_FLAG = 'oao_auto_update_last_check';

	public function store( $key, $value, $ttl_in_sec ) {
		$key = $this->get_real_key( $key );

		if ( ! $key['index'] ) {
			return set_transient( $key['key'], $value, $ttl_in_sec );
		}

		$data = get_transient( $key['key'] );

		if ( ! is_array( $data ) ) {
			$data = array();
		}

		$data[ $key['index'] ] = $value;

		return set_transient( $key['key'], $data, $ttl_in_sec );
	}

	public function get( $key ) {
		$key = $this->get_real_key( $key );

		$data = get_transient( $key['key'] );

		if ( ! $key['index'] ) {
			return $data;
		}

		if ( is_array( $data ) && isset( $data[ $key['index'] ] ) ) {
			return $data[ $key['index'] ];
		}

		return false;
	}

	public function erase_transient_data() {
		delete_transient( self::CATALOG_SITE_TYPES );
		delete_transient( self::CATALOG_THEMES );
		delete_transient( self::CATALOG_PLUGINS );
		delete_transient( self::TRANSIENT_CHECK_FLAG );
	}


	private function get_real_key( $key ) {
		$index = false;

		if ( $key != self::CATALOG_SITE_TYPES && $key != self::TRANSIENT_CHECK_FLAG ) {
			if ( strpos( $key, self::CATALOG_PLUGINS ) !== false ) {
				$index = substr( $key, 20 );
				$key   = self::CATALOG_PLUGINS;
			}
			else if ( strpos( $key, self::CATALOG_THEMES ) !== false ) {
				$index = substr( $key, 19 );
				$key   = self::CATALOG_THEMES;
			}
		}

		return array(
			'key'   => $key,
			'index' => $index
		);
	}

}