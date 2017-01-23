<?php
include_once 'Db.php';
include_once 'Operatore.php';
class OperatoreFactory {
	private static $singleton;
	private function __constructor() {
	}
	
	/**
	 * Restiuisce un singleton per creare operatori
	 *
	 * @return \OperatoreFactory
	 */
	public static function instance() {
		if (! isset ( self::$singleton )) {
			self::$singleton = new OperatoreFactory ();
		}
		return self::$singleton;
	}
	public function getOperatoreById($id) {
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[getUtenteById] impossibile inizializzare il database" );
			$mysqli->close ();
			return null;
		}
		// non capisco la u che c'è qui per l'utente, io ho messo la o ma non so perchè
		// cerco nella tabella operatore
		$query = "select
					o.id  ,
					o.nome ,
					o.cognome ,
					o.email ,					
					o.cellulare ,
					o.abilitato ,
					o.username , 
					o.admin
				 from operatore o
				 where o.id= ?";
		$stmt = $mysqli->stmt_init ();
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[getOperatoreById] impossibile" . " inizializzare il prepared statement" );
			$mysqli->close ();
			return null;
		}
		
		if (! $stmt->bind_param ( 'i', $id )) {
			error_log ( "[getOperatoreById] impossibile" . " effettuare il binding in input" );
			$mysqli->close ();
			return null;
		}
		$operatore = self::loadOperatoreFromStatement ( $stmt );
		if (isset ( $operatore )) {
			$mysqli->close ();
			return $operatore;
		}
		return null;
	}
	
	/**
	 * Autentica l'operatore tramite le credenziali
	 *
	 * @param string $username        	
	 * @param string $password        	
	 * @return utente
	 */
	public function authenticateOperatore($username, $password) {
		
		// inizializza la connession al database
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[authenticateOperatore] impossibile inizializzare il database" );
			$mysqli->close ();
			return null;
		}
		
		// cerco nella tabella operatore
		$query = "select
					id,
					nome ,
					cognome ,
					email ,
					cellulare ,
					abilitato,
					username ,
					admin
					from operatore
				 	where username= ? and password=?";
		$stmt = $mysqli->stmt_init ();
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[authenticateOperatore] impossibile" . " inizializzare il prepared statement" );
			$mysqli->close ();
			return null;
		}
		
		if (! $stmt->bind_param ( 'ss', $username, $password )) {
			error_log ( "[authenticateOperatore] impossibile" . " effettuare il binding in input" );
			$mysqli->close ();
			return null;
		}
		
		$operatore = self::loadOperatoreFromStatement ( $stmt );
		if (isset ( $operatore )) {
			// ho trovato un operatore
			$mysqli->close ();
			return $operatore;
		}
	}
	/**
	 *
	 * @param unknown $row        	
	 * @return operatore
	 */
	public function createOperatoreFromArray($row) {
		$operatore = new operatore ();
		$operatore->setId ( $row ['id'] );
		$operatore->setNome ( $row ['nome'] );
		$operatore->setCognome ( $row ['cognome'] );
		$operatore->setEmail ( $row ['email'] );
		$operatore->setAbilitato ( $row ['abilitato'] );
		$operatore->setCellulare ( $row ['cellulare'] );
		$operatore->setUsername ( $row ['username'] );
		$operatore->setIsAdmin ( $row ['admin'] );
		// non manca la password qui??
		return $operatore;
	}
	
	
	
	private function loadOperatoriFromStatement(mysqli_stmt $stmt) {
		
		$operatori = array ();
		if (! $stmt->execute ()) {
			error_log ( "[loadOperatoriFromStatement] impossibile" . " eseguire lo statement" );
			return null;
		}
		$row = array ();
		$bind = $stmt->bind_result ( $row ['id'], $row ['nome'], $row ['cognome'], $row ['email'], $row ['cellulare'], $row ['abilitato'], $row ['username'], $row ['admin'] );
		if (! $bind) {
			error_log ( "[loadOperatoriFromStatement] impossibile" . " effettuare il binding in output" );
			return null;
		}
		while ( $stmt->fetch () ) {
			$operatori [] = self::createOperatoreFromArray ( $row );
		}
		$stmt->close ();
		return $operatori;
	}
	
	/**
	 *
	 * @param mysqli_stmt $stmt        	
	 * @return NULL|operatore
	 */
	private function loadOperatoreFromStatement(mysqli_stmt $stmt) {
		if (! $stmt->execute ()) {
			error_log ( "[loadOperatoreFromStatement] impossibile" . " eseguire lo statement" );
			return null;
		}
		$row = array ();
		// ed anche qui non manca la password??
		$bind = $stmt->bind_result ( $row ['id'], $row ['nome'], $row ['cognome'], $row ['email'], $row ['cellulare'], $row ['abilitato'], $row ['username'], $row ['admin'] );
		if (! $bind) {
			error_log ( "[loadOperatoreFromStatement] impossibile" . " effettuare il binding in output" );
			return null;
		}
		if (! $stmt->fetch ()) {
			return null;
		}
		$stmt->close ();
		
		return self::createOperatoreFromArray ( $row );
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
		$query = "update operatore
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
	 * @param int $id        	
	 * @param string $nome        	
	 * @param string $cognome        	
	 * @param string $email        	
	 * @param string $cellulare        	
	 * @return NULL|number
	 */
	public function changeOperatoreData($id, $nome, $cognome, $email, $cellulare) {
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[changeOperatoreData] impossibile inizializzare il database" );
			$mysqli->close ();
			return null;
		}
		$query = "update operatore
				set
				nome=?,
				cognome=?,
				email=?,
				cellulare=?
				where id= ? ";
		
		$stmt = $mysqli->stmt_init ();
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[changeOperatoreData] impossibile" . " inizializzare il prepared statement" );
			$mysqli->close ();
			return null;
		}
		
		if (! $stmt->bind_param ( 'ssssi', $nome, $cognome, $email, $cellulare, $id )) {
			error_log ( "[changeOperatoreData] impossibile" . " effettuare il binding in input" );
			$mysqli->close ();
			return null;
		}
		
		if (! $stmt->execute ()) {
			error_log ( "[changeOperatoreData] impossibile" . " eseguire lo statement" );
			return 0;
		}
		return $stmt->affected_rows;
	}
	
	/**
	 *
	 * @param unknown $id        	
	 */
	public function enableOperatore($id) {
		return self::setAbilitato ( $id, true );
	}
	
	/**
	 *
	 * @param unknown $id        	
	 */
	public function disableOperatore($id) {
		return self::setAbilitato ( $id, false );
	}
	
	/**
	 *
	 * @param unknown $id        	
	 * @param unknown $abilitato        	
	 * @return NULL|number
	 */
	private function setAbilitato($id, $abilitato) {
		// scrivere logica accesso db e update
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[setAbilitato] impossibile inizializzare il database" );
			$mysqli->close ();
			return null;
		}
		$query = "update operatore set abilitato= ? where id= ?";
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
	
	/**
	 *
	 * @param int $id        	
	 */
	public function enableOperatoreAdmin($id) {
		return self::setAdmin ( $id, true );
	}
	
	/**
	 *
	 * @param int $id        	
	 */
	public function disableOperatoreAdmin($id) {
		return self::setAdmin ( $id, false );
	}
	/**
	 *
	 * @param int $id        	
	 * @param bool $abilitato        	
	 * @return NULL|int
	 */
	private function setAdmin($id, $abilitato) {
		// scrivere logica accesso db e update
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[setAdmin] impossibile inizializzare il database" );
			$mysqli->close ();
			return null;
		}
		$query = "update operatore set admin= ? where id= ?";
		$stmt = $mysqli->stmt_init ();
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[setAdmin] impossibile" . " inizializzare il prepared statement" );
			$mysqli->close ();
			return null;
		}
		
		if (! $stmt->bind_param ( 'ii', $abilitato, $id )) {
			error_log ( "[setAdmin] impossibile" . " effettuare il binding in input" );
			$mysqli->close ();
			return null;
		}
		if (! $stmt->execute ()) {
			error_log ( "[setAdmin] impossibile" . " eseguire lo statement" );
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
		$query = "update operatore
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
	 * @param int $id        	
	 * @return number
	 */
	public function deleteOperatore($id) {
		// scrivere logica accesso db e update
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[deleteOperatore] impossibile inizializzare il database" );
			$mysqli->close ();
			return - 1;
		}
		$query = "delete from operatore where id=?";
		$stmt = $mysqli->stmt_init ();
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[deleteOperatore] impossibile" . " inizializzare il prepared statement" );
			$mysqli->close ();
			return - 1;
		}
		
		if (! $stmt->bind_param ( 'i', $id )) {
			error_log ( "[deleteOperatore] impossibile" . " effettuare il binding in input" );
			$mysqli->close ();
			return - 1;
		}
		if (! $stmt->execute ()) {
			error_log ( "[deleteOperatore] impossibile" . " eseguire lo statement" );
			return 0;
		}
		return $stmt->affected_rows;
	}
	
	
	public function createNewOperatore($username, $nome, $cognome, $email,  $cellulare, $abilitato, $password, $admin) {
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[createNewOperatore] impossibile inizializzare il database" );
			$mysqli->close ();
			return null;
		}
	
		$query = "Insert into operatore
				(nome, cognome , email , abilitato , cellulare , username, password, admin )
				Values (?,?,?,?,?,?,?,?)";
	
		$stmt = $mysqli->stmt_init ();
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[createNewOperatore] impossibile" . " inizializzare il prepared statement" );
			$mysqli->close ();
			return null;
		}
	
		if (! $stmt->bind_param ( 'sssisssi', $nome, $cognome, $email,  $abilitato, $cellulare, $username, $password , $admin )) {
			error_log ( "[createNewOperatore] impossibile" . " effettuare il binding in input" );
			$mysqli->close ();
			return null;
		}
		if (! $stmt->execute ()) {
			error_log ( "[createNewOperatore] impossibile" . " eseguire lo statement" );
			return 0;
		}
		return $stmt->affected_rows;
	}
	
	/**
	 * 
	 * @param unknown $nome
	 * @param unknown $cognome
	 * @return operatore
	 */
	public function searchOperatoriByParam($nome, $cognome) {
		$operatori = array ();
		// costruisco la where "a pezzi" a seconda di quante
		// variabili sono definite
		$bind = "";
		$where = " where 1=1 ";
		$par = array ();	
		if (isset ( $nome ) && ! empty ( $nome )) {
			$where .= " and lower(nome) like lower(?) ";
			$bind .= "s";
			$par [] = "%" . $nome . "%";
		}
		if (isset ( $cognome ) && ! empty ( $cognome )) {
			$where .= " and lower(cognome) like lower(?) ";
			$bind .= "s";
			$par [] = "%" . $cognome . "%";
		}	
		$query = "select
               		id,
					nome ,
					cognome ,
					email ,
					cellulare ,
					abilitato,
					username ,
					admin
					from operatore
                  " . $where;
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[searchUOperatoriByParam] impossibile inizializzare il database" );
			$mysqli->close ();
			return $operatori;
		}
		$stmt = $mysqli->stmt_init ();
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[searchUOperatoriByParam] impossibile" . " inizializzare il prepared statement" );
			$mysqli->close ();
			return $operatori;
		}	
		switch (count ( $par )) {
			case 1 :
				if (! $stmt->bind_param ( $bind, $par [0] )) {
					error_log ( "[searchUOperatoriByParam] impossibile" . " effettuare il binding in input" );
					$mysqli->close ();
					return $operatori;
				}
				break;
			case 2 :
				if (! $stmt->bind_param ( $bind, $par [0], $par [1] )) {
					error_log ( "[searchUOperatoriByParam] impossibile" . " effettuare il binding in input" );
					$mysqli->close ();
					return $operatori;
				}
				break;
		}	
		$operatori = self::loadOperatoriFromStatement( $stmt );
		$mysqli->close ();
		return $operatori;
	}
	
	
}

?>
