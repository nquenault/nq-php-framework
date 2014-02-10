<?php if(!defined('BASEPATH')) die('No direct script access allowed');

class Headers
{
	/*
	|--------------------------------------------------------------------------
	| <SingleTon Part>
	|--------------------------------------------------------------------------
	|
	| --> DO NOT WRITE IN THIS BLOCK <--
	|
	*/
	private static $instance;
	private function __construct() { }

	public static function getInstance()
	{
		if(!isset(self::$instance))
		{
			$c = __CLASS__;
			self::$instance = new $c;
		}

		return self::$instance;
	}

	public function __clone() { trigger_error('Le clônage n\'est pas autorisé.', E_USER_ERROR); }
	/*
	|--------------------------------------------------------------------------
	| </SingleTon Part>
	|--------------------------------------------------------------------------
	|
	| Write your code from here..
	|
	*/

	private $done = false;
	private $tags = array();
	private $lang = 'en';
	private $title = '';
	private $doctypeDone = false;

	public static function directSetTitle($title, $append = false) { Headers::getInstance()->setTitle($title, $append); }
	public function setTitle($title, $append = false) { $this->title = ($append ? $this->title : '').$title; }

	public static function directClear() { Headers::directClean(); }
	public static function directClean() { Headers::getInstance()->clean(); }
	public function clear() { $this->clean(); }
	public function clean()
	{
		$this->done = false;
		$this->tags = array();
		$this->lang = 'en';
		$this->title = '';
		$this->done = false;
	}

	public static function directSetLanguage($lang) { Headers::getInstance()->setLanguage($lang); }
	public function setLanguage($lang)
	{
		if(strpos($lang, ',') !== false)
			$lang = substr($lang, 0, strpos($lang, ','));

		$this->lang = $lang;
	}

	public static function directWrite() { Headers::directFlush(); }
	public static function directFlush() { Headers::getInstance()->flush(); }
	public function write() { $this->flush(); }
	public function flush()
	{
		if($this->done)
			return;

		$this->done = true;
		
		if(!$this->doctypeDone)
			echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"
                      \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"".$this->lang."\" lang=\"".$this->lang."\">
<head>
	<title>".$this->title."</title>
";

		$this->doctypeDone = true;

		foreach($this->tags as $tag)
			echo "\t".$tag->html()."\n";
	}

	public static function directAddTag($tag) { Headers::getInstance()->addTag($tag); }
	public function addTag($tag)
	{
		$class = strtolower(get_class($tag));

		if(strrev(substr(strrev($class), 0, 3)) == 'tag')
			array_push($this->tags, $tag);
		elseif(is_array($tag) && isset($tag['tagName']))
		{
			$h = new htmlTag($tag['tagName']);

			if(isset($tag['parameters']))
				$h->addParameters($tag['parameters']);

			if(isset($tag['content']))
				$h->setContent($tag['content']);

			array_push($this->tags, $h);
		}
	}
}

?>