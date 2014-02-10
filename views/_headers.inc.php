<?php if(!defined('BASEPATH')) die('No direct script access allowed');

Headers::directAddTag(new metaTag('keywords', ''));

$description = 'Description à modifier';

Headers::directAddTag(new metaTag('description', $description));

$tag = new metaTag('og:description', $description);
$tag->setParamName('property');
Headers::directAddTag($tag);
unset($tag);

Headers::directAddTag(new metaTag('robots', 'index,all,follow'));
Headers::directAddTag(new metaTag('category', 'chat, dialogue en direct, rencontres, mail, email'));
Headers::directAddTag(new metaTag('apple-mobile-web-app-capable', 'no'));
Headers::directAddTag(new metaTag('apple-mobile-web-app-status-bar-style', 'black'));

Headers::directAddTag(new linkTag(getUrl('styles/global.css')));
Headers::directAddTag(new linkTag(getUrl('styles/loaders'.$config['url_suffix'].'?initdb=0'), null, null, 'text/css'));
Headers::directAddTag(new linkTag(getUrl('images/menulogo.png'), 'icon', null, 'image/png'));

Headers::directFlush();

?>
	<script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', '']);
	  _gaq.push(['_setDomainName', '']);
	  _gaq.push(['_setAllowLinker', true]);
	  _gaq.push(['_trackPageview']);

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	</script>
