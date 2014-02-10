<?php if(!defined('BASEPATH')) die('No direct script access allowed');

Headers::directSetTitle($config['site_title']);
Headers::directSetLanguage($config['lang']);

Headers::directAddTag(new metaTag('Content-Type', 'text/html;charset='.$config['charset'], true));

$configScript = new scriptTag();
$configScript->setContent('
		var config = {
			baseurl: \''.BASEURL.'\',
			url_suffix: \''.$config['url_suffix'].'\',
			anchors_ajax_navigation: '.($config['anchors_ajax_navigation'] ? 'true' : 'false').'
		};

		var isiOS = '.(IS_iOS ? 'true' : 'false').'
		var isiPhone = '.(IS_iPhone ? 'true' : 'false').'
		var isiPod = '.(IS_iPod ? 'true' : 'false').'
	');
Headers::directAddTag($configScript);
unset($configScript);

Headers::directAddTag(new scriptTag(getUrl('/core/scripts/json2.js')));

if($config['jquery']['enabled'])
{
	Headers::directAddTag(new scriptTag(getJQueryFilePath(array('/scripts/', '/core/scripts/'), $config['jquery']['version'])));
	Headers::directAddTag(new scriptTag(getUrl('/core/scripts/framework.common.js')));

	if(IS_iOS)
		Headers::directAddTag(new scriptTag(getUrl('/core/scripts/framework.util.ios.js')));

	foreach($config['jquery']['plugins'] as $plugin)
		Headers::directAddTag(new scriptTag('http://jqueryui.com/ui/jquery.ui.'.$plugin.'.js'));
}

if($config['kineticjs']['enabled'])
	Headers::directAddTag(new scriptTag(getKineticJSFilePath(array('/scripts/', '/core/scripts/'), $config['kineticjs']['version'])));

?>