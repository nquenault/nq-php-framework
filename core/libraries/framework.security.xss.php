<?php if(!defined('BASEPATH')) die('No direct script access allowed');

function clearXSS($value)
{
	if(is_array($value))
	{
		foreach ($value as $t_key => $t_value)
			if (!is_array($t_value))
				$value[$t_key] = clearXSS($t_value);
	}
	else
	{
		$notAllowedChar = "&<>\"'/";
		for($i=0;$i<strlen($notAllowedChar);$i++)
		{
			$hexCode = dechex(ord($notAllowedChar[$i]));
			$value = preg_replace('#\x'.$hexCode.'#i', '&#x'.$hexCode.';', $value);
		}
	}
	
	return $value;
}

if($config['xss_filtering']['REQUEST'])
	$_REQUEST = clearXSS($_REQUEST);

if($config['xss_filtering']['POST'])
	$_POST = clearXSS($_POST);

if($config['xss_filtering']['GET'])
	$_GET = clearXSS($_GET);

if($config['xss_filtering']['COOKIE'])
	$_COOKIE = clearXSS($_COOKIE);

?>