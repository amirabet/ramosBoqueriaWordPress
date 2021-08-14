<?php

// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

class Install_Config {
	private $source_url;
	private $slug;

	function __construct( $source_url, $slug ) {
		$this->source_url = $source_url;
		$this->slug = $slug;
	}

	function get_source_url() {
		return $this->source_url;
	}

	function get_slug() {
		return $this->slug;
	}

}