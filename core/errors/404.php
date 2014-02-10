<?php if(!defined('BASEPATH')) die('No direct script access allowed');

$luckuDude = rand(0, 100);

$errorimgs = array();

if($handle = opendir(getPath('/core/images/404/')))
{
	while(($file = readdir($handle)) !== false)
	{
		$filePath = getPath('/core/images/404/'.$file);
		if(filetype($filePath) == 'file' && preg_match('#^[^_].+\.(jp(e)?g|png|gif|bmp)$#', $file))
			array_push($errorimgs, $file);
	}
	closedir($handle);
}

if($luckuDude < (100 - $config['behavior']['404_RANDOM_IMAGE']) || count($errorimgs) == 0)
{
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL <?php echo (isset($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : dirname($_SERVER['REQUEST_URI']).'/'); ?> was not found on this server.</p>
</body></html>
<?php
	die();
}

$img = $errorimgs[rand(0, count($errorimgs) - 1)];
$tooltip = 'Cliquez pour revenir à la page d\'accueil';

?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body style="padding: 0px; margin: 0px; cursor: pointer; height: 100%;" alt="<?php echo $tooltip; ?>" title="<?php echo $tooltip; ?>">
<div style="text-align: center; cursor: pointer;" alt="<?php echo $tooltip; ?>" title="<?php echo $tooltip; ?>">
	<img src="<?php echo getUrl('/images/404/'.$img); ?>" onclick="window.location='<?php echo base_url(); ?>';" style="border: 0px; cursor: pointer;" alt="<?php echo $tooltip; ?>" title="<?php echo $tooltip; ?>" />
</div>
</div>
</body></html>