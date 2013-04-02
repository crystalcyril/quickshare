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
?>
<html>
	<head>
		<title>File Listing</title>
	</head>
	<body>
<?php

$target_lifespan = $conf['file_max_lifespan'];
if ($is_instant) {
	$target_lifespan = 0;
}

$oldFiles = $fileDb->findOldFiles(0);

if (count($oldFiles) > 0) {
?>
		<table>
			<thead>
				<th></th>
				<th>ID</th>
				<th>Age (sec)</th>
				<th>Exists?</th>
				<th>Expired?</th>
			</thead>
			<tbody>
<?php
	$count = 0;
	foreach ($oldFiles as $oldFile) {
	
		$id = $oldFile['id'];
		$expired = $oldFile['age'] >= $target_lifespan;
		$file_path = 'data/uploads/' . $id;
		$is_file_exists = file_exists($file_path);
	
		// output html
?>
			<tr>
				<td><?php echo $count + 1;?></td>
				<td><?php echo $id;?></td>
				<td><?php echo $oldFile['age'];?></td>
				<td><?php echo $is_file_exists ? 'Yes' : '<span style="color: red;">No</span>';?></td>
				<td><?php echo $expired ? '<span style="color: red;">Yes</span>' : 'No';?></td>
			</tr>
<?php
		
		$count++;
	}	// for each old files
}
?>
			</tbody>
		</table>
	</body>
</html>
<?php
$fileDb->close();
?>