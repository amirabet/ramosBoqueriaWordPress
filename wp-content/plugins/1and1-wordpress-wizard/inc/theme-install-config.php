<?php

// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

class Theme_Install_Config extends Install_Config {
	private $activate_path;

	function __construct( $source_url, $slug, $activate_path ) {
		parent::__construct( $source_url, $slug );

		$this->activate_path = $activate_path;
	}

	function get_activate_path() {
		return $this->activate_path;
	}

}