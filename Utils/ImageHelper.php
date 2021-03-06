<?php

namespace Utils;

class ImageHelper
{

public static function base64_decode($base64_string, $output_file)
 {

    $ifp = fopen($output_file, "wb"); 
    
    try
    {	
		fwrite($ifp, base64_decode($base64_string)); 
    	fclose($ifp); 
	}
	catch(Exception $e)
	{
		return null;
	}

	return $output_file; 
 }

public static function base64_encode($input_file)
{
	try
	{
	$encoded_data = base64_encode(file_get_contents($input_file));
	}
	catch(Exception $e)
	{
		return null;
	}

	return $encoded_data;
}


public static function file_size($input_file)
{
	return filesize($input_file);
}

}


?>