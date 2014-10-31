<?php

/**
 *  @module         syncData
 *  @version        see info.php of this module
 *  @authors        Ralf Hertsch (†), cms-lab
 *  @copyright      2011 - 2012 Ralf Hertsch (†)
 *  @copyright      2013-2014 cms-lab 
 *  @license        GNU GPL (http://www.gnu.org/licenses/gpl.html)
 *  @license terms  see info.php of this module
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH')) {	
	include(LEPTON_PATH.'/framework/class.secure.php'); 
} else {
	$oneback = "../";
	$root = $oneback;
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= $oneback;
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) { 
		include($root.'/framework/class.secure.php'); 
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}
// end include class.secure.php
 
// include language file for flexTable
if(!file_exists(LEPTON_PATH .'/modules/'.basename(dirname(__FILE__)).'/languages/' .LANGUAGE .'.php')) {
	require_once(LEPTON_PATH .'/modules/'.basename(dirname(__FILE__)).'/languages/DE.php'); // Vorgabe: DE verwenden 
}
else { 
	require_once(LEPTON_PATH .'/modules/'.basename(dirname(__FILE__)).'/languages/' .LANGUAGE .'.php');
}

if (!class_exists('checkDroplets')) {
	// try to load required class.droplets.php
	if (file_exists(LEPTON_PATH.'/modules/kit_tools/class.droplets.php')) {
		require_once LEPTON_PATH.'/modules/kit_tools/class.droplets.php';
	}
	else {
		// load embedded class.droplets.php
		require_once LEPTON_PATH.'/modules/'.basename(dirname(__FILE__)).'/class.droplets.php';
	}
}

require_once(LEPTON_PATH .'/modules/'.basename(dirname(__FILE__)).'/class.syncdata.php');
require_once(LEPTON_PATH .'/modules/'.basename(dirname(__FILE__)).'/class.interface.php');

global $admin;
global $database;

$error = '';

/**
 * 0.10 --> 0.11
 */
// delete unused mod_sync_data_cronjob_data
$SQL = sprintf("DROP TABLE IF EXISTS %smod_sync_data_cronjob_data", TABLE_PREFIX);
$database->query($SQL);
if ($database->is_error()) {
	$error .= sprintf('<p>[DROP TABLE mod_sync_data_cronjob_data] %s</p>', $database->get_error());
}
// delete directory /htt
if (file_exists(LEPTON_PATH.'/modules/'.basename(dirname(__FILE__)).'/htt')) {
	$interface = new syncDataInterface();
	if (!$interface->clearDirectory(LEPTON_PATH.'/modules/'.basename(dirname(__FILE__)).'/htt', false)) {
		$error .= sprintf('<p>[DELETE DIRECTORY %s] %s</p>', '/modules/'.basename(dirname(__FILE__)).'/htt', $interface->getError());
	}
}

// remove Droplets
$dbDroplets = new dbDroplets();
$droplets = array('sync_client');
foreach ($droplets as $droplet) {
	$where = array(dbDroplets::field_name => $droplet);
	if (!$dbDroplets->sqlDeleteRecord($where)) {
		$message = sprintf('[UPGRADE] Error uninstalling Droplet: %s', $dbDroplets->getError());
	}	
}

// Install Droplets
$droplets = new checkDroplets();
$droplets->droplet_path = LEPTON_PATH.'/modules/sync_data/droplets/';

if ($droplets->insertDropletsIntoTable()) {
  $message = sprintf(sync_msg_install_droplets_success, 'syncData');
}
else {
  $message = sprintf(sync_msg_install_droplets_failed, 'syncData', $droplets->getError());
}
if ($message != "") {
  echo '<script language="javascript">alert ("'.$message.'");</script>';
}


// Prompt Errors
if (!empty($error)) {
	$admin->print_error($error);
}

?>