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

// import default droplep
if (!function_exists('droplep_install')) {
    include_once LEPTON_PATH.'/modules/dropleps/functions.php';
}
if (file_exists(dirname(__FILE__) . '/dropleps/droplep_sync_client.zip')) {
droplep_install(dirname(__FILE__) . '/dropleps/droplep_sync_client.zip', LEPTON_PATH . '/temp/unzip/');
}

// Prompt Errors
if (!empty($error)) {
	$admin->print_error($error);
}

?>