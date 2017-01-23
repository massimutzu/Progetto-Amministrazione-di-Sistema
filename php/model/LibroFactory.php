<?php
include_once basename ( __DIR__ ) . '/../Settings.php';
include_once 'Db.php';
include_once 'Libro.php';

/**
 * Classe per la creazione dei libri, ovvero le istanze fisiche della entità catalogo
 */
class LibroFactory {
	private static $singleton;
	private function __constructor() {
	}
	
	/**
	 * Restiuisce un singleton per creare utenti
	 *
	 * @return \LibroFactory
	 */
	public static function instance() {
		if (! isset ( self::$singleton )) {
			self::$singleton = new LibroFactory ();
		}
		return self::$singleton;
	}
	
	
	/**
	 * 
	 * @param unknown $idCatalogo
	 * @param unknown $inventario
	 * @param unknown $id
	 * 
	 * @return libro
	 */
	public function searchLibriByParam($idCatalogo, $inventario, $id) {
		$libri = array ();
		
		// costruisco la where "a pezzi" a seconda di quante
		// variabili sono definite
		$bind = "";
		$where = " where 1=1 ";
		$par = array ();
		
		if (isset ( $idCatalogo )) {
			$where .= " and catalogo_fk =? ";
			$bind .= "i";
			$par [] = $idCatalogo;
		}
		if (isset ( $inventario )) {
			$where .= " and lower(codice_inventario) like lower(?) ";
			$bind .= "s";
			$par [] = "%" . $inventario . "%";
		}
		if (isset ( $id )) {
			$where .= " and id =? ";
			$bind .= "i";
			$par [] = $id;
		}
		$query = "select
				id,
				codice_inventario,
				noleggiabile, 
				note,
				catalogo_fk,
				IF(	(select count(noleggio.id) from noleggio where noleggio.libro_fk=libro.id and noleggio.end_rent is null)>0,1,0) as noleggiato
				from libro " . $where;
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[searchLibriByParam] impossibile inizializzare il database" );
			$mysqli->close ();
			return $$libri;
		}
		$stmt = $mysqli->stmt_init ();
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[searchLibriByParam] impossibile" . " inizializzare il prepared statement" );
			$mysqli->close ();
			return $$libri;
		}
		
		switch (count ( $par )) {
			case 1 :
				if (! $stmt->bind_param ( $bind, $par [0] )) {
					error_log ( "[searchLibriByParam] impossibile" . " effettuare il binding in input" );
					$mysqli->close ();
					return $$libri;
				}
				break;
			case 2 :
				if (! $stmt->bind_param ( $bind, $par [0], $par [1] )) {
					error_log ( "[searchLibriByParam] impossibile" . " effettuare il binding in input" );
					$mysqli->close ();
					return $$libri;
				}
				break;
			
			case 3 :
				if (! $stmt->bind_param ( $bind, $par [0], $par [1], $par [2] )) {
					error_log ( "[searchLibriByParam] impossibile" . " effettuare il binding in input" );
					$mysqli->close ();
					return $$libri;
				}
				break;
		}
		
		$$libri = self::loadLibriFromStatement ( $stmt );
		$mysqli->close ();
		return $$libri;
	}
	
	private function &loadLibriFromStatement(mysqli_stmt $stmt) {
		$libri = array ();
		if (! $stmt->execute ()) {
			error_log ( "[loadLibriFromStatement] impossibile eseguire lo statement" );
			return null;
		}
		$row = array ();
		$bind = $stmt->bind_result ( 
				$row ['id'], 
				$row ['codice_inventario'],
				$row ['noleggiabile'], 
				$row ['note'], 
				$row ['catalogo_fk'], 
				$row ['noleggiato'] );
		if (! $bind) {
			error_log ( "[loadLibriFromStatement] impossibile  effettuare il binding in output" );
			return null;
		}
		while ( $stmt->fetch () ) {
			$libri [] = self::createLibroFromArray( $row );
		}
		$stmt->close ();
		return $libri;
	}
	
	public function createLibroFromArray($row) {
		$libro = new libro();
		$libro->setId ( $row ['id'] );
		$libro->setcodice_inventario( $row ['codice_inventario'] );
		$libro->setnoleggiabile( $row ['noleggiabile'] );
		$libro->setnote( $row ['note'] );
		$libro->setCatalogo_fk( $row ['catalogo_fk'] );
		$libro->setNoleggiato( $row ['noleggiato'] );
		return $libro;
	}
	
	public function addLibroToCatalogo(libro $libro) {
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[addLibroToCatalogo] impossibile inizializzare il database" );
			$mysqli->close ();
			return 0;
		}
		$stmt = $mysqli->stmt_init ();
		$count = 0;
		$query = " insert into libro
                    (codice_inventario , noleggiabile ,note ,catalogo_fk ) values ( 'temp' , ? , ? , ? )";
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[addLibroToCatalogo] impossibile" . " inizializzare il prepared statement" );
			return 0;
		}
		if (! $stmt->bind_param ( 'isi', $libro->getnoleggiabile (), $libro->getnote (), $libro->getCatalogo_fk () )) {
			error_log ( "[addLibroToCatalogo] impossibile" . " effettuare il binding in input" );
			return 0;
		}
		if (! $stmt->execute ()) {
			error_log ( "[addLibroToCatalogo] impossibile" . " eseguire lo statement" );
			return 0;
		}
		$count = $stmt->affected_rows;
		
		$stmt->close ();
		
		// prendiamo l'id appena inserito del libro
		$lastid = $mysqli->insert_id;
		// creiamo un codice inventario composto da un prefisso e una lunghezza impostati nei settings..
		$codicePad = Settings::$inventarioPreCode . str_pad ( $lastid, Settings::$inventarioPadNums, '0', STR_PAD_LEFT );
		
		$stmt = $mysqli->stmt_init ();
		$query = " update libro set codice_inventario = ? where id= ?";
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[addLibroToCatalogo] impossibile" . " inizializzare il prepared statement" );
			return 0;
		}
		if (! $stmt->bind_param ( 'si', $codicePad, $lastid )) {
			error_log ( "[addLibroToCatalogo] impossibile" . " effettuare il binding in input" );
			return 0;
		}
		if (! $stmt->execute ()) {
			error_log ( "[addLibroToCatalogo] impossibile" . " eseguire lo statement" );
			return 0;
		}
		$count = $stmt->affected_rows;
		
		$stmt->close ();
		
		$mysqli->close ();
		return $count;
	}
	
	public function deleteLibro($id) {
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[deleteLibro] impossibile inizializzare il database" );
			$mysqli->close ();
			return 0;
		}
		$stmt = $mysqli->stmt_init ();
		$count = 0;
		$query = " delete from libro where id= ?";
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[deleteLibro] impossibile" . " inizializzare il prepared statement" );
			return 0;
		}
		if (! $stmt->bind_param ( 'i', $id )) {
			error_log ( "[deleteLibro] impossibile" . " effettuare il binding in input" );
			return 0;
		}
		if (! $stmt->execute ()) {
			error_log ( "[deleteLibro] impossibile" . " eseguire lo statement" );
			return 0;
		}
		$count = $stmt->affected_rows;
		$stmt->close ();
		$mysqli->close ();
		return $count;
	}
	



	public function updateNoleggiabile($id, $noleggiabile) {
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[updateNoleggiabile] impossibile inizializzare il database" );
			$mysqli->close ();
			return 0;
		}
		$stmt = $mysqli->stmt_init ();
		$count = 0;
		$query = " update libro set
                    noleggiabile = ?
                    where libro.id = ?";
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[updateNoleggiabile] impossibile" . " inizializzare il prepared statement" );
			return 0;
		}
		if (! $stmt->bind_param ( 'ii', $noleggiabile,$id  )) {
			error_log ( "[updateNoleggiabile] impossibile" . " effettuare il binding in input" );
			return 0;
		}
		if (! $stmt->execute ()) {
			error_log ( "[updateNoleggiabile] impossibile" . " eseguire lo statement" );
			return 0;
		}
		$count = $stmt->affected_rows;
		$stmt->close ();
		$mysqli->close ();
		return $count;
	}
	
	
	public function updateNote($id, $note) {
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[updateNote] impossibile inizializzare il database" );
			$mysqli->close ();
			return 0;
		}
		$stmt = $mysqli->stmt_init ();
		$count = 0;
		$query = " update libro set                   
                    note = ?                    
                    where libro.id = ?";
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[updateNote] impossibile" . " inizializzare il prepared statement" );
			return 0;
		}
		if (! $stmt->bind_param ( 'si',$note, $id )) {
			error_log ( "[updateNote] impossibile" . " effettuare il binding in input" );
			return 0;
		}
		if (! $stmt->execute ()) {
			error_log ( "[updateNote] impossibile" . " eseguire lo statement" );
			return 0;
		}
		$count = $stmt->affected_rows;
		$stmt->close ();
		$mysqli->close ();
		return $count;
	}
}

?>
