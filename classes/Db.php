<?php 
/******************************************************************/
/* auteur: Geraud LACHENY (2014)
/* site web: http://www.geraudlacheny.fr
/******************************************************************/

class Db {
	/** @var string Serveur de la base de données (ex. localhost) */
	private $_server;

	/** @var string Utilisateur de la base de données (ex. root) */
	private $_user;

	/** @var string mot de passe de la base de données (ex. peut etre vide !) */
	private $_password;

	/** @var string Type de la base de données (MySQL, PgSQL, etc...) */
	//private $_type;

	/** @var string Nom de la base de données */
	private $_database;

	/** @var mixed Ressource */
	private $_link;

	/** @var mixed Résultat de la requete */
	private $_result;
	
	/** @var string Prefixe des tables de la base de données */
	private $_prefix;
	
	/** @var string Dernière requete */
	private $_lastQuery;


	public function __construct($server, $user, $password, $database) {
		$this->_server = $server;
		$this->_user = $user;
		$this->_password = $password;		
		$this->_database = $database;
		//$this->_type = DB_TYPE;
		$this->_prefix = DB_PREFIX;

		$this->connect();
	}
	
	function __destruct() {
		$this->disconnect();
	}
	
	public function connect() {
		try {
			$this->_link = mysqli_connect($this->_server, $this->_user, $this->_password);
			$this->set_db($this->_database);
		}
		catch(exception $e){
			return $e;
		}
	}
	
	public function disconnect() {
		try {		
			mysqli_close($this->_link);			
		}
		catch(exception $e) {		
			return $e;		
		}
	}
	
	private function set_db($db_name) {
		return mysqli_select_db($this->_link, $db_name);
	}
	
	public function free($result) {
		try {		
			mysqli_free_result($result);			
		}
		catch(exception $e) {		
			return $e;		
		}
	}
	
	private function sanitize($string) {
		return mysqli_real_escape_string($this->_link, $string);
	}
	
	public function lastQuery() {
		return $this->_lastQuery;
	}
	
	public function pingServer() {
		try {
			if(!mysqli_ping($this->_link))
				return false;
			
			else
				return true;
		}
		catch(exception $e) {
			return $e;
		}
	}
	
	public function execute($query) {
	
		$query = str_replace("}", "", $query);
		$query = str_replace("{", DB_PREFIX, $query);
		
		try {
			if(empty($this->_link)) {
				$this->connect();
				$this->_result = mysqli_query($this->_link, $this->sanitize($query));
				$this->free($this->_result);
				$this->disconnect();
				return $this->_result;
			}
			else {
				$this->_result = mysqli_query($this->_link, $query);
				return $this->_result;
			}
		}
		catch(exception $e) {
			return $e;
		}
	}
	
	public function numRows($result) {
		try {
			return mysqli_num_rows($result);
		}
		catch(exception $e) {
			return $e;
		}
	}
	
	/*
	public function fetch($result) {
		try {
			$resultArray = array();
			if ($result !== false && $result !== true)
				while ($row = mysqli_fetch_assoc($result))
					$resultArray[] = $row;
			
			return $resultArray;
		}
		catch(exception $e) {
			return $e;
		}
	}
	*/
	
}

?>