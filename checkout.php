<?php

require_once 'common/implementator.php';

$obj = new Implementator();
$obj->checkout($_REQUEST['user_id'], $_REQUEST['products'], $_REQUEST['address']);