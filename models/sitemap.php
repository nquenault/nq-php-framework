<?php if(!defined('BASEPATH')) die('No direct script access allowed');

class sitemap
{
	public function index()
	{
		echo file_get_contents(getPath('/sitemap.xml'));
	}
}

?>