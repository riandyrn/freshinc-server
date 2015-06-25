<?php

require_once 'common/database.php';
require_once 'common/configs.php';

$db = new Database(Configs::$db_config);

$table_name = 'img_tbl';

$tmpName = $_FILES['imgfile']['tmp_name'];
$fp = fopen($tmpName, 'r'); // open a file handle of the temporary file
$imgContent = fread($fp, filesize($tmpName)); // read the temp file
fclose($fp); // close the file handle

$data_array = array(
	'img_name' => $_FILES['imgfile']['name'],
	'img_type' => $_FILES['imgfile']['type'],
	'img_size' => $_FILES['imgfile']['size'],
	'img_data' => $imgContent
);

$type = 'ssis';

$db->executePreparedInsert($table_name, $data_array, $type);