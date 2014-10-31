//:client interface for syncData
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
if (file_exists(LEPTON_PATH.'/modules/sync_data/class.synchronize.php')) {
	require_once(LEPTON_PATH.'/modules/sync_data/class.synchronize.php');
	$client = new syncClient();
	$params = $client->getParams();
	$params[syncClient::param_preset] = (isset($preset)) ? (int) $preset : 1;
	$params[syncClient::param_css] = (isset($css) && (strtolower($css) == 'false')) ? false : true;
	if (!$client->setParams($params)) return $client->getError();
	return $client->action();
}
else {
	return "syncData is not installed!";
}