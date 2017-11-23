<?php 
/******************************************************************
auteur: Geraud LACHENY (2014)
site web: http://www.geraudlacheny.fr
/******************************************************************/ 

session_start();

require_once('config/config.inc.php');
require_once('config/settings.inc.php');
require_once('classes/User.php');
require_once('lib/functions.php');

unset($_SESSION['auth']);
setcookie('identifiant');
setcookie('mdp');

redirect('login.php');

?>