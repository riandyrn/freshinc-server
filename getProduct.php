<?php

require_once 'common/implementator.php';

$obj = new Implementator();
$obj->getProduct($_GET["product_id"]);