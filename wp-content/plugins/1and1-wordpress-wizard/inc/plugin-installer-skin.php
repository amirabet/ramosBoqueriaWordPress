<?php

// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

include_once 'upgrader-skin.php';

class One_And_One_Plugin_Installer_Skin extends One_And_One_Upgrader_Skin {
	public $plugin = '';
	public $plugin_active = false;
	public $plugin_network_active = false;

	function __construct($args = array()) {
		$defaults = array( 'url' => '', 'plugin' => '', 'nonce' => '', 'title' => __('Update Plugin') );
		$args = wp_parse_args($args, $defaults);

		$this->plugin = $args['plugin'];

		$this->plugin_active = is_plugin_active( $this->plugin );
		$this->plugin_network_active = is_plugin_active_for_network( $this->plugin );

		parent::__construct($args);
	}

	function before() {
		if ( ! empty( $this->api ) ) {
			$this->upgrader->strings[ 'process_success' ] = sprintf(
				esc_html__( 'Successfully installed the plugin <strong>%1s$ %2s$</strong>.', '1and1-wordpress-wizard' ),
				$this->api->name,
				$this->api->version
			);
		}
	}

	function after() {
		$this->plugin = $this->upgrader->plugin_info();
	}

	function get_folder_exsists_message() {
		return __( 'The plugin has already been installed.', '1and1-wordpress-wizard' );
	}

}
