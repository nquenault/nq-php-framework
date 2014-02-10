<?php if(!defined('BASEPATH')) die('No direct script access allowed');

require_once getPath('/core/libraries/framework.htmlTag.inc.php');

class linkTag extends htmlTag
{
	public function __construct($href, $rel = null, $media = null, $type = null)
	{
		$parameters = array(
			'rel'	=> '',
			'type'	=> '',
			'media'	=> $media === null ? 'all' : $media,
			'href'	=> $href
		);

		if($type === null)
			$type = getMimeType($href);
		

		if($rel === null)
			if(strpos($type, '/css') !== false)
				$rel = 'stylesheet';

		$parameters['type'] = $type;
		$parameters['rel'] = $rel === null ? '' : $rel;

		parent::__construct('link', $parameters);
	}

	public static function directHtml($href, $rel = null, $media = null, $type = null)
	{
		$h = new linkTag($href, $media, $rel, $type);
		return $h->html();
	}

	public static function directWrite($href, $rel = null, $media = null, $type = null)
	{
		echo linkTag::directHtml($href, $media, $rel, $type);
	}
}

?>