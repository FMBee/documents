<?php
/******************************************************************/
/* auteur: Geraud LACHENY (2014)
/* site web: http://www.geraudlacheny.fr
/******************************************************************/

/** Ameliore la configuration de PHP **/
ini_set('upload_max_filesize', '100M');
ini_set('default_charset', 'utf-8');
ini_set('magic_quotes_runtime', 0);

/** en phase de DEV/TEST **/
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

/** en phase de PRODUCTION **/
error_reporting(0);

/** corrige le charset du serveur APACHE si ce n'est pas trop tard **/
if (!headers_sent())
	header('Content-Type: text/html; charset=utf-8');
?>