<?php
/*
Plugin Name:  1&1 WP Wizard
Plugin URI:   http://www.1and1.com
Description:  WordPress Setup Wizard
Version:      2.0
License:      GPLv2 or later
Author:       1&1
Author URI:   http://www.1and1.com
Text Domain:  1and1-wordpress-wizard
Domain Path:  /languages
*/

/*
Copyright 2014 1&1
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Online: http://www.gnu.org/licenses/gpl.txt
*/

// Do not allow direct access!
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Forbidden' );
}

class One_And_One_Wizard {

	const VERSION = '2.0';

	private $persistence_manager;
	private $welcome_panel;
	private $setup_wizard_dispatcher;
	private $setup_installer_dispatcher;

	public function __construct() {
		if ( is_admin() ) {
			$this->load_files();

			$this->persistence_manager = new One_And_One_Persistence_Manager();
			$this->welcome_panel = new One_And_One_Setup_Welcome_Panel();
			$this->setup_wizard_dispatcher = new One_And_One_Setup_Wizard_Dispatcher();
			$this->setup_installer_dispatcher = new One_And_One_Setup_Installer_Dispatcher();

			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'add_styles_scripts' ) );

			// shows the 1&1 "get started message" as a seperate panel above the welcome panel
			add_action( 'admin_notices', array( $this, 'show_welcome_panel' ) );

			add_action( 'admin_menu', array( $this, 'add_tools_menu' ) );

			// add ajax hook
			add_action( 'wp_ajax_oao_dismiss_welcome_panel', array( $this, 'dismiss_welcome_panel' ) );

			register_activation_hook( __FILE__, array( $this, 'activate_plugin' ) );

			add_action( 'admin_init', array( $this->setup_installer_dispatcher, 'dispatcher_installer_action' ) );
			add_action( 'admin_init', array( $this, 'start_auto_update' ) );
		}
	}


	public function load_files() {
		include_once 'inc/welcome-panel.php';
		include_once 'inc/setup-wizard-dispatcher.php';
		include_once 'inc/setup-installer-dispatcher.php';
		include_once 'inc/persistence-manager.php';

		include_once 'inc/autoupdater.php';
		include_once 'inc/utility.php';
	}


	public function load_textdomain() {
		load_plugin_textdomain( '1and1-wordpress-wizard', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	public function add_styles_scripts() {
		$suffix = SCRIPT_DEBUG ? '' : '.min';

		wp_register_style( '1and1-wizard-welcome', self::get_css_url( 'welcome-panel' . $suffix . '.css' ), array(), self::VERSION );
		wp_register_style( '1and1-wp-wizard', self::get_css_url( 'wizard' . $suffix . '.css' ), array(), self::VERSION );
		wp_register_style( '1and1-install-progress', self::get_css_url( 'install-progress' . $suffix . '.css' ), array(), self::VERSION );

		wp_register_script( '1and1-install-progress', self::get_js_url( 'install-progress.js' ), array('jquery'), self::VERSION );
	}

	public function show_welcome_panel() {
		// Check user permissions
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Check if we are in the dashboard
		if ( 'dashboard' != get_current_screen()->id ) {
			return;
		}

		if ( $this->persistence_manager->get( One_And_One_Persistence_Manager::WELCOME_PANEL_DISMISS_KEY ) == false ) {
			return $this->welcome_panel->welcome_panel_message();
		}
	}

	public function add_tools_menu() {
		add_management_page( __( '1&1 WP Wizard', '1and1-wordpress-wizard' ), __( '1&1 WP Wizard', '1and1-wordpress-wizard' ), 'manage_options', '1and1-wordpress-wizard', array( $this->setup_wizard_dispatcher, 'dispatch_wizard_actions' ) );
	}

	public function activate_plugin() {
		// Check WordPress version
		if ( version_compare( get_bloginfo( 'version' ), '3.5', '<' ) ) {
			die( __( 'The 1&1 WP Wizard could not be activated. To activate the plugin, you need WordPress 3.5 or higher.', '1and1-wordpress-wizard' ) );
		}
	}

	public function dismiss_welcome_panel() {
		$this->persistence_manager->store( One_And_One_Persistence_Manager::WELCOME_PANEL_DISMISS_KEY, true );
	}

	public static function get_css_url( $file = '' ) {
		return plugins_url( 'css/' . $file, __FILE__ );
	}

	public static function get_js_url( $file = '' ) {
		return plugins_url( 'js/' . $file, __FILE__ );
	}

	public static function get_images_url( $image = '' ) {
		return plugins_url( 'images/' . $image, __FILE__ );
	}

	public static function get_plugin_file_path() {
		return __FILE__;
	}

	public static function get_plugin_dir_path() {
		return plugin_dir_path( __FILE__ );
	}

	public static function get_inc_dir_path() {
		return plugin_dir_path( __FILE__ ) . 'inc/';
	}

	public static function get_views_dir_path() {
		return plugin_dir_path( __FILE__ ) . 'inc/views/';
	}

	public function start_auto_update() {
		$updater = new One_And_One_Autoupdater();
		$updater->start_auto_update();
	}

}

$one_and_one_wizard = new One_And_One_Wizard();
