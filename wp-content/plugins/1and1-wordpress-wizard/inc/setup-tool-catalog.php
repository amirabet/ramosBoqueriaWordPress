<?php

// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

include_once 'transience-manager.php';

class One_And_One_Catalog {

	private $catalog_url = 'https://community.1and1.com/api/wizard/';

	private $transient_manager;
	private $cache = array();

	public function __construct() {
		$this->transient_manager = new One_And_One_Transience_Manager();
	}


	public function get_latest_version() {
		return $this->get_api_data( 'update-check' );
	}


	public function get_site_types() {
		$site_types = $this->make_api_call( 'website-types', One_And_One_Transience_Manager::CATALOG_SITE_TYPES );

		if ( ! $site_types ) {
			$site_types = array();
		}

		return $site_types;
	}

	public function get_themes_by_site_type( $site_type ) {
		$args = array(
			'website_type' => $site_type
		);

		return $this->make_api_call( 'themes', One_And_One_Transience_Manager::CATALOG_THEMES . '_' . $site_type, $args );
	}

	public function get_theme_by_id( $site_type, $theme_id ) {
		$themes = $this->get_themes_by_site_type( $site_type );

		if ( isset( $themes->{$theme_id} ) ) {
			return $themes->{$theme_id};
		}

		return false;
	}


	public function get_plugins( $site_type, $filtered = true ) {
		$cache_key = 'plugins-' . $filtered . '-' . $site_type;

		if ( isset( $this->cache[ $cache_key ] ) ) {
			return $this->cache[ $cache_key ];
		}

		$args = array(
			'website_type' => $site_type
		);

		$plugins = $this->make_api_call( 'plugins', One_And_One_Transience_Manager::CATALOG_PLUGINS . '_' . $site_type, $args );

		if ( $plugins && $filtered ) {
			$plugins_keys = array_keys( (array) $plugins );

			$active_plugins  = (array) get_option( 'active_plugins', array() );
			$network_plugins = is_multisite() ? wp_get_active_network_plugins() : false;

			foreach ( $active_plugins as $plugin ) {
				$parts = explode( '/', $plugin );

				if ( in_array( $parts[0], $plugins_keys ) ) {
					unset( $plugins->$parts[0] );
				}
			}

			if ( $network_plugins ) {
				foreach ( $network_plugins as $plugin ) {
					$parts = explode( '/', $plugin );

					if ( in_array( $parts[0], $plugins_keys ) ) {
						unset( $plugins->$parts[0] );
					}
				}
			}
		}

		// Store in cache so we don't need to do all this logic the next time
		$this->cache[ $cache_key ] = $plugins;

		return $plugins;
	}

	public function get_default_plugins( $site_type ) {
		$plugins = $this->get_plugins( $site_type );

		if ( ! $plugins ) {
			return false;
		}

		return wp_filter_object_list( (array) $plugins, array( 'installation' => 'default' ) );
	}

	public function get_recommended_plugins( $site_type ) {
		$plugins = $this->get_plugins( $site_type );

		if ( ! $plugins ) {
			return false;
		}

		return wp_filter_object_list( (array) $plugins, array( 'installation' => 'recommended' ) );
	}

	public function get_plugins_by_ids( $site_type, array $plugin_ids ) {
		$plugins = $this->get_plugins( $site_type, false );
		$by_ids  = array();

		foreach ( $plugin_ids as $plugin_id ) {
			$by_ids[ $plugin_id ] = $plugins->{ $plugin_id };
		}

		return (object) $by_ids;
	}


	private function make_api_call( $callback, $cache_key, $args = array(), $expiration = false ) {
		$data = $this->transient_manager->get( $cache_key );

		if ( false === $data || null == $data ) {
			$data = $this->get_api_data( $callback, $args );

			if ( ! $expiration ) {
				$expiration = 12 * HOUR_IN_SECONDS;
			}

			// and cache it
			$this->transient_manager->store( $cache_key, $data, $expiration );
		}

		return $data;
	}

	private function get_api_data( $callback, $args = array() ) {
		$args = wp_parse_args( $args, array( 'locale' => get_locale() ) );
		$url  = add_query_arg( $args, $this->catalog_url . $callback );
		$data = One_And_One_Utility::get_remote_response_body( $url );

		if ( ! $data ) {
			$data = $this->api_call_fallback( $callback );
		}

		$data = json_decode( $data );

		return $data;
	}

	private function api_call_fallback( $callback ) {
		return '';
	}

}