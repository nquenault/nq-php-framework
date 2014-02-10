<?php if(!defined('BASEPATH')) die('No direct script access allowed');

class welcome
{
	public function index()
	{
		loadview('welcome', array(
			'ip' => get_ip_address()
		));
	}
}

?>