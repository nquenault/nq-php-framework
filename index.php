<?php
define('BASEPATH', dirname(__FILE__).DIRECTORY_SEPARATOR);
define('BASEURL', str_replace(basename(__FILE__), '', $_SERVER['PHP_SELF']));

require './core/_boot.php';

preg_match('#([^?]*)#', $_SERVER['REQUEST_URI'], $matches);
preg_match(
	'|'.strhex(BASEURL).'(([^/]*)?(/([^/]*))?(/([^/]*))?(\.[^/\?]*))(\?(.*))?$|',
	$matches[0],
	$matches
);

if((isset($matches[7]) && $matches[7] == $config['url_suffix']) || !isset($matches[7]))
{
	$model = isset($matches[2]) ? $matches[2] : $config['default_page'];

	if(isset($matches[4]) && $matches[4])
		loadmodel($model, $matches[4]);
	else
		loadmodel($model);
}
elseif($config['behavior']['HOME_ON_NOTFOUND'])
	redirect();
else
	include getPath('/core/errors/404.php');
?>