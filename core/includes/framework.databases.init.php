<?php if(!defined('BASEPATH')) die('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Connection à la base de données
|--------------------------------------------------------------------------
*/
if($config['db']['hostname'] && $config['db']['username'] && $config['db']['database'])
{
	$db['default'] = mysql_connect(
		$config['db']['hostname'],
		$config['db']['username'],
		$config['db']['password']
	);

	if(!$db['default'])
	{
		$errors = mysqli_error();
		die('Impossible de se connecter à la base '.$config['db']['hostname']."<br /><br />".utf8_encode($errors[0]['message']));
	}
	else mysql_select_db($config['db']['database']);
}

?>