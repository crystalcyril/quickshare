<?php

// vim: ts=2 sw=2 sts=2


$conf = parse_ini_file(ROOT_DIR . DS . 'conf' . DS . 'conf.ini');

if ($conf === FALSE) {
	die("failed to read configuration file");
}

//
// make sensible defaults
//

if (!isset($conf['data_dir'])) {
	$conf['data_dir'] = ROOT_DIR . DS . 'data' . DS;
}

if (!isset($conf['upload_dir'])) {
	$conf['upload_dir'] = $conf['data_dir'] . 'uploads' . DS;
}

if (!isset($conf['db_dir'])) {
	$conf['db_dir'] = $conf['data_dir'] . 'db' . DS;
}

// debug
//print_r($conf);


/*

// the data directory
$conf['data_dir'] = 'data/';

// the maximum lifespan of a file can stay. in seconds.
// if a file is older then the specified duration, it will be 
// removed.
$conf['file_max_lifespan'] = 120;
//$conf['file_max_lifespan'] = 300;

//$conf['file_max_lifespan'] = 1;		// instant

// the maximum file size, in bytes, which a user can upload.
$conf['max_file_size'] = 52428800;



// *********************** //
// Advanced Configuration  //
// *********************** //

// the directory which stores the uploaded file.
$conf['upload_dir'] = $conf['data_dir'] . 'uploads/';

// the directory which stores database
$conf['db_dir'] = $conf['data_dir'] . 'db/';

*/


