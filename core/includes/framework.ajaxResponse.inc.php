<?php if(!defined('BASEPATH')) die('No direct script access allowed');

class ajaxResponse
{
	private $success = false;
	private $message = '';
	private $location = '';
	private $data = '';

	public function setSuccess($success = true) { $this->success = ($success ? true : false); return $this; }
	public function setMessage($message) { $this->message = $message; return $this; }
	public function appendMessage($message)	{ $this->message .= $message; return $this; }
	public function setLocation($url) { $this->location = $url; return $this; }
	public function setData($data) { $this->data = $data; return $this; }

	public function isSuccessful() { return $this->success; }
	public function hasMessage() { return $this->message != ''; }
	public function hasLocation() { return $this->location != ''; }
	public function hasData() { return $this->data != ''; }

	public function flush($exit = false)
	{
		echo json_encode(array(
			'success'	=> $this->success,
			'message'	=> $this->message,
			'data'		=> $this->data,
			'location'	=> $this->location
		));

		if($exit) exit();
	}

	public static function directFlushOutput($output, $exit = false)
	{
		$h = new ajaxResponse();
		
		if(isset($output['success']))
			$h->setSuccess($output['success']);
		
		if(isset($output['message']))
			$h->setMessage($output['message']);
		
		if(isset($output['data']))
			$h->setData($output['data']);
		
		if(isset($output['location']))
			$h->setLocation($output['location']);

		$h->flush($exit);
	}

	public static function directFlush($success, $message = '', $location, $data = '', $exit = false)
	{
		ajaxResponse::directFlush(array(
			'success'	=> $success,
			'message'	=> $message,
			'location'	=> $location,
			'data'		=> $data
		), $exit);
	}

	public static function directFlushSuccess($success, $exit = false)
	{
		ajaxResponse::directFlush(array(
			'success'	=> $success
		), $exit);
	}

	public static function directFlushMessage($message, $success = true, $exit = false)
	{
		ajaxResponse::directFlush(array(
			'success'	=> $success,
			'message'	=> $message
		), $exit);
	}

	public static function directFlushLocation($location, $success = true, $exit = false)
	{
		ajaxResponse::directFlush(array(
			'success'	=> $success,
			'location'	=> $location
		), $exit);
	}

	public static function directFlushData($data, $success = true, $exit = false)
	{
		ajaxResponse::directFlush(array(
			'success'	=> $success,
			'data'		=> $data
		), $exit);
	}
}

?>