<?php

require_once 'XapianWrapper.php';

$x = new XapianWrapper();
$res = $x->index(array());
print_r($res);