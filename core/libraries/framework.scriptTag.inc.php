<?php if(!defined('BASEPATH')) die('No direct script access allowed');

require_once getPath('/core/libraries/framework.htmlTag.inc.php');

class scriptTag extends htmlTag
{
	public function __construct($path = null)
	{
		$parameters = array(
			'type' => getMimeType('js')
		);

		if($path !== null)
			$parameters['src'] = $path;

		parent::__construct('script', $parameters, '');
	}

	public static function directHtml($path)
	{
		$h = new scriptTag($path);
		return $h->html();
	}

	public static function directWrite($path)
	{
		echo scriptTag::directHtml($path);
	}
}

?>