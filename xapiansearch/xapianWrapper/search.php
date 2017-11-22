<?php

require_once 'XapianWrapper.php';

$x = new XapianWrapper();
$params = array('search' => 'foo');
$res = $x->search($params);
print_r($res);