<?php
header('Content-Type: text/css;charset=UTF-8');
?>
/*
|--------------------------------------------------------------------------
| Loaders styles
|--------------------------------------------------------------------------
*/
<?php

if($handle = opendir(getPath('/images/')))
{
	while(($file = readdir($handle)) !== false)
	{
		$filePath = getPath('/images/'.$file);
		if(filetype($filePath) == 'file' && preg_match('#^loader-([^.]*)\.gif$#', $file, $matches))
		{
			list($width, $height, $type, $attr) = getimagesize($filePath);

			echo ".loader-".$matches[1]." {\n";
			echo "\tbackground-image: url('".getUrl('/images/'.$file)."');\n";
			echo "\tbackground-repeat: no-repeat;\n";
			echo "\tbackground-position: left center;\n";
			echo "\tpadding-left: ".($width + 4)."px;\n";
			echo "\tmargin-right: 4px;\n";
			echo "\tline-height: ".($height + 0)."px;\n";
			//echo "\tborder: 1px solid black;\n";
			echo "}\n\n";
		}
	}
	closedir($handle);
}
?>