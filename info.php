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

$module_directory     = 'sync_data';
$module_name          = 'syncData';
$module_function      = 'tool';
$module_version       = '0.5.1';
$module_status        = 'Beta';
$module_platform      = '1.x'; 
$module_author        = 'Ralf Hertsch (†), cms-lab';
$module_license       = 'GNU General Public License';
$module_description   = 'Data backup, restore and synchronize for LEPTON CMS'; 
$module_home          = 'http://cms-lab.com';
$module_guid          = '44E09E74-6431-4232-9979-7C9BF615C921';


/**
 *  changelog:
 *  https://github.com/labby/syncData
 *
 */
?>