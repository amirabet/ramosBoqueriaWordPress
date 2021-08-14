<?php

// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

include_once 'upgrader-skin.php';

class One_And_One_Theme_Installer_Skin extends One_And_One_Upgrader_Skin {
	public $theme = '';

	function __construct($args = array()) {
		$defaults = array( 'url' => '', 'theme' => '', 'nonce' => '', 'title' => __('Update Theme') );
		$args = wp_parse_args($args, $defaults);

		$this->theme = $args['theme'];

		parent::__construct($args);
	}

	function get_folder_exsists_message() {
		return __( 'The theme already exists.', '1and1-wordpress-wizard' );
	}

}