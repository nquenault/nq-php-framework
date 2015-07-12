<?php if(!defined('BASEPATH')) die('No direct script access allowed');

function clearSQLi($value)
{
	return mysql_real_escape_string($value);
}

/**
 * No XSS protection needed
 * Prevention is made by '../libraries/framework.security.xss.php'
 */
function getVar($varname, $default = false, $regexMatch = '.*', $regexOptions = '', $regexDelimiter = '/')
{
	if(!isset($_REQUEST[$varname]))
		return $default;

	if(!preg_match($regexDelimiter.$regexMatch.$regexDelimiter.$regexOptions, $_REQUEST[$varname]))
		return $default;
	else
		return $_REQUEST[$varname];
}

function getUrl($url, $absolute = false)
{
	global $config;

	$returnUrl = '';

	if($absolute)
		$returnUrl = 'http://'.$_SERVER['SERVER_NAME'].($_SERVER['SERVER_PORT'] != 80 ? ':'.$_SERVER['SERVER_PORT'] : '');

	return preg_replace('#(\x2F|\x5C)+#', '/', BASEURL.'/'.
		($config['anchors_ajax_navigation'] && $config['url_suffix'] == pathinfo($url, PATHINFO_EXTENSION) ? '#' : '').
		$url);
}

function site_url($path = '', $absolute = false, $forceAnchor = null)
{
	global $config;
	
	$returnUrl = '';

	if($forceAnchor === null)
		$forceAnchor = $config['anchors_ajax_navigation'];

	if($absolute)
		$returnUrl = 'http://'.$_SERVER['SERVER_NAME'].($_SERVER['SERVER_PORT'] != 80 ? ':'.$_SERVER['SERVER_PORT'] : '');

	return $returnUrl.preg_replace('#(\x2F|\x5C)+#', '/', BASEURL.'/'.
		($forceAnchor ? '#' : '').
		$path.($path && $path[strlen($path) - 1] != '/' ? $config['url_suffix'] : ''));
}

function base_url($absolute = false) { return site_url('/', $absolute); }

function redirect($url, $forceAnchor = false)
{
	global $config;

	if($forceAnchor === null)
		$forceAnchor = $config['anchors_ajax_navigation'];
		
	$uri = (strpos($url, '://') === false ? site_url($url, true, false) : ($forceAnchor ? '#' : '').$url);

	header('HTTP/1.1 301 Moved Permanently');
	header('Location: '.$uri);
	die('<html><head><title>301 Moved Permanently</title></head><body><a href="'.$uri.'">moved here</a></body></html>');
}

function loadmodel($model, $func = 'index')
{
	global $config;

	$model = str_replace('.', '', $model);

	if(!file_exists(getPath('/models/'.$model.'.php')))
		$model = $config['default_page'];

	require_once getPath('/models/'.$model.'.php');

	if(class_exists($model) && in_array($func, get_class_methods($model)))
	{
		$handle = new $model();
		$handle->$func();
	}
	else
		redirect(); //loadmodel($config['default_page']);
}

function loadview($view, $vars = array())
{
	global $config;
	
	Headers::directFlush();
	Headers::directClean();

	foreach($vars as $varname => $value)
		$$varname = $value;
	
	include getPath('/views/'.$view.'.php');
}

function dbquery($dbname, $query, $values = array(), &$error = false)
{
	global $db;
	$result = array();
	$values = $values == null || !is_array($values) ? array() : $values;

	if(!isset($db[$dbname]))
	{
		$error = 'La connexion ['.$dbname.'] n\'a pas été trouvée';
		return $result;
	}

	if(count($values) != 0)	foreach($values as $value)
		$query = preg_replace('/%s/', clearSQLi($value), $query, 1);

	try
	{
		$dbresult = @mysql_query($query);

		if($dbresult === false)
		{
			$errors = mysql_error();

			if(is_array($errors))
				foreach($errors as $t_error)
					if($t_error['message'])
						$error = ($error ? "\n" : '').$t_error['message'];
			else
				$error = $errors;

			return array();
		}
		elseif($dbresult !== true)
			while($row = mysql_fetch_array($dbresult, MYSQL_ASSOC))
				array_push($result, $row);
	}
	catch (Exception $ex)
	{
		$error = $ex->getMessage();
	}

	return $result;
}

function loadDirectory($path, $pattern = '#^[^_].+\.php$#')
{
	global $config;
	global $db;

	$result = true;

	if($handle = opendir(getPath($path)))
	{
		while(($file = readdir($handle)) !== false)
		{
			$filePath = getPath($path.'/'.$file);
			if(filetype($filePath) == 'file' && preg_match($pattern, $file))
				try
				{
					require_once $filePath;
				}
				catch(Exception $err)
				{
					$result = false;
				}
		}
		closedir($handle);
	}

	return $result;
}

function getJQueryFilePath($scriptsPaths, $version)
{
	if(!is_array($scriptsPaths))
		$scriptsPaths = array($scriptsPaths);

	foreach($scriptsPaths as $scriptsPath)
		if($handle = opendir(getPath($scriptsPath)))
		{
			while(($file = readdir($handle)) !== false)
			{
				if(preg_match('#jquery-'.strhex($version).'#', $file))
				{
					closedir($handle);
					return getUrl($scriptsPath).$file;
				}
			}
			closedir($handle);
		}

	return 'http://ajax.googleapis.com/ajax/libs/jquery/'.$version.'/jquery.min.js';
}

function getKineticJSFilePath($scriptsPaths, $version)
{
	if(!is_array($scriptsPaths))
		$scriptsPaths = array($scriptsPaths);

	foreach($scriptsPaths as $scriptsPath)
		if($handle = opendir(getPath($scriptsPath)))
		{
			while(($file = readdir($handle)) !== false)
			{
				if(preg_match('#kinetic-v'.strhex($version).'#', $file))
				{
					closedir($handle);
					return getUrl($scriptsPath).$file;
				}
			}
			closedir($handle);
		}
	
	return 'http://d3lp1msu2r81bx.cloudfront.net/kjs/js/lib/kinetic-v'.$version.'.min.js';
}

function strhex($str)
{
	$result = '';

	for($i=0;$i<strlen($str);$i++)
		$result .= '\x'.strtoupper(dechex(ord($str[$i])));

	return $result;
}

function getMimeType($path)
{
	$type = false;

	if(preg_match('/\.([^\.]+)$/', $path, $matches))
		$path = $matches[1];

	switch(strtolower($tpath))
	{
		case 'css': $type = 'text/css'; break;
		case 'js': $type = 'text/javascript'; break;
		case 'pdf': $type = 'application/pdf'; break;
		case 'psd': $type = 'image/vnd.adobe.photoshop'; break;
		case 'htm':
		case 'html': $type = 'text/html'; break;
		case 'mpeg':
		case 'mpg':
		case 'mpe':
		case 'mp3': $type = 'video/mpeg'; break;
		case 'qt':
		case 'mov': $type = 'video/quicktime'; break;
		case 'avi': $type = 'video/msvideo'; break;
		case 'txt': $type = 'text/plain'; break;
		case 'swf': $type = 'application/x-shockwave-flash'; break;
		case 'flv': $type = 'video/x-flv'; break;
		case 'png': $type = 'image/png'; break;
		case 'jpe':
		case 'jpeg':
		case 'jpg': $type = 'image/jpeg'; break;
		case 'gif': $type = 'image/gif'; break;
		case 'bmp': $type = 'image/bmp'; break;
		case 'ico': $type = 'image/vnd.microsoft.icon'; break;
		case 'tiff':
		case 'tif': $type = 'image/tiff'; break;
		case 'svg':
		case 'svgz': $type = 'image/svg+xml'; break;
		case 'zip': $type = 'multipart/x-zip'; break;
		case 'exe':
		case 'msi': $type = 'application/x-msdownload'; break;
		case 'cab': $type = 'application/vnd.ms-cab-compressed'; break;
		case 'doc': $type = 'application/msword'; break;
		case 'rtf': $type = 'application/rtf'; break;
		case 'xls': $type = 'application/vnd.ms-excel'; break;
		case 'ppt': $type = 'application/vnd.ms-powerpoint'; break;
		case 'odt': $type = 'application/vnd.oasis.opendocument.text'; break;
		case 'ods': $type = 'application/vnd.oasis.opendocument.spreadsheet'; break;
	}

	// from here, it take a f* long time to exec ! :o

	if(!$type && function_exists('finfo_file'))
	{
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_file($finfo, $file);
        finfo_close($finfo);
    }
	elseif(!$type && file_exists('upgradephp/ext/mime.php'))
	{
        require_once 'upgradephp/ext/mime.php';
        $type = mime_content_type($file);
    }

    if(!$type || in_array($type, array('application/octet-stream', 'text/plain')))
	{
        $secondOpinion = exec('file -b --mime-type '.escapeshellarg($path), $foo, $returnCode);
        if ($returnCode === 0 && $secondOpinion)
            $type = $secondOpinion;
    }

    if(file_exists('upgradephp/ext/mime.php') && (!$type || in_array($type, array('application/octet-stream', 'text/plain'))))
	{
        require_once 'upgradephp/ext/mime.php';

        $exifImageType = exif_imagetype($path);
        if ($exifImageType !== false)
            $type = image_type_to_mime_type($exifImageType);
    }

	return $type ? $type : 'application/octet-stream';
}

function array2Dictionary($array, $keyName)
{
	$result = array();

	if(!is_array($array))
		return $result;

	foreach($array as $key => $item)
		$result[
			isset($item[$keyName]) && $item[$keyName] && !isset($result[$item[$keyName]]) ?
				$item[$keyName] : $key
		] = $item;

	return $result;
}

?>
