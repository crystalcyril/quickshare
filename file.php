<?php

require_once('bootstrap.inc.php');
require_once('lib/file_store.php');



$fileDb = new FileDb();
$fileDb->open();




$file_id = @$_REQUEST['id'];

// do nothing if no file id is given.
if (empty($file_id)) {
	echo "file id missing";
	die();
}
if (!preg_match("/[a-f0-9]/", $file_id)) {
	die("the file ID is not valid");
}


// make sure the file id contains numbers and alphabets only



// find the file

$file_path = $conf['upload_dir'] . $file_id;


// die if not found
if (!file_exists($file_path)) {
	die("file not found. It may be expired or invalid");
}


// find the database record
$originalFilename = $fileDb->getOriginalFilename($file_id);
//die('originalFilename = ' . $originalFilename);


// serve the file

header("Content-type: application/octet-stream");
header("Content-Length: " . filesize($file_path));
header('Content-Disposition: attachment; filename="' . $originalFilename . '"');
set_time_limit(0);
readfile_chunked($file_path);
die($file_path);

$fileDb->close();

exit;
