<?php 
/******************************************************************
auteur: Geraud LACHENY (2014)
site web: http://www.geraudlacheny.fr
/******************************************************************/

include('classes/Db.php');

class User {

	private $_id;
	private $_prenom;
	private $_nom;
	private $_email;	
	private $_identifiant;
	private $_mdp;
	
	function __construct() {	
		$this->_id = $id;
		$this->_prenom = $prenom;
		$this->_nom = $nom;
		$this->_email = $email;
		$this->_identifiant = $identifiant;
		$this->_mdp = $mdp;
	}
	
	function __destruct() {
	}
	
	public static function check($login, $password) {
		
		$resultArray = self::get($login, $password);
		
		if(count($resultArray)==1)
			return $resultArray[0];
		else
			return false;

	}
	
	public static function get($login, $password) {
		$db = new Db(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
		$db->connect();
		if ($db->pingServer() == 1) {				
			$result = $db->execute("SELECT * FROM `users` WHERE identifiant = '" . $login. "' AND mdp = '" . md5(SALT . $password)."'");
					
			$resultArray = array();
			while ($row = mysqli_fetch_row($result)) {
				$i=0;
				$object = new User();
				$object->id = $row[$i++];
				$object->prenom = $row[$i++];
				$object->nom = $row[$i++];
				$object->email = $row[$i++];
				$object->identifiant = $row[$i++];
				$object->mdp = $row[$i++];
							
				$resultArray[] = $object;
			}
			$db->free($result);
		
		}
		$db->disconnect();
		
		return $resultArray;
	
	}
	
	public static function getOne($login, $password) {
	
		$objects = User::get($login, $password);
		if (count($objects) > 0)
			return $objects[0];
		else
			return false;
		
	}
	
	public function __toString() {
		
		return ( isset($this->prenom) ? strtoupper(substr(stripslashes($this->prenom), 0, 1)) . strtolower(substr(stripslashes($this->prenom), 1)) : '' ) . ' ' . ( isset($this->nom) ? strtoupper(stripslashes($this->nom )) : ''  );
		
	}
	
}
?>