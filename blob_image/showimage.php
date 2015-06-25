<?php

require_once 'common/database.php';
require_once 'common/configs.php';

$db = new Database(Configs::$db_config);

$query = "SELECT * FROM img_tbl WHERE id=?";
$data_array = array(6);
$type = "i";

$arr = $db->executePreparedSelect($query, $data_array, $type);

$size = 2 * $arr[0]['img_size'];
header('Content-length: ' . $size);
header('Content-type: ' . $arr[0]['img_type']);
print $arr[0]['img_data'];