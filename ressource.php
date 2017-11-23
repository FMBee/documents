<?php 
/******************************************************************/
/* auteur: Geraud LACHENY (2014)
/* site web: http://www.geraudlacheny.fr
/******************************************************************/

// le chemin complet du fichier
$fullPath = stripslashes($_GET['id']);

// verification du non-envoi des entetes
if( headers_sent() )
die('Les ent&ecirc;tes sont dej&agrave; envoy&eacute;es');

// requis en fonction des navigateurs
if(ini_get('zlib.output_compression'))
ini_set('zlib.output_compression', 'Off');

if( file_exists($fullPath) ) { 
	// parse Info / obtenir l'extension
	$fsize = filesize($fullPath);
	$path_parts = pathinfo($fullPath);
	$ext = strtolower($path_parts["extension"]);
	 
	// determine le Content Type
	switch ($ext) {
		case "pdf": $ctype="application/pdf"; break;
		case "exe": $ctype="application/octet-stream"; break;
		case "zip": $ctype="application/zip"; break;
		case "docx" :
		case "doc": $ctype="application/msword"; break;
		case "xlsx" :
		case "xls": $ctype="application/vnd.ms-excel"; break;
		case "pptx" :
		case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
		case "gif": $ctype="image/gif"; break;
		case "png": $ctype="image/png"; break;
		case "jpeg":
		case "jpg": $ctype="image/jpg"; break;
		default: $ctype="application/force-download";
	}

	header("Pragma: public"); // requis
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false); // requis
	header("Content-Type: $ctype");
	header("Content-Disposition: attachment; filename=\"" . basename($fullPath)."\";" );
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: " . $fsize);
	ob_clean();
	flush();
	readfile($fullPath);

}
else die('Fichier non trouv&eacute;');
?>