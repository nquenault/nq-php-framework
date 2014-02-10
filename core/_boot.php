<?php if(!defined('BASEPATH')) die('No direct script access allowed');

function getPath($path) { return preg_replace('#(\x2F|\x5C)+#', DIRECTORY_SEPARATOR, BASEPATH.'/'.$path); }

require_once getPath('/config/config.php');
require_once getPath('/core/includes/framework.functions.inc.php');

header('Content-Type: text/html; charset='.$config['charset']);
header('Content-Language: '.$config['lang']);

if(isset($config['session_name']) && $config['session_name'])
	session_name($config['session_name']);

$totalSecondes = $config['session_expire']['secondes'];
$totalSecondes += $config['session_expire']['minutes'] * 60;
$totalSecondes += $config['session_expire']['hours'] * 3600;
$totalSecondes += $config['session_expire']['days'] * 86400;

session_set_cookie_params($totalSecondes);
session_start();

loadDirectory('/core/libraries/');
loadDirectory('/core/includes/');

loadDirectory('/libraries/');
loadDirectory('/includes/');

?>