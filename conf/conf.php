<?php

// the data directory
$conf['data_dir'] = 'data/';

// the maximum lifespan of a file can stay. in seconds.
// if a file is older then the specified duration, it will be 
// removed.
$conf['file_max_lifespan'] = 7 * 24 * 60 * 60;
//$conf['file_max_lifespan'] = 300;

//$conf['file_max_lifespan'] = 1;		// instant

// the maximum file size, in bytes, which a user can upload.
$conf['max_file_size'] = 1024 * 1024 * 1024;



// ***********************//
// Advanced Configuration //
// ***********************//

// the directory which stores the uploaded file.
$conf['upload_dir'] = $conf['data_dir'] . 'uploads/';

// the directory which stores database
$conf['db_dir'] = $conf['data_dir'] . 'db/';
