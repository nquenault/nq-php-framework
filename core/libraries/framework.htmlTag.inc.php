<?php if(!defined('BASEPATH')) die('No direct script access allowed');

class htmlTag
{
	private $tagName = '';
	private $parameters = array();
	private $content = null;

	public function setTagName($tagName) { $this->tagName = $tagName; }
	public function addParameter($paramName, $paramValue) { $this->parameters[$paramName] = $paramValue; }
	public function removeParameters() { $this->parameters = array(); }
	public function setContent($content = null) { $this->content = $content; }
	public function removeContent() { $this->setContent(null); }
	public function write()	{ echo $this->html(); }

	public function __construct($tagName, $parameters = array(), $content = null)
	{
		$this->setTagName($tagName);
		$this->addParameters($parameters);
		$this->setContent($content);
	}

	public function addContent($content = '')
	{
		if($this->content === null)
			$this->content = $content;
		else
			$this->content .= $content;
	}

	public function addParameters($parameters)
	{
		if(!is_array($parameters))
			return;

		foreach($parameters as $paramName => $paramValue)
			$this->addParameter($paramName, $paramValue);
	}

	public function html()
	{
		$htmlTag = '<'.$this->tagName;

		foreach($this->parameters as $paramName => $paramValue)
			$htmlTag .= ' '.$paramName.'="'.str_replace('"', '\"', $paramValue).'"';

		$htmlTag .= $this->content !== null ? '>'.$this->content.'</'.$this->tagName.'>' : ' />';
		
		return $htmlTag;
	}

	public static function directHtml($tagName, $parameters = array(), $content = null)
	{
		$h = new htmlTag($tagName, $parameters, $content);
		return $h->html();
	}

	public static function directWrite($tagName, $parameters = array(), $content = null)
	{
		echo htmlTag::directHtml($tagName, $parameters, $content);
	}
}

?>