<?php if(!defined('BASEPATH')) die('No direct script access allowed');

function chrlist($from, $to)
{
	$charset = '';

	for($i=$from;$i<=$to;$i++)
		$charset .= chr($i);

	return $charset;
}

function strip_accent($string) {
	$conv = array(
		'a'		=> chrlist(224,229), // àáâãäæ
		'A'		=> chrlist(192,197), // ÀÁÂÃÄÅ
		'ae'	=> chr(230), // æ
		'AE'	=> chr(198), // Æ
		'B'		=> chr(223), // ß
		'c'		=> chr(231), // ç
		'C'		=> chr(199), // Ç
		'D'		=> chr(208), // Ð
		'e'		=> chrlist(232,235), // èéêë
		'E'		=> chrlist(200,203), // ÈÉÊË
		'i'		=> chrlist(236,239), // ìíîï
		'I'		=> chrlist(204,207), // ÌÍÎÏ
		'n'		=> chr(241), // ñ
		'N'		=> chr(209), // Ñ
		'o'		=> chrlist(242,246), // òóôõö
		'O'		=> chrlist(210,214), // ÒÓÔÕÖ
		'u'		=> chrlist(249,252), // ùúûü
		'U'		=> chrlist(217,220), // ÙÚÛÜ
		'y'		=> chr(253).chr(255), // ýÿ
		'Y'		=> chr(221).chr(159).chr(165), // Ý¥Ÿ
	);
	foreach($conv as $noax => $axx) {
		for($i=0;$i<strlen($axx);$i++) {
			$string = str_replace($axx[$i], $noax, $string);
		}
	}
	return $string;
}
function strip_specialchars($string, $replace = '') {
	$ponct = '&~!"#$%\'()*+,-./:;<=>?@[\\]^_`{|}‘’¡¢£¤¦§¨©ª«¬­®¯°±²³´µ¶·¸¹º»¼½¾¿×ØÞð÷øþ';
	for($i=0;$i<strlen($ponct);$i++) {
		$string = str_replace($ponct[$i], $replace, $string);
	}
	return $string;
}

function get_ip_address()
{
	$keys = array(
		'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED',
		'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'
	);

	foreach($keys as $key)
		if (array_key_exists($key, $_SERVER) === true)
			foreach (explode(',', $_SERVER[$key]) as $ip)
				if (filter_var($ip, FILTER_VALIDATE_IP) !== false)
					return $ip;
}

function GenKey($length = 50)
{
	$key = '';

	$alpha = 'abcdefghijklmnopqrstuvwxyz';
	$num = '0123456789';
	$charset = $alpha.$num.strtoupper($alpha);

	for($i=0;$i<$length;$i++)
		$key .= $charset[rand(0, strlen($charset) - 1)];

	return $key;
}

?>