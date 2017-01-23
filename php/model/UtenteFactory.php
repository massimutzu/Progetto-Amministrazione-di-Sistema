<?php
include_once 'Db.php';
include_once 'Utente.php';
class utenteFactory {
	private static $singleton;
	private function __constructor() {
	}
	
	/**
	 * Restiuisce un singleton per creare utenti
	 *
	 * @return \utenteFactory
	 */
	public static function instance() {
		if (! isset ( self::$singleton )) {
			self::$singleton = new utenteFactory ();
		}
		return self::$singleton;
	}
	
	/**
	 * Carica un utente eseguendo un prepared statement
	 *
	 * @param mysqli_stmt $stmt        	
	 * @return null
	 */
	private function loadUtenteFromStatement(mysqli_stmt $stmt) {
		if (! $stmt->execute ()) {
			error_log ( "[loadUtenteFromStatement] impossibile" . " eseguire lo statement" );
			return null;
		}
		
		$row = array ();
		$bind = $stmt->bind_result ( $row ['utente_id'], $row ['utente_nome'], $row ['utente_cognome'], $row ['utente_email'], $row ['utente_indirizzo'], $row ['utente_abilitato'], $row ['utente_cellulare'], $row ['utente_username'] );
		if (! $bind) {
			error_log ( "[loadUtenteFromStatement] impossibile" . " effettuare il binding in output" );
			return null;
		}
		
		if (! $stmt->fetch ()) {
			return null;
		}
		
		$stmt->close ();
		
		return self::createUtenteFromArray ( $row );
	}
	private function &loadUtentiFromStatement(mysqli_stmt $stmt) {
		$utenti = array ();
		if (! $stmt->execute ()) {
			error_log ( "[loadUtentiFromStatement] impossibile" . " eseguire lo statement" );
			return null;
		}
		$row = array ();
		$bind = $stmt->bind_result ( $row ['utente_id'], $row ['utente_nome'], $row ['utente_cognome'], $row ['utente_email'], $row ['utente_indirizzo'], $row ['utente_abilitato'], $row ['utente_cellulare'], $row ['utente_username'], $row['Noleggiati'] );
		if (! $bind) {
			error_log ( "[loadUtentiFromStatement] impossibile" . " effettuare il binding in output" );
			return null;
		}
		while ( $stmt->fetch () ) {
			$utenti [] = self::createUtenteFromArray ( $row );
		}
		$stmt->close ();
		return $utenti;
	}
	/**
	 *
	 * @param unknown $id        	
	 * @return utente
	 */
	public function getUtenteById($id) {
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[getUtenteById] impossibile inizializzare il database" );
			$mysqli->close ();
			return null;
		}
		// cerco nella tabella utete
		$query = "select
					u.id utente_id ,
					u.nome utente_nome,
					u.cognome utente_cognome,
					u.email utente_email,
					u.indirizzo utente_indirizzo,
					u.abilitato utente_abilitato,
					u.cellulare utente_cellulare,
					u.username utente_username
				 from utente u
				 where u.id= ?";
		$stmt = $mysqli->stmt_init ();
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[getUtenteById] impossibile" . " inizializzare il prepared statement" );
			$mysqli->close ();
			return null;
		}
		
		if (! $stmt->bind_param ( 'i', $id )) {
			error_log ( "[getUtenteById] impossibile" . " effettuare il binding in input" );
			$mysqli->close ();
			return null;
		}
		$utente = self::loadUtenteFromStatement ( $stmt );
		if (isset ( $utente )) {
			$mysqli->close ();
			return $utente;
		}
		return null;
	}
	
	/**
	 * Crea un utente da una riga del db
	 *
	 * @param type $row        	
	 * @return \utente
	 */
	public function createUtenteFromArray($row) {
		$utente = new utente ();
		$utente->setId ( $row ['utente_id'] );
		$utente->setNome ( $row ['utente_nome'] );
		$utente->setCognome ( $row ['utente_cognome'] );
		$utente->setEmail ( $row ['utente_email'] );
		$utente->setIndirizzo ( $row ['utente_indirizzo'] );
		$utente->setAbilitato ( $row ['utente_abilitato'] );
		$utente->setCellulare ( $row ['utente_cellulare'] );
		$utente->setUsername ( $row ['utente_username'] );
		$utente->setNoleggiAperti($row['Noleggiati']);
		return $utente;
	}
	public function createNewUtente($username, $nome, $cognome, $email, $indirizzo, $cellulare, $abilitato, $password) {
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[createNewUtente] impossibile inizializzare il database" );
			$mysqli->close ();
			return null;
		}
		
		$query = "Insert into utente
				(nome, cognome , email , indirizzo , abilitato , cellulare , username, password ) 
				Values (?,?,?,?,?,?,?,?)";
		
		$stmt = $mysqli->stmt_init ();
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[createNewUtente] impossibile" . " inizializzare il prepared statement" );
			$mysqli->close ();
			return null;
		}
		
		if (! $stmt->bind_param ( 'ssssisss', $nome, $cognome, $email, $indirizzo, $abilitato, $cellulare, $username, $password )) {
			error_log ( "[createNewUtente] impossibile" . " effettuare il binding in input" );
			$mysqli->close ();
			return null;
		}
		if (! $stmt->execute ()) {
			error_log ( "[createNewUtente] impossibile" . " eseguire lo statement" );
			return 0;
		}
		return $stmt->affected_rows;
	}
	
	/**
	 * Autentica l'utente tramite le credenziali
	 *
	 * @param string $username        	
	 * @param string $password        	
	 * @return utente
	 */
	public function authenticateUtente($username, $password) {
		
		// inizializza la connessione al database
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[authenticateUtente] impossibile inizializzare il database" );
			$mysqli->close ();
			return null;
		}
		// cerco nella tabella utente
		$query = "select
					 u.id utente_id ,
					u.nome utente_nome, 
					 u.cognome utente_cognome,
					u.email utente_email,
					 u.indirizzo utente_indirizzo,
					u.abilitato utente_abilitato,
					u.cellulare utente_cellulare,
					 u.username utente_username
				 from utente u
				 where u.username= ? and u.password=?";
		$stmt = $mysqli->stmt_init ();
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[authenticateUtente] impossibile" . " inizializzare il prepared statement" );
			$mysqli->close ();
			return null;
		}
		
		if (! $stmt->bind_param ( 'ss', $username, $password )) {
			error_log ( "[authenticateUtente] impossibile" . " effettuare il binding in input" );
			$mysqli->close ();
			return null;
		}
		$utente = self::loadUtenteFromStatement ( $stmt );
		if (isset ( $utente )) {
			$mysqli->close ();
			return $utente;
		}
		return null;
	}
	
	/**
	 * change user password
	 *
	 * @param integer $id        	
	 * @param string $oldPassword        	
	 * @param string $newPassword        	
	 */
	public function changePassword($id, $oldPassword, $newPassword) {
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[changePassword] impossibile inizializzare il database" );
			$mysqli->close ();
			return null;
		}
		$query = "update utente
				set password= ?
				 where id= ? and password=?";
		$stmt = $mysqli->stmt_init ();
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[changePassword] impossibile" . " inizializzare il prepared statement" );
			$mysqli->close ();
			return null;
		}
		
		if (! $stmt->bind_param ( 'sis', $newPassword, $id, $oldPassword )) {
			error_log ( "[changePassword] impossibile" . " effettuare il binding in input" );
			$mysqli->close ();
			return null;
		}
		if (! $stmt->execute ()) {
			error_log ( "[changePassword] impossibile" . " eseguire lo statement" );
			return 0;
		}
		return $stmt->affected_rows;
	}
	
	/**
	 *
	 * @param integer $id        	
	 * @param string $nome        	
	 * @param string $cognome        	
	 * @param string $email        	
	 * @param string $cellulare        	
	 * @param string $indirizzo     
	 * @return integer|NULL  	
	 */
	public function changeUtenteData($id, $nome, $cognome, $email, $cellulare, $indirizzo) {
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[changeUtenteData] impossibile inizializzare il database" );
			$mysqli->close ();
			return null;
		}
		$query = "update utente				
				set 
				nome=?,
				cognome=?,
				email=?,
				cellulare=?,
				indirizzo=?
				where id=? ";
		
		$stmt = $mysqli->stmt_init ();
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[changeUtenteData] impossibile" . " inizializzare il prepared statement" );
			$mysqli->close ();
			return null;
		}
		
		if (! $stmt->bind_param ( 'sssssi', $nome, $cognome, $email, $cellulare, $indirizzo, $id )) {
			error_log ( "[changeUtenteData] impossibile" . " effettuare il binding in input" );
			$mysqli->close ();
			return null;
		}
		
		if (! $stmt->execute ()) {
			error_log ( "[changeUtenteData] impossibile" . " eseguire lo statement" );
			return 0;
		}
		return $stmt->affected_rows;
	}
	
	/**
	 *
	 * @param integer $id        	
	 * @param string $newPassword        	
	 */
	public function resetPassword($id, $newPassword) {
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[resetPassword] impossibile inizializzare il database" );
			$mysqli->close ();
			return null;
		}
		$query = "update utente
				set password= ?
				 where id= ?";
		$stmt = $mysqli->stmt_init ();
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[resetPassword] impossibile" . " inizializzare il prepared statement" );
			$mysqli->close ();
			return null;
		}
		
		if (! $stmt->bind_param ( 'si', $newPassword, $id )) {
			error_log ( "[resetPassword] impossibile" . " effettuare il binding in input" );
			$mysqli->close ();
			return null;
		}
		if (! $stmt->execute ()) {
			error_log ( "[resetPassword] impossibile" . " eseguire lo statement" );
			return 0;
		}
		return $stmt->affected_rows;
	}
	
	/**
	 *
	 * @param unknown $id        	
	 */
	public function enableUtente($id) {
		return self::setAbilitato ( $id, true );
	}
	
	/**
	 *
	 * @param unknown $id        	
	 */
	public function disableUtente($id) {
		return self::setAbilitato ( $id, false );
	}
	
	/**
	 *
	 * @param unknown $nome        	
	 * @param unknown $cognome        	
	 * @param unknown $indirizzo        	
	 * @return utente[]
	 */
	public function searchUtenteByParam($nome, $cognome, $indirizzo) {
		$utenti = array ();
		
		// costruisco la where "a pezzi" a seconda di quante
		// variabili sono definite
		$bind = "";
		$where = " where 1=1 ";
		$par = array ();
		
		if (isset ( $nome ) && ! empty ( $nome )) {
			$where .= " and lower(utente.nome) like lower(?) ";
			$bind .= "s";
			$par [] = "%" . $nome . "%";
		}
		if (isset ( $cognome ) && ! empty ( $cognome )) {
			$where .= " and lower(utente.cognome) like lower(?) ";
			$bind .= "s";
			$par [] = "%" . $cognome . "%";
		}
		if (isset ( $indirizzo ) && ! empty ( $indirizzo )) {
			$where .= " and lower(utente.indirizzo) like lower(?) ";
			$bind .= "s";
			$par [] = "%" . $indirizzo . "%";
		}
		$query = "select
               		id utente_id ,
					nome utente_nome, 
					cognome utente_cognome,
					email utente_email,
					indirizzo utente_indirizzo,
					abilitato utente_abilitato,
					cellulare utente_cellulare,
					username utente_username, 
					(Select count(noleggio.libro_fk) from noleggio where noleggio.utente_fk =utente.id And noleggio.end_rent is null ) as Noleggiati 
				 from utente
                  " . $where;
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[searchUtenteByParam] impossibile inizializzare il database" );
			$mysqli->close ();
			return $utenti;
		}
		$stmt = $mysqli->stmt_init ();
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[searchUtenteByParam] impossibile" . " inizializzare il prepared statement" );
			$mysqli->close ();
			return $utenti;
		}
		
		switch (count ( $par )) {
			case 1 :
				if (! $stmt->bind_param ( $bind, $par [0] )) {
					error_log ( "[searchUtenteByParam] impossibile" . " effettuare il binding in input" );
					$mysqli->close ();
					return $utenti;
				}
				break;
			case 2 :
				if (! $stmt->bind_param ( $bind, $par [0], $par [1] )) {
					error_log ( "[searchUtenteByParam] impossibile" . " effettuare il binding in input" );
					$mysqli->close ();
					return $utenti;
				}
				break;
			
			case 3 :
				if (! $stmt->bind_param ( $bind, $par [0], $par [1], $par [2] )) {
					error_log ( "[searchUtenteByParam] impossibile" . " effettuare il binding in input" );
					$mysqli->close ();
					return $utenti;
				}
				break;
		}
		
		$utenti = self::loadUtentiFromStatement ( $stmt );
		$mysqli->close ();
		return $utenti;
	}
	
	/**
	 *
	 * @param unknown $id        	
	 * @param unknown $abilitato        	
	 */
	private function setAbilitato($id, $abilitato) {
		// scrivere logica accesso db e update
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[setAbilitato] impossibile inizializzare il database" );
			$mysqli->close ();
			return null;
		}
		/*
		 * Questo????
		 * if($abilitato == true)
		 * {
		 * $bool = "b'1'";
		 * }
		 * else
		 * {
		 * $bool = "b'0'";
		 * }
		 * $query = "update utente set abilitato= $bool where id= ?";
		 */
		$query = "update utente set abilitato= ? where id= ?";
		$stmt = $mysqli->stmt_init ();
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[setAbilitato] impossibile" . " inizializzare il prepared statement" );
			$mysqli->close ();
			return null;
		}
		
		if (! $stmt->bind_param ( 'ii', $abilitato, $id )) {
			error_log ( "[setAbilitato] impossibile" . " effettuare il binding in input" );
			$mysqli->close ();
			return null;
		}
		if (! $stmt->execute ()) {
			error_log ( "[setAbilitato] impossibile" . " eseguire lo statement" );
			return 0;
		}
		return $stmt->affected_rows;
	}
	public function deleteUtente($id) {
		// scrivere logica accesso db e update
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[deleteUtente] impossibile inizializzare il database" );
			$mysqli->close ();
			return -1;
		}
		$query = "delete from utente where id=?";
		$stmt = $mysqli->stmt_init ();
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[deleteUtente] impossibile" . " inizializzare il prepared statement" );
			$mysqli->close ();
			return -1;
		}
		
		if (! $stmt->bind_param ( 'i', $id )) {
			error_log ( "[deleteUtente] impossibile" . " effettuare il binding in input" );
			$mysqli->close ();
			return -1;
		}
		if (! $stmt->execute ()) {
			error_log ( "[deleteUtente] impossibile" . " eseguire lo statement" );
			return 0;
		}
		return $stmt->affected_rows;
	}
	public static function AutoCompleteCognome($cognome, $numresult) {
		$cognomi = array ();
		$query = "select C.Cognome
			FROM utente C
			WHERE lower(cognome) like lower(?)
			LIMIT ?
				";
		
		$bind .= "si";
		$par [] = "%" . $cognome . "%";
		$par [] = $numresult;
		
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[AutoCompleteCognome] impossibile inizializzare il database" );
			$mysqli->close ();
			return $cognomi;
		}
		$stmt = $mysqli->stmt_init ();
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[AutoCompleteCognome] impossibile" . " inizializzare il prepared statement" );
			$mysqli->close ();
			return $cognomi;
		}
		if (! $stmt->bind_param ( $bind, $par [0], $par [1] )) {
			error_log ( "[AutoCompleteCognome] impossibile" . " effettuare il binding in input" );
			$mysqli->close ();
			return $cognomi;
		}
		if (! $stmt->execute ()) {
			error_log ( "[AutoCompleteCognome] impossibile" . " eseguire lo statement" );
			return $cognomi;
		}
		
		$bind = $stmt->bind_result ( $row ['Cognome'] );
		if (! $bind) {
			error_log ( "[AutoCompleteCognome] impossibile" . " effettuare il binding in output" );
			return $cognomi;
		}
		while ( $stmt->fetch () ) {
			$cognomi [] = ($row ['Cognome']);
		}
		$stmt->close ();
		return $cognomi;
	}
}

?>