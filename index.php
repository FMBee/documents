<?php 
/******************************************************************
auteur: Geraud LACHENY (2014)
site web: http://www.geraudlacheny.fr
/******************************************************************/


require_once('config/config.inc.php');
require_once('config/settings.inc.php');
require_once('lib/functions.php');
require_once('lib/simplesearch.php');

if( AUTHENTIFICATION == 'on' ){

	require_once('classes/User.php');

	session_start();

	// si l'utilisateur n'est pas authentifie, il est renvoye sur la page de login
	if( !isset($_SESSION['auth']) )
		redirect('login.php');
}

// par defaut, trier par...
$orderby = 'nom';
if( isset($_GET['orderby']) && in_array($_GET['orderby'], array('nom', 'date', 'taille', 'type')) )
	$orderby = $_GET['orderby'];
  
// par defaut, tri ascendant 
$order = 'asc';
if( isset($_GET['order']) && in_array($_GET['order'], array('asc', 'desc')) ) 
	$order = $_GET['order'];


// par defaut, repertoire courant
$repertoire_courant = BASE;
if( isset($_GET['p']) && !empty($_GET['p']) ) {
	$repertoire_courant =  secureDir($_GET['p']);
	
	$pos = strpos($repertoire_courant, BASE);
	if( (0 != $pos) or ($pos === false) ) {
		$repertoire_courant = BASE;
	}
}

//debug($_POST);

if ( isset($_GET['apicall']) ) {

	$titles  = $_GET['titlemode'] == 'name' ? true : false;
	$query 	 = $_GET['query'];
}
else{
	$titles  = !empty($_POST['titlemode']) ? ($_POST['titlemode'] == 'name') : true;
	$query 	 = !empty($_POST['query']) ? $_POST['query'] : '';
}

if ($titles) {
	
	$results = listAllDirContent(BASE);
	asort($results);
}
else {
	if( !empty($query) ) {
      
        $results = simpleSearch($query);
    	$results = filterSearch($results);
    }
    else{
        $results = '';
    }
}
// debug($results, true);

if ( isset($_GET['apicall']) ) {

	header('Content-Type: application/json; charset=UTF-8');
	
	$a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');
	$b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
	$query = strtoupper(str_replace($a, $b, $query));

	foreach ( $results as &$ligne ) {
		
		
		if ( $titles ) {
			
			array_walk($ligne, 'walkNormalizeString');	// codage UTF8 

			$nom = strtoupper(str_replace($a, $b, $ligne['nom']));
			
			if ( strpos($nom, $query) ) {	// recherche trouvée
				
				if ( $ligne['type'] == 'dossier' ) {
					
					$ligne['url'] = 	 $_SERVER['SERVER_NAME']
										.$_SERVER['PHP_SELF']
										.'?p='
										.rawurlencode($ligne['url']);
  				}
				$data[] = $ligne;
			}
		}
		else{
			$champs = $ligne['champs'];

			array_walk($champs, 'walkNormalizeString');	// codage UTF8 
			
			$data[] = $champs;
		}
	}
	$return = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	
	if (! $return) {
		
		echo 'Erreur d\'encodage JSON - code:' .json_last_error();
	}
	else{
		echo $return;
	}	
	exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8" />
	<title>Docs Groupe Garrigue</title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />	
	<!-- <meta name="robots" content="noindex,nofollow" />-->
	
	<!-- JQUERY -->
<!-- 	<script src="js/jquery-1.11.0.min.js"></script>	 -->

	<!-- Jquery, Datatables, Bootstrap  -->
	<link rel="stylesheet" type="text/css" href="js/datatables.min.css"/>
	<script type="text/javascript" src="js/datatables.min.js"></script>
	
	<!-- CSS -->
	<link rel="stylesheet" href="themes/original/css/normalize.css" />
	<link rel="stylesheet" href="themes/original/css/screen.css" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	
	<!-- SHADOWBOX -->
	<script src="js/shadowbox-3.0.3/shadowbox.js"></script>
	<link rel="stylesheet" type="text/css" href="js/shadowbox-3.0.3/shadowbox.css" />
	<script>
		Shadowbox.init();
	</script>
	
	<!-- SCRIPTS DIVERS -->		
	<script>	
		$(document).ready(function(){
			$(".element2").hover( 
				function () { $(this).addClass("hover"); }, 
				function () { $(this).removeClass("hover"); }
			);

			$(".element2").click(function () { 
				$(".element2").removeClass("stay");
				$(this).addClass("stay");
			});
			$(".image").click(function () { 
				$(".element2").removeClass("stay");
				$(this).parent().addClass("stay");
			});
			$(".fichier").click(function () { 
				$(".element2").removeClass("stay");
				$(this).parent().addClass("stay");
			});			
			$(".inconnu").click(function () { 
				$(".element2").removeClass("stay");
				$(this).parent().addClass("stay");
			});
			
			/*$(".repertoire").click(function(){		
				window.location.href = 'index.php?p='+$(this).attr("id").slice(8);
			});
			$(".image").click(function(){		
				window.location.href = 'ressource.php?id='+$(this).attr("id").slice(8);
			});
			$(".fichier").click(function(){		
				window.location.href = 'ressource.php?id='+$(this).attr("id").slice(8);
			});
			$(".inconnu").click(function(){		
				window.location.href = 'ressource.php?id='+$(this).attr("id").slice(8);
			});*/
			
			
			$(".repertoire").dblclick(function(){		
				window.location.href = 'index.php?p='+$(this).attr("id").slice(8);
			});
			/*
			$(".image").dblclick(function(){		
				window.location.href = 'ressource.php?id='+$(this).attr("id").slice(8);
			});
			$(".fichier").dblclick(function(){		
				window.location.href = 'ressource.php?id='+$(this).attr("id").slice(8);
			});
			$(".inconnu").dblclick(function(){		
				window.location.href = 'ressource.php?id='+$(this).attr("id").slice(8);
			});*/
			
		});
	</script>
	
	<!-- Piwik -->
<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//10.106.76.115/piwik/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', '1']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<noscript><p><img src="//10.106.76.115/piwik/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->
	
</head>
 
<body>

	<?php include 'search-modal.php'; ?>
	  
	<header id="entete">
		<div class="contenu">
			<div id="logo">
				<img alt="logo" src="themes/original/images/logo.png" />
			</div><!-- fin logo -->
			
			<?php if(AUTHENTIFICATION == 'on'): ?>
			<div id="logout">
				<span style="color:#AAA;"><?= $_SESSION['auth'] ?></span>
				<a title="Se déconnecter" href="deconnexion.php" onclick="return confirm('Etes-vous sur de vous déconnecter ?')"><img alt="se deconnecter" src="themes/original/images/logout.png" /></a>
			</div><!--fin logout -->
			<?php endif; ?>
			
			<br /><br /><br /><br /><br /><br />
			<div>
				<form action="index.php" method="post" id="search">
					
<!-- 						<div class="checkbox"> -->
<!-- 						  <label><input type="checkbox" name="titlemode" checked disabled>Par nom</label> -->
<!-- 						</div> -->
					<label class="radio-inline"><input type="radio" name="titlemode" value="name" checked>Par nom</label>
					<label class="radio-inline"><input type="radio" name="titlemode" value="content">Par contenu</label>	
					<br />
					<div class="input-group col-xs-6">
					    <input type="text" class="form-control" name="query" id="query" placeholder="Tapez votre recherche">
					    <div class="input-group-btn">
					      <button class="btn btn-default" type="submit">
					        <i class="glyphicon glyphicon-search"></i>
					      </button>
					    </div>
					</div>
				</form>
			</div>
		</div><!-- fin contenu -->
	</header>
	
	<nav id="fildariane">
		<div class="contenu">
			<ul>
				<?php 
				$noeud = 0;
				$t_fildariane = array();
				
				foreach(getBreadcrumb($repertoire_courant) as $element) { 
					
					$noeud++;
					$t_fildariane[] = $element;
					$p = implode("/", $t_fildariane);
					// j'affiche la racine
					if($noeud == 1) { ?>
					
						<li class="racine"><a href="index.php"><img alt="racine" src="themes/original/images/repertoire.png" />Docs Groupe Garrigue</a></li>
						<?php $nb_elements = count(getBreadcrumb($repertoire_courant));
							if ($nb_elements > 0) {?>
								<li><img alt="&gt;" src="themes/original/images/arrow.gif" /></li>
						<?php }						
					}
					else {
						echo "<li><a href=\"index.php?p=" , rawurlencode($p) , "&amp;orderby=nom&amp;order=". $order ."\">" , normalizeString($element) , "</a></li>";
						// s'il n'y a pas d'elements enfants, on n'affiche pas le marqueur
						if(hasChildren($p)) { ?>
							<li><img alt="&gt;" src="themes/original/images/arrow.gif" /></li>
						<?php }
					}
				} ?>
			</ul>
		</div><!-- fin contenu -->
	</nav>
	
	<div id="vues">
		<div class="contenu">

		</div><!-- fin contenu -->
	</div><!-- fin vues -->
	
	
	<div id="corps">
		
		<div class="contenu">		
		
			<div id="arborescence">
			
				<ul>
					<li class="racine"><a href="index.php"><img alt="racine" src="themes/original/images/repertoire.png" />Docs Groupe Garrigue</a></li>
					<?php foreach(getDir(BASE) as $element): ?>
						<li>
							<a href="index.php?p=<?= rawurlencode(BASE . "/" . $element['nom']) ?>">
								<img alt="repertoire" src="themes/original/images/repertoire.png" />
								<span <?= rawurlencode(BASE . "/" . $element['nom']) == rawurlencode($repertoire_courant) ? 'style="font-weight:bold"' : '' ?> title="<?= normalizeString($element['nom']) ?>"><?= shortenString(normalizeString($element['nom']), 37) ?></span><!-- CODE:  -->
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			
			</div><!-- fin arborescence -->
	
	
			<div id="contenu_repertoire">
		
				<table id="tab_element2">
					<thead>
						<tr>
							<th style="width:49%;">
								<a href="index.php?p=<?= rawurlencode($p) ?>&amp;orderby=nom&amp;order=<?= $order=='asc' ? 'desc' : ($order=='desc' ? 'asc' : 'asc') ?>">Nom</a>
								<a href="index.php?p=<?= rawurlencode($p) ?>&amp;orderby=nom&amp;order=asc"><img alt="asc" src="themes/original/images/<?= $orderby=='nom' && $order=='asc' ? 'asc.png' : 'asc2.png' ?>" />
								<a href="index.php?p=<?= rawurlencode($p) ?>&amp;orderby=nom&amp;order=desc"><img alt="desc" src="themes/original/images/<?= $orderby=='nom' && $order=='desc' ? 'desc.png' : 'desc2.png' ?>" />
							</th>
<!--								<a href="index.php?p=<?= rawurlencode($p) ?>&amp;orderby=type&amp;order=<?= $order=='asc' ? 'desc' : ($order=='desc' ? 'asc' : 'asc') ?>">Type</a>
								<a href="index.php?p=<?= rawurlencode($p) ?>&amp;orderby=type&amp;order=asc"><img alt="asc" src="themes/original/images/<?= $orderby=='type' && $order=='asc' ? 'asc.png' :  'asc2.png'  ?>" />
								<a href="index.php?p=<?= rawurlencode($p) ?>&amp;orderby=type&amp;order=desc"><img alt="desc" src="themes/original/images/<?= $orderby=='type' && $order=='desc' ? 'desc.png' :  'desc2.png' ?>" />
							</th>-->
							<th style="width:15%;">
								<a href="index.php?p=<?= rawurlencode($p) ?>&amp;orderby=taille&amp;order=<?= $order=='asc' ? 'desc' : ($order=='desc' ? 'asc' : 'asc') ?>">Taille</a>
								<a href="index.php?p=<?= rawurlencode($p) ?>&amp;orderby=taille&amp;order=asc"><img alt="asc" src="themes/original/images/<?= $orderby=='taille' && $order=='asc' ? 'asc.png' : 'asc2.png'  ?>" />
								<a href="index.php?p=<?= rawurlencode($p) ?>&amp;orderby=taille&amp;order=desc"><img alt="desc" src="themes/original/images/<?= $orderby=='taille' && $order=='desc' ? 'desc.png' : 'desc2.png' ?>" />
 							</th> 
							<th style="width:16%;">
								<a href="index.php?p=<?= rawurlencode($p) ?>&amp;orderby=date&amp;order=<?= $order=='asc' ? 'desc' : ($order=='desc' ? 'asc' : 'asc') ?>">Modifi&eacute; le</a>
								<a href="index.php?p=<?= rawurlencode($p) ?>&amp;orderby=date&amp;order=asc"><img alt="asc" src="themes/original/images/<?= $orderby=='date' && $order=='asc' ? 'asc.png' : 'asc2.png' ?>" />
								<a href="index.php?p=<?= rawurlencode($p) ?>&amp;orderby=date&amp;order=desc"><img alt="desc" src="themes/original/images/<?= $orderby=='date' && $order=='desc' ? 'desc.png' : 'desc2.png' ?>" />
							</th>
						</tr>
					</thead>
					
					<tbody>
						
					<?php 
					$contenu_repertoire = listDir($repertoire_courant);
//debug($contenu_repertoire);					
					if( isset($contenu_repertoire) && !empty($contenu_repertoire) ) {
						
							foreach($contenu_repertoire as $element) {
							
								//if ( ($element['extension'] != 'cache') || (substr($element['nom'], -6) != '.cache' )) { 
								
								echo '<tr class="element2">';
								
								switch($element['type']) {
								
									case 'repertoire': ?>
									
										<td class="element2_1 repertoire" id="element_<?= rawurlencode($repertoire_courant."/".$element['nom']), "&amp;orderby=nom&amp;order=", $order ?>" title="<?= $element['nom'] ?>">
											<img alt="repertoire" src="themes/original/images/24/repertoire.png" />
											<?= normalizeString($element['nom']) ?>
										</td>
<!-- 										<td class="element2_2"><span>Dossier de fichiers</span></td> -->
										<td class="element2_3">&nbsp;</td>
										<td class="element2_4">&nbsp;</td>
										
										<?php break;
										
									case 'fichier':
// debug('NOM:');
// debug($repertoire_courant."/".$element['nom.extension']);
// debug(urlencode($repertoire_courant."/".$element['nom.extension']));
										if( in_array(strtolower($element['extension']), $t_extensions_reconnues) ) { ?>
										
											<td class="element2_1 fichier" id="element_<?= rawurlencode($repertoire_courant."/".$element['nom.extension']), "&amp;orderby=nom&amp;order=", $order ?>" title="<?= $element['nom.extension'] ?>">
												<img alt="fichier" src="themes/original/images/24/<?= strtolower($element['extension']) ?>.png" />
												<a href="<?= ($repertoire_courant."/".$element['nom.extension']);?>" target="blank"><?= shortenString(normalizeString($element['nom.extension']), 60) ?></a>
											</td>
<!--											<td class="element2_2"><span><?= $t_extensions[strtolower($element['extension'])]?></span></td>
-->  
											<td class="element2_3"><?= formatSize($element['taille']) ?></td>
											<td class="element2_4"><?= date('d/m/Y', $element['date']) ?></td>
										
										<?php }
										else { ?>
										
											<td class="element2_1 inconnu" id="element_<?= rawurlencode($repertoire_courant."/".$element['nom.extension']), "&amp;orderby=nom&amp;order=", $order ?>" title="<?= $element['nom.extension'] ?>">
												<img alt="inconnu" src="themes/original/images/24/inconnu.png" />
												<a href="<?= $repertoire_courant."/".$element['nom.extension'];?>"><?= shortenString(normalizeString($element['nom.extension']), 60) ?></a>
											</td>
<!--											<td class="element2_2"><span>&nbsp;</span></td>
-->											<td class="element2_3"><?= formatSize($element['taille'])?></td>
											<td class="element2_4"><?= date('d/m/Y', $element['date']) ?></td>
								
										<?php }
										break;
										
									case 'image': ?>
									
										<td class="element2_1 image" id="element_<?= rawurlencode($repertoire_courant."/".$element['nom.extension']), "&amp;orderby=nom&amp;order=", $order ?>" title="<?= $element['nom.extension'] ?>">
											<img alt="image" src="themes/original/images/24/<?= strtolower($element['extension']) ?>.png" />
											<a href="<?= $repertoire_courant."/".$element['nom.extension'];?>"><?= shortenString(normalizeString($element['nom.extension']), 60) ?></a>
										</td>
<!--										<td class="element2_2"><span><?= $t_extensions[strtolower($element['extension'])]?></span></td>
-->										<td class="element2_3"><?= formatSize($element['taille'])?></td>
										<td class="element2_4"><?= date('d/m/Y', $element['date']) ?></td>

										<?php break;			
								} 
								echo '</tr>';
							}
						}
						else { ?>
						
							<tr><td><img id="info" alt="[i]" src="themes/original/images/info.png" />R&eacute;pertoire vide</td></tr>
							
						<?php } ?>
						</tbody>
						
						<tfoot></tfoot>
					
					</table>

					
				
			
			</div><!-- fin contenu_repertoire -->
	
		</div><!-- fin contenu -->
		
	</div><!-- fin corps -->
	
	<footer id="pieddepage">
		<div class="contenu">
			<ul>
				<!--<li><img src="http://i.creativecommons.org/l/by-nc/3.0/fr/88x31.png" alt="Licence Creative Commons" /><a href="#">g&eacute;raud lacheny 2014</a></li>
				<li><a href="mailto: contact@geraudlacheny.fr">contact&nbsp;&raquo;</a></li>-->
			</ul>
		</div><!-- fin contenu -->
		
	</footer>

	
	<script type="text/javascript">

	  accentSearch = function ( data ) {
			
		    return ! data ?
		        '' :
		        typeof data === 'string' ?
		            data
		                .replace( /[uüû]/g, 	'[uüû]' )
		                .replace( /[oôö]/g, 	'[oôö]' )
		                .replace( /[aàâä]/g, 	'[aàâä]' )
		                .replace( /[eéèêë]/g, 	'[eéèêë]' )
		                .replace( /[iîï]/g, 	'[iîï]' )
		                .replace( /cç/g, 		'[cç]' ) :
		            data;
	  };
	
	  $(document).ready(function() {

	<?php if ($titles): ?>
        
	    var table = $('#data-search').DataTable( {

	    	"dom": 'tip',
	        "pagingType": "full",
	        "pageLength": 10,
	        "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/French.json"
            }
		});
		
    	table
    	  .search(
    	    accentSearch( <?= $titles ? "'$query'" : "''" ?> ),
    	    true,	//regex
    	    true	//smart
    	  )
    	  .draw();
  	  
        $('#mySearch').keyup( function () {
    	       table
    	         .search(
    	           accentSearch( this.value ),
    	           true,	//regex
    	           true	//smart
    	         )
    	         .draw();
    	     } );		
	<?php else: ?>
        
	    var table = $('#data-search').DataTable( {

	    	"dom": 'ftip',
	        "pagingType": "full",
	        "pageLength": 10,
	        "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/French.json"
            },
		});        

	<?php endif; ?>
        
		$('#query').focusin(function(){

	        $(this).css("background-color", "#FFFFCC");
	        $(this).val('');
	    });
// 		$('#query').keypress(function(event){
// 	        if(event.key == 'Enter') {
// 		        $('#search').submit();
// 	        }
// 	    });
	<?php if(! empty($query)): ?> 
		
		$('#searchModal').modal({backdrop: 'static'});
		
	<?php endif; ?>

	  });   
	</script>
</body>
</html>

