<?php
include_once 'Db.php';
include_once 'Noleggio.php';
include_once 'Operatore.php';
include_once 'Utente.php';
include_once 'Libro.php';

/**
 * Classe per la creazione di noleggi di libri
 */
class NoleggioFactory {
	/**
	 *
	 * @var unknown
	 */
	private static $singleton;
	/**
	 */
	private function __constructor() {
	}
	
	/**
	 * Restiuisce un singleton per creare noleggi
	 *
	 * @return \NoleggioFactory
	 */
	public static function instance() {
		if (! isset ( self::$singleton )) {
			self::$singleton = new NoleggioFactory ();
		}
		return self::$singleton;
	}
	
	public function getExpiringNoleggio($daysDueToExpire) {
		$noleggi = array ();
		$duedays= Settings::$MaxDayForRent-$daysDueToExpire;
		if ($duedays<1){
			//se mettiamo più di $MaxDayForRent
			$duedays= 1;
			
		}
		// costruisco la where "a pezzi" a seconda di quante
		// variabili sono definite
		$query = "	select 
					noleggio.id,
					start_rent,
					end_rent,
					libro_fk, 
					operatore_startrent_fk,
					operatore_endrent_fk,
					libro.codice_inventario,
					catalogo.titolo, 
					catalogo.autore, 
					utente.nome, 
					utente.cognome, 
					utente.id as idutente, 
					datediff(CURDATE(), start_rent) as giorninoleggio
					from noleggio inner join libro
					on noleggio.libro_fk=libro.id
					inner join catalogo
					on catalogo.id=libro.catalogo_fk
					inner join utente
					on noleggio.utente_fk=utente.id
					where end_rent is null 
					And 
					datediff(CURDATE(), start_rent) > ?";
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[getExpiringNoleggio] impossibile inizializzare il database" );
			$mysqli->close ();
			return $noleggi;
		}
		$stmt = $mysqli->stmt_init ();
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[getExpiringNoleggio] impossibile" . " inizializzare il prepared statement" );
			$mysqli->close ();
			return $noleggi;
		}		
		if (! $stmt->bind_param ( 'i',  $duedays)) {
			error_log ( "[getExpiringNoleggio] impossibile" . " effettuare il binding in input" );
			return 0;
		}
		$noleggi = self::loadNoleggiExtendedFromStatement ( $stmt );
		$mysqli->close ();
		return $noleggi;
	}
	
	
	public function searchNoleggio( $codice_inventario, $titolo, $autore, $nome, $cognome, $status) {
		$noleggi = array ();
		
		// costruisco la where "a pezzi" a seconda di quante
		// variabili sono definite
		$bind = "";
		$where = " where 1=1 ";
		$par = array ();
		
		if (isset ( $codice_inventario )) {
			$where .= " and codice_inventario =? ";
			$bind .= "s";
			$par [] = $codice_inventario;
		}
		if (isset ( $titolo )) {
			$where .= " and lower(titolo) like lower(?) ";
			$bind .= "s";
			$par [] = "%" . $titolo . "%";
		}
		if (isset ( $autore )) {
			$where .= " and lower(autore) like lower(?) ";
			$bind .= "s";
			$par [] = "%" . $autore . "%";
		}
		
		if (isset ( $nome )) {
			$where .= " and lower(nome) like lower(?) ";
			$bind .= "s";
			$par [] = "%" . $nome . "%";
		}
		
		if (isset ( $cognome )) {
			$where .= " and lower(cognome) like lower(?) ";
			$bind .= "s";
			$par [] = "%" . $cognome . "%";
		}
		
		if (isset ( $status )) {
			if ($status==0){
				//closed
				$where .= " and not end_rent is null ";
			}
			if ($status==1){
				//open
				$where .= " and end_rent is null ";
			}
		}		
	
		$query = "select 
				noleggio.id,
				start_rent,
				end_rent,				
				libro_fk,				 
				operatore_startrent_fk,
				operatore_endrent_fk,
				libro.codice_inventario,
				catalogo.titolo,
				catalogo.autore,
				utente.nome, 
				utente.cognome,
				utente_fk as idutente,
				IF (end_rent is null, datediff(CURDATE(), start_rent), datediff(end_rent, start_rent)) as giorninoleggio
				from noleggio  
				inner join  utente on utente.id= noleggio.utente_fk
				inner join libro  on libro.id= noleggio.libro_fk
				inner join catalogo on libro.catalogo_fk= catalogo.id " 
				. $where;
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[searchNoleggio] impossibile inizializzare il database" );
			$mysqli->close ();
			return $noleggi;
		}
		$stmt = $mysqli->stmt_init ();
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[searchNoleggio] impossibile" . " inizializzare il prepared statement" );
			$mysqli->close ();
			return $noleggi;
		}
		
		switch (count ( $par )) {
			case 1 :
				if (! $stmt->bind_param ( $bind, $par [0] )) {
					error_log ( "[searchNoleggio] impossibile" . " effettuare il binding in input" );
					$mysqli->close ();
					return $noleggi;
				}
				break;
			case 2 :
				if (! $stmt->bind_param ( $bind, $par [0], $par [1] )) {
					error_log ( "[searchNoleggio] impossibile" . " effettuare il binding in input" );
					$mysqli->close ();
					return $noleggi;
				}
				break;
			
			case 3 :
				if (! $stmt->bind_param ( $bind, $par [0], $par [1], $par [2] )) {
					error_log ( "[searchNoleggio] impossibile" . " effettuare il binding in input" );
					$mysqli->close ();
					return $noleggi;
				}
				break;
			case 4 :
				if (! $stmt->bind_param ( $bind, $par [0], $par [1], $par [2], $par [3] )) {
					error_log ( "[searchNoleggio] impossibile" . " effettuare il binding in input" );
					$mysqli->close ();
					return $noleggi;
				}
				break;
		}
		
		$noleggi = self::loadNoleggiExtendedFromStatement ( $stmt );
		$mysqli->close ();
		return $noleggi;
	}
	
	/**
	 * 
	 * @param unknown $idUtente
	 * @param unknown $status
	 * @return NULL|noleggio[]
	 */
	public function searchNoleggioByUtente($idUtente,  $status) {
		$noleggi = array ();
		$bind = "";
		$where = " where 1=1 ";
		$par = array ();
	
		if (isset ( $idUtente )) {
			$where .= " and utente_fk =? ";
			$bind .= "i";
			$par [] = $idUtente;
		}	
		if (isset ( $status )) {
			if ($status==0){
				//closed
				$where .= " and not end_rent is null ";
			}
			if ($status==1){
				//open
				$where .= " and end_rent is null ";
			}
		}
	
		$query = "select
				noleggio.id,
				start_rent,
				end_rent,
				libro_fk,
				operatore_startrent_fk,
				operatore_endrent_fk,
				libro.codice_inventario,
				catalogo.titolo,
				catalogo.autore,
				utente.nome,
				utente.cognome,
				utente_fk as idutente,
				IF (end_rent is null, datediff(CURDATE(), start_rent), datediff(end_rent, start_rent)) as giorninoleggio
				from noleggio
				inner join  utente on utente.id= noleggio.utente_fk
				inner join libro  on libro.id= noleggio.libro_fk
				inner join catalogo on libro.catalogo_fk= catalogo.id "
				. $where;
				$mysqli = Db::getInstance ()->connectDb ();
				if (! isset ( $mysqli )) {
					error_log ( "[searchNoleggioByUtente] impossibile inizializzare il database" );
					$mysqli->close ();
					return $noleggi;
				}
				$stmt = $mysqli->stmt_init ();
				$stmt->prepare ( $query );
				if (! $stmt) {
					error_log ( "[searchNoleggioByUtente] impossibile" . " inizializzare il prepared statement" );
					$mysqli->close ();
					return $noleggi;
				}
	
				switch (count ( $par )) {
					case 1 :
						if (! $stmt->bind_param ( $bind, $par [0] )) {
							error_log ( "[searchNoleggio] impossibile" . " effettuare il binding in input" );
							$mysqli->close ();
							return $noleggi;
						}
						break;
					case 2 :
						if (! $stmt->bind_param ( $bind, $par [0], $par [1] )) {
							error_log ( "[searchNoleggioByUtente] impossibile" . " effettuare il binding in input" );
							$mysqli->close ();
							return $noleggi;
						}
						break;					
				}
	
				$noleggi = self::loadNoleggiExtendedFromStatement ( $stmt );
				$mysqli->close ();
				return $noleggi;
	}
	
	
	
	/**
	 *
	 * @param mysqli_stmt $stmt        	
	 * @return NULL|noleggio[]
	 */
	private function &loadNoleggiFromStatement(mysqli_stmt $stmt) {
		$noleggi = array ();
		if (! $stmt->execute ()) {
			error_log ( "[loadNoleggiFromStatement] impossibile" . " eseguire lo statement" );
			return null;
		}
		$row = array ();
		$bind = $stmt->bind_result (
				$row ['id'], 
				$row ['start_rent'],
				$row ['end_rent'],
				$row ['utente_fk'],
				$row ['libro_fk'],
				$row ['operatore_startrent_fk'],
				$row ['operatore_endrent_fk'] );
		if (! $bind) {
			error_log ( "[loadNoleggiFromStatement] impossibile" . " effettuare il binding in output" );
			return null;
		}
		while ( $stmt->fetch () ) {
			$noleggi [] = self::createNoleggioFromArray ( $row );
		}
		$stmt->close ();
		return $noleggi;
	}
		
	private function &loadNoleggiExtendedFromStatement(mysqli_stmt $stmt) {
		$noleggi = array ();
		if (! $stmt->execute ()) {
			error_log ( "[loadNoleggiExtendedFromStatement] impossibile" . " eseguire lo statement" );
			return null;
		}
		$row = array ();
		$bind = $stmt->bind_result (
				$row ['id'],
				$row ['start_rent'],
				$row ['end_rent'], 
				$row ['libro_fk'], 
				$row ['operatore_startrent_fk'], 
				$row ['operatore_endrent_fk'],
				$row ['codice_inventario'],
				$row ['titolo'],
				$row ['autore'],
				$row ['nome'],
				$row ['cognome'],
				$row ['idutente'],
				$row ['giorninoleggio']
				);
		if (! $bind) {
			error_log ( "[loadNoleggiExtendedFromStatement] impossibile" . " effettuare il binding in output" );
			return null;
		}
		while ( $stmt->fetch () ) {
			$noleggi [] = self::createNoleggioExtendedFromArray( $row );
		}
		$stmt->close ();
		return $noleggi;
	}
	
	public function createNoleggioExtendedFromArray($row) {
		$noleggio = new noleggio ();
		$noleggio->setId ( $row ['id'] );
		$noleggio->setStart_rent ( $row ['start_rent'] );
		$noleggio->setEnd_rent ( $row ['end_rent'] );
		$noleggio->setLibroFk( $row ['libro_fk'] );
		$noleggio->setOperatore_startrent ( $row ['operatore_startrent_fk'] );
		$noleggio->setOperatore_endrent ( $row ['operatore_endrent_fk'] );
		$noleggio->setCodiceInventario( $row ['codice_inventario'] );
		$noleggio->setTitolo( $row ['titolo'] );
		$noleggio->setautore( $row ['autore'] );
		$noleggio->setNome( $row ['nome'] );
		$noleggio->setCognome( $row ['cognome'] );
		$noleggio->setIdUtente( $row ['idutente'] );
		$noleggio->setGiorniNoleggio( $row ['giorninoleggio'] );	
		return $noleggio;
	}
	
	
	/**
	 *
	 * @param unknown $row        	
	 * @return noleggio
	 */
	public function createNoleggioFromArray($row) {
		$noleggio = new noleggio ();
		$noleggio->setId ( $row ['id'] );
		$noleggio->setStart_rent ( $row ['start_rent'] );
		$noleggio->setEnd_rent ( $row ['end_rent'] );
		$noleggio->setUtente_fk ( $row ['utente_fk'] );
		$noleggio->setLibro_fk ( $row ['libro_fk'] );
		$noleggio->setOperatore_startrent ( $row ['operatore_startrent_fk'] );
		$noleggio->setOperatore_endrent ( $row ['operatore_endrent_fk'] );
		return $noleggio;
	}
	/**
	 *
	 * @param noleggio $nol        	
	 * @return number|unknown
	 */
	public function updateNoleggio(noleggio $nol) {
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[updateNoleggio] impossibile inizializzare il database" );
			$mysqli->close ();
			return 0;
		}
		$stmt = $mysqli->stmt_init ();
		$count = 0;
		$query = " update noleggio set
                    end_rent = ?,
                    operatore_endrent_fk = ?                    
                    where noleggio.id = ?
                    ";
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[updateNoleggio] impossibile" . " inizializzare il prepared statement" );
			return 0;
		}
		if (! $stmt->bind_param ( 'sii', $nol->getEnd_rent (), $nol->getOperatore_endrentFk(), $nol->getId () )) {
			error_log ( "[updateNoleggio] impossibile" . " effettuare il binding in input" );
			return 0;
		}
		if (! $stmt->execute ()) {
			error_log ( "[updateNoleggio] impossibile" . " eseguire lo statement" );
			return 0;
		}
		$count = $stmt->affected_rows;
		$stmt->close ();
		$mysqli->close ();
		return $count;
	}
	/**
	 *
	 * @param unknown $noleggio        	
	 * @return number|unknown
	 */
	public function createNoleggio(noleggio $noleggio) {
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[createNoleggio] impossibile inizializzare il database" );
			$mysqli->close ();
			return 0;
		}
		$stmt = $mysqli->stmt_init ();
		$count = 0;
		$query = " insert into noleggio 
                    (start_rent , utente_fk , libro_fk , operatore_startrent_fk ) values ( ? ,  ? , ? , ? )";
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[createNoleggio] impossibile" . " inizializzare il prepared statement" );
			return 0;
		}
		if (! $stmt->bind_param ( 'siii', $noleggio->getStart_rent (), $noleggio->getIdUtente(), $noleggio->getLibroFk(), $noleggio->getOperatore_startrent_fk())) {
			error_log ( "[createNoleggio] impossibile" . " effettuare il binding in input" );
			return 0;
		}
		
		//inizio transazione
		$mysqli->autocommit(false);
		
		if (! $stmt->execute ()) {
			error_log ( "[createNoleggio] impossibile" . " eseguire lo statement" );
			$mysqli->rollback();
			return 0;
		}
		$count = $stmt->affected_rows;
		$stmt->close ();
		
		//commit del noleggio 
		$mysqli->commit();
		$mysqli->autocommit(true);
		$mysqli->close ();
		return $count;
	}
	/**
	 *
	 * @param unknown $id        	
	 * @return number|unknown
	 */
	public function deleteNoleggio($id) {
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[deleteNoleggio] impossibile inizializzare il database" );
			$mysqli->close ();
			return 0;
		}
		$stmt = $mysqli->stmt_init ();
		$count = 0;
		$query = " delete noleggio where id= ?";
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[deleteNoleggio] impossibile" . " inizializzare il prepared statement" );
			return 0;
		}
		if (! $stmt->bind_param ( 'i', $id )) {
			error_log ( "[deleteNoleggio] impossibile" . " effettuare il binding in input" );
			return 0;
		}
		if (! $stmt->execute ()) {
			error_log ( "[deleteNoleggio] impossibile" . " eseguire lo statement" );
			return 0;
		}
		$count = $stmt->affected_rows;
		$stmt->close ();
		$mysqli->close ();
		return $count;
	}
}

?>
