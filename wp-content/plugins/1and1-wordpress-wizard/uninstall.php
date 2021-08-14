<?php
// Check if uninstall is called from WordPress!
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

include_once 'inc/persistence-manager.php';
$persistence_manager = new One_And_One_Persistence_Manager();
$persistence_manager->erase_persistent_data();

include_once 'inc/transience-manager.php';
$transience_manager = new One_And_One_Transience_Manager();
$transience_manager->erase_transient_data();
