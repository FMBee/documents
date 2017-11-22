<?php 
/******************************************************************/
/* auteur: Geraud LACHENY (2014)
/* site web: http://www.geraudlacheny.fr
/******************************************************************/

/****************************************************
 AUTHENTIFICATION
 une fois la base de donnees creee et la chaine de 
 connexion remplie, 
 passer AUTHENTIFICATION a "on" pour activer, 
 passer AUTHENTIFICATION a "off" pour desactiver
****************************************************/
/** passer AUTHENTIFICATION a "on" pour activer, "off" pour desactiver  **/
define('AUTHENTIFICATION', 'off' );

/** adresse de l'hebergement MySQL. **/
define('DB_SERVER', 'serveur_de_la_bdd');

/** nom de la base de donnees. **/
define('DB_NAME', 'nom_de_la_bdd');

/** utilisateur de la base de donnees MySQL. **/
define('DB_USER', 'utilisateur_de_la_bdd');

/** mot de passe de la base de donnees MySQL. **/
define('DB_PASSWORD', 'mdp_de_la_bdd');

/** prefixe de la base de donnees. **/
define('DB_PREFIX', '');

/** jeu de caracteres a utiliser par la base de donnees lors de la creation des tables.   **/
define('DB_CHARSET', 'utf8');

/** clefs uniques d'authentification et salage. **/
define('SALT', 'hg153Fi_0BPqefq545wjoe%');


/****************************************************
 EXPLORATEUR PHP                       
****************************************************/
/** repertoire racine destine a etre parcouru, par defaut : ./documents/   **/
define('BASE', 'documents');


/**  extensions identifiees et associees a une icone placee dans 'images/48/' ou 'images/96/'   **/
$t_extensions_reconnues = array(
	'css',
	'doc',
	'docx',
	'flv',
	'gif',
	'html',
	'jpeg',
	'jpg',
	'mov',
	'mp4',
	'mpeg',
	'pdf',
	'php',
	'png',
	'ppt',
	'pptx',
	'rar',
	'swf',
	'txt',
	'wmv',
	'xls',
	'xlsx',
	'xlsm',
	'zip'
	);

/** correspondance des extensions en francais **/
$t_extensions = array(
	'css'=>'Feuille de style',
	'doc'=>'Microsoft Word',
	'docx'=>'Microsoft Word',
	'flv'=>'Fichier FLV',
	'gif'=>'Image GIF',
	'html'=>'Page web',
	'jpeg'=>'Image JPEG',
	'jpg'=>'Image JPEG',
	'mov'=>'Fichier MOV',
	'mp4'=>'Fichier MP4',
	'mpeg'=>'Fichier MPEG',
	'pdf'=>'Adobe Acrobat',
	'php'=>'Script PHP',
	'png'=>'Image PNG',
	'ppt'=>'Microsoft Power Point',
	'pptx'=>'Microsoft Power Point',
	'rar'=>'Archive WinRar',
	'swf'=>'Fichier SWF',
	'txt'=>'Document texte',
	'wmv'=>'Fichier WMV',
	'xls'=>'Microsoft Excel',
	'xlsx'=>'Microsoft Excel',
	'xlsm'=>'Microsoft Excel',
	'zip'=>'Archive WinZip'
);

/**  repertoires a ne pas montrer a l'internaute en sus des repertoires caches commencant par un . => ., .., .htaccess, etc... **/
$t_repertoires_sensibles = array(
	'lost+found',
	'sources',
	'source',
	'corbeille'
);

/**  listing des fichiers a ne pas montrer a l'internaute en sus les fichiers caches **/
$t_fichiers_sensibles = array(
	'index.php'
);

/**  listing des extensions des fichiers a ne pas montrer a l'internaute **/
$t_extensions_sensibles = array(
	'.bak',
	'.source',
	'.sources'
);

?>