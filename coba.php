<?php

//echo hash('sha512', '12345inisalt');

$json = '[{"id": 1, "amount": 2}, {"id": 2, "amount": 2}]';
var_dump(json_decode($json, true));
