<?php

require_once 'common/implementator.php';

$obj = new Implementator();
$obj->cancelPutProductToCart($_REQUEST['user_id'], $_REQUEST['product_id'], $_REQUEST['amount']);