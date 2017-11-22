<?php

require_once 'XapianWrapper.php';
$x = new XapianWrapper();
$params = array(
	'items' => array('foo'),
);
$res = $x->delete($params);
print_r($res);