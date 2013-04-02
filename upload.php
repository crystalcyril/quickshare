<?php

//
// To see the PHP example in action, please do the following steps.
//
// 1. Open test/js/uploader-demo-jquery.js file and change the request.endpoint
// parameter to point to this file.
//
//  ...
//  request: {
//    endpoint: "../server/php/example.php"
//  }
//  ...
//
// 2. As a next step, make uploads and chunks folders writable.
//
// 3. Open test/jquery.html to see if everything is working correctly,
// the uploaded files should be going into uploads folder.
//
// 4. If the upload failed for any reason, please open the JavaScript console,
// if this does not help please read the excellent documentation we have for you.
//
// https://github.com/valums/file-uploader/blob/master/readme.md
//


// Include the uploader class
require_once('bootstrap.inc.php');

require_once('lib/qqFileUploader.php');
require_once('lib/file_store.php');


$fileDb = new FileDb();
$fileDb->open();



$uploader = new qqFileUploader();

// Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
$uploader->allowedExtensions = array();

// Specify max file size in bytes.
$uploader->sizeLimit = $conf['max_file_size'];

// Specify the input name set in the javascript.
$uploader->inputName = 'qqfile';

// If you want to use resume feature for uploader, specify the folder to save parts.
$uploader->chunksFolder = 'chunks';

// Call handleUpload() with the name of the folder, relative to PHP's getcwd()
$newFilename = generateGuid();
$result = $uploader->handleUpload($conf['upload_dir'], $newFilename);

// To save the upload with a specified name, set the second parameter.
// $result = $uploader->handleUpload('uploads/', md5(mt_rand()).'_'.$uploader->getName());

// To return a name used for uploaded file you can use the following line.
$result['uploadName'] = $uploader->getUploadName();

// add the URL
if ($result['success']) {
	
	$originalFilename = $uploader->getName();
	$fileDb->saveFile($newFilename, $originalFilename);


	// add the download URL
	$result['downloadUrl'] = get_app_base_url() . 'file.php?id=' . $newFilename;
	
	// tell the user how long this file can stay
	$result['fileLife'] = $conf['file_max_lifespan'];
	
}


header("Content-Type: text/plain");
echo json_encode($result);

$fileDb->close();