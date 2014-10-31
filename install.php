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

// include language file for syncData
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

global $admin;

$tables = array('dbSyncDataArchives','dbSyncDataCfg','dbSyncDataFiles','dbSyncDataJobs','dbSyncDataProtocol');
$error = '';

foreach ($tables as $table) {
	$create = null;
	$create = new $table();
	if (!$create->sqlTableExists()) {
		if (!$create->sqlCreateTable()) {
			$error .= sprintf('<p>[INSTALLATION %s] %s</p>', $table, $create->getError());
		}
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