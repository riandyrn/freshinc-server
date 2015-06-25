<?php

require_once 'common/implementator.php';

$obj = new Implementator();
$obj->login($_REQUEST['username'], $_REQUEST['password']);