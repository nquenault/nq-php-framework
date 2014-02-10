<?php if(!defined('BASEPATH')) die('No direct script access allowed');

require_once getPath('/core/libraries/framework.htmlTag.inc.php');

class metaTag extends htmlTag
{
	public $paramName = 'name';

	public function __construct($name, $content, $httpequiv = false)
	{
		parent::__construct('meta', array(
			($httpequiv ? 'http-equiv' : $this->paramName) => $name,
			'content' => $content
		));
	}

	public static function directHtml($name, $content, $httpequiv = false)
	{
		$h = new metaTag($name, $content, $httpequiv);
		return $h->html();
	}

	public static function directWrite($name, $content, $httpequiv = false)
	{
		echo metaTag::directHtml($name, $content, $httpequiv);
	}

	public function setParamName($paramName)
	{
		$this->paramName = $paramName;
		return $this;
	}
}

?>