<?php if(!defined('BASEPATH')) die('No direct script access allowed');

define('AJAXED', (getVar('ajaxed') == 1 ? true : false));

define('IS_iOS', preg_match('#(iPhone|iPod|iPad)#', $_SERVER['HTTP_USER_AGENT']) ? true : false);
define('IS_iPhone', preg_match('#iPhone#', $_SERVER['HTTP_USER_AGENT']) ? true : false);
define('IS_iPad', preg_match('#iPad#', $_SERVER['HTTP_USER_AGENT']) ? true : false);
define('IS_iPod', preg_match('#iPod#', $_SERVER['HTTP_USER_AGENT']) ? true : false);

?>