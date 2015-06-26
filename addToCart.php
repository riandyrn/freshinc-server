<?php

require_once 'common/implementator.php';

$obj = new Implementator();
$obj->addToCart($_REQUEST['product_id'], $_REQUEST['amount'], $_REQUEST['user_id']);