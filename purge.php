<?php

require_once('bootstrap.inc.php');
require_once('lib/file_store.php');


// get parameters

$is_instant = false;

if (php_sapi_name() == 'cli') {
	// cli application
	while (($arg = array_shift($argv)) !== null) {
		if ('now' == $arg) {
			$is_instant = true;
		}
	}
	
} else {
	// from web
	$is_instant = array_key_exists('now', $_REQUEST) ? true : false;
}

//echo "is_instant = " . ( $is_instant ? 'true' : 'false');

$fileDb = new FileDb();
$fileDb->open();


$target_lifespan = $conf['file_max_lifespan'];
if ($is_instant) {
	$target_lifespan = 0;
}

$oldFiles = $fileDb->findOldFiles($target_lifespan);

if (count($oldFiles) > 0) {
	
	$deleteCount = 0;
	foreach ($oldFiles as $oldFile) {
	
		$id = $oldFile['id'];
	
		echo "old file: " . $id . " (age: " . $oldFile['age'] . " seconds)<br/>";
		
		// delete the physical file first
		$file_path = 'data/uploads/' . $id;
		if (!file_exists($file_path) || unlink($file_path)) {
			
			// if successful, delete the file in database as well.
			if ($fileDb->deleteFile($id)) {
				$deleteCount++;
			}
			
		}
		
	}	// for each old files
	echo "removed $deleteCount file(s)";
	
}


$fileDb->close();
