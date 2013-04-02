<?php

 
/**
 * Generate Globally Unique Identifier (GUID)
 * E.g. 2EF40F5A-ADE8-5AE3-2491-85CA5CBD6EA7
 *
 * @param boolean $include_braces Set to true if the final guid needs
 *                                to be wrapped in curly braces
 * @return string
 */
function generateGuid($include_braces = false, $has_dash = false) {
    if (function_exists('com_create_guid')) {
        if ($include_braces === true) {
            $r = com_create_guid();
        } else {
            $r = substr(com_create_guid(), 1, 36);
        }
        if (!$has_dash) {
        	$r = str_ireplace('-', '', $r);
        }
        return strtolower($r);
    } else {
    	
    		$dash = $has_dash ? '-' : '';
    	
        mt_srand((double) microtime() * 10000);
        $charid = strtolower(md5(uniqid(rand(), true)));
       
        $guid = substr($charid,  0, 8) . $dash .
                substr($charid,  8, 4) . $dash .
                substr($charid, 12, 4) . $dash .
                substr($charid, 16, 4) . $dash .
                substr($charid, 20, 12);
 
        if ($include_braces) {
            $guid = '{' . $guid . '}';
        }
   
        return $guid;
    }
}


// Read a file and display its content chunk by chunk
function readfile_chunked($filename, $retbytes = TRUE) {
	$buffer = "";
	$cnt =0;
	// $handle = fopen($filename, "rb");
	$handle = fopen($filename, "rb");
	if ($handle === false) {
		return false;
	}
	while (!feof($handle)) {
		$buffer = fread($handle, 32 * 1024);	// TODO externalize this
		echo $buffer;
		ob_flush();
		flush();
		if ($retbytes) {
			$cnt += strlen($buffer);
		}
	}
	$status = fclose($handle);
	if ($retbytes && $status) {
		return $cnt; // return num. bytes delivered like readfile() does.
	}
	return $status;
}


function get_max_upload_size() {
	
	$max_upload_size = to_bytes(ini_get('upload_max_filesize'));
	$post_max_size = to_bytes(ini_get('post_max_size'));
	
	$r = $max_upload_size < $post_max_size ? $max_upload_size : $post_max_size;
	
	return $r;
}


function to_bytes($str){
    $val = trim($str);
    $last = strtolower($str[strlen($str)-1]);
    switch($last) {
        case 'g': $val *= 1024;
        case 'm': $val *= 1024;
        case 'k': $val *= 1024;
    }
    return $val;
}


function get_app_base_url() {
  $protocol = (@$_SERVER['HTTPS'] && @$_SERVER['HTTPS'] != "off") ? "https" : "http";
  return $protocol . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/';
}
