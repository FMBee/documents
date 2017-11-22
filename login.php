<?php 
/******************************************************************
auteur: Geraud LACHENY (2014)
site web: http://www.geraudlacheny.fr
/******************************************************************/

require_once('config/config.inc.php');
require_once('config/settings.inc.php');
require_once('classes/User.php');
require_once('lib/functions.php');

// si il y a authentification, l'internaute est redirige automatiquement sur la page d'index
if(AUTHENTIFICATION !== 'on')
	redirect('index.php');

session_start();

/***********************************************************************************************************/
/********************************* INITIALISATION DES VARIABLES ********************************************/
/***********************************************************************************************************/
$error = "";

/***********************************************************************************************************/
/********************************* TRAITEMENT DES DONNEES **************************************************/
/***********************************************************************************************************/
if(isset($_COOKIE['identifiant']) && !empty($_COOKIE['identifiant']) && isset($_COOKIE['mdp']) && !empty($_COOKIE['mdp'])) {

	
	$monUtilisateur = User::check($_COOKIE['identifiant'], $_COOKIE['mdp']);
	
	if($monUtilisateur !== false) {
		$_SESSION['auth'] = $monUtilisateur;
		redirect('index.php');
	}
	else {
		// on supprime les cookies
		setcookie('identifiant');
		setcookie('mdp');
		
		$error = "L'identifiant ou le mot de passe saisi est incorrect.";	
	}
	
}
else {
	
	if( isset($_POST['txt_identifiant']) || isset($_POST['txt_mdp']) ) {
	
		/////////// CONTROLES PRELIMINAIRES ////////////////////////////////////////////////////////////////////////
		$nbErreurs = isset($GLOBALS['login_nbErreurs']) ? (int)$GLOBALS['login_nbErreurs'] : 0;
		$nbChampsrestantsaremplir = isset($GLOBALS['login_nbChampsrestantsaremplir']) ? (int)$GLOBALS['login_nbChampsrestantsaremplir'] : 0;
		$t_erreurs = array();
	
		/***********************************************************************************************************/
		/********** IDENTIFIANT ************************************************************************************/
		/***********************************************************************************************************/
		if (isset($_POST['txt_identifiant'])) {
			$_POST['txt_identifiant'] = trim($_POST['txt_identifiant']);
			if ($_POST['txt_identifiant'] == '') {
				$nbChampsrestantsaremplir++;
				$t_erreurs['txt_identifiant'] = 'Veuillez saisir votre identifiant';
			}
		}
	
		/***********************************************************************************************************/
		/*********** MDP *******************************************************************************************/
		/***********************************************************************************************************/
		if (isset($_POST['txt_mdp'])) {
			$_POST['txt_mdp'] = trim($_POST['txt_mdp']);
			if ($_POST['txt_mdp'] == '') {
				$nbChampsrestantsaremplir++;
				$t_erreurs['txt_mdp'] = 'Veuillez saisir votre mot de passe';
			}
		}
	
		////////// FIN CONTROLES PRELIMINAIRES //////////////////////////////////////////////////////////////////////
		$GLOBALS['login_nbErreurs'] = $nbErreurs;
		$GLOBALS['login_nbChampsrestantsaremplir'] = $nbChampsrestantsaremplir ;
	
	
		if ($nbErreurs == 0 && $nbChampsrestantsaremplir == 0) {
		
			$monUtilisateur = User::check($_POST['txt_identifiant'], $_POST['txt_mdp']);
	
			if($monUtilisateur !== false) {
				// si l'authentification est bonne et que la case est cochee, on cree le cookie
				if (isset($_POST['chk_cookie']) && $_POST['chk_cookie']=="oui") {			
					// on cree les cookies valide 1 JOUR			
					setcookie('identifiant', $_POST['txt_identifiant'], time() + 1*24*3600, null, null, false, true);
					setcookie('mdp', $_POST['txt_mdp'], time() + 1*24*3600, null, null, false, true);
				}
				// sinon on supprime
				else {
					// on supprime les cookies	
					setcookie('identifiant');
					setcookie('mdp');
				}
				//je cree une session
				$_SESSION['auth'] = $monUtilisateur;
				
				//je redirige
				redirect('index.php');
			}
			else 
				$error = "L'identifiant ou le mot de passe saisi est incorrect.";
	
		}
	
	}

}
?>
<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>Authentification</title>
	<meta name="robots" content="noindex,nofollow" />

	<!-- CSS -->
	<link rel="stylesheet" href="themes/original/css/login.css" />
	
	<!-- JQUERY -->
	<script src="js/jquery-1.11.0.min.js"></script>
  
	<!-- SCRIPTS DIVERS -->
	<script src="js/jquery.placeholder.min.js"></script>
	<script>
	$(document).ready(function(){
		$('input[placeholder]').placeholder();
	});
	</script>
	
	<script>
	$(document).ready(function(){	
		$('#txt_identifiant').focus();					 		 		
	});	
	</script>
</head>

<body>

	<div id="content">
		
		<div id="logo">
			<img alt="logo" src="themes/original/images/logo.png" />
		</div>
		
		<? if(isset($error)) { ?><p class="error"><?php echo $error ?></p><? } ?>
		
		<form method="post" action="login.php">
		
			<div id="identifiant">
				<input type="text" name="txt_identifiant" id="txt_identifiant" placeholder="Identifiant" required="" value="" />
			</div>
			<div id="mdp">
				<input type="password" name="txt_mdp" id="txt_mdp" placeholder="Mot de passe" required="" />
			</div>
			<div id="maintenir">
				<input type="checkbox" name="chk_maintenir" id="chk_maintenir"> 
				<label for="chk_maintenir"><span>Maintenir la connexion</span></label>
			</div>
			<div id="soumettre">
				<input type="submit" name="sub_login" id="sub_login" value="Connexion" style="width:100px;" />
			</div>

		</form>
			
</div><!-- fin de content -->

</body>
</html>