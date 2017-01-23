<?php
include_once 'Db.php';
include_once 'Catalogo.php';

/**
 * Classe per la creazione di libri nel catalogo
 */
class CatalogoFactory {
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
	 * Restiuisce un singleton per creare utenti operatori
	 *
	 * @return \CatalogoFactory
	 */
	public static function instance() {
		if (! isset ( self::$singleton )) {
			self::$singleton = new CatalogoFactory ();
		}
		return self::$singleton;
	}
	
	public static function AutoCompleteTitolo($titolo, $numresult){
		$titoli= array();
		$query = "select          
			C.Titolo		
			FROM catalogo C
			WHERE lower(titolo) like lower(?)
			LIMIT ?
				";
		
		$bind .= "si";
		$par [] = "%" . $titolo . "%";
		$par [] = $numresult;
				
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[AutoCompleteTitolo] impossibile inizializzare il database" );
			$mysqli->close ();
			return $titoli;
		}
		$stmt = $mysqli->stmt_init ();
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[AutoCompleteTitolo] impossibile" . " inizializzare il prepared statement" );
			$mysqli->close ();
			return $titoli;
		}
		if (! $stmt->bind_param ( $bind, $par [0] , $par[1])) {
			error_log ( "[AutoCompleteTitolo] impossibile" . " effettuare il binding in input" );
			$mysqli->close ();
			return $titoli;
		}
		if (! $stmt->execute ()) {
			error_log ( "[AutoCompleteTitolo] impossibile" . " eseguire lo statement" );
			return $titoli;
		}
		
		$bind = $stmt->bind_result ( $row ['Titolo'] );
		if (! $bind) {
			error_log ( "[AutoCompleteTitolo] impossibile" . " effettuare il binding in output" );
			return $titoli;		
		}
		while ( $stmt->fetch () ) {
			$titoli [] = ( $row ['Titolo'] );
		}
		$stmt->close ();
		return $titoli;
	}
	
	public static function AutoCompleteAutore($autore, $numresult){
		$autori= array();
		$query = "select
			C.Autore
			FROM catalogo C
			WHERE lower(Autore) like lower(?)
			LIMIT ?
				";
	
		$bind .= "si";
		$par [] = "%" . $autore . "%";
		$par [] = $numresult;
	
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[AutoCompleteAutore] impossibile inizializzare il database" );
			$mysqli->close ();
			return $autori;
		}
		$stmt = $mysqli->stmt_init ();
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[AutoCompleteAutore] impossibile" . " inizializzare il prepared statement" );
			$mysqli->close ();
			return $autori;
		}
		if (! $stmt->bind_param ( $bind, $par [0] , $par[1])) {
			error_log ( "[AutoCompleteAutore] impossibile" . " effettuare il binding in input" );
			$mysqli->close ();
			return $autori;
		}
		if (! $stmt->execute ()) {
			error_log ( "[AutoCompleteAutore] impossibile" . " eseguire lo statement" );
			return $autori;
		}
	
		$bind = $stmt->bind_result ( $row ['Titolo'] );
		if (! $bind) {
			error_log ( "[AutoCompleteAutore] impossibile" . " effettuare il binding in output" );
			return $autori;
		}
		while ( $stmt->fetch () ) {
			$autori [] = ( $row ['Titolo'] );
		}
		$stmt->close ();
		return $autori;
	}
	
	
	
	/**
	 *
	 * @param unknown $titolo        	
	 * @param unknown $autore        	
	 * @param unknown $id        	
	 * @return NULL|catalogo[]
	 */
	public function searchCatalog($titolo, $autore, $id) {
		$catalogItems = array ();
		
		// costruisco la where "a pezzi" a seconda di quante
		// variabili sono definite
		$bind = "";
		$where = " where 1=1 ";
		$par = array ();
		
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
		if (isset ( $id )) {
			$where .= " and id =? ";
			$bind .= "i";
			$par [] = $id;
		}
		$query = "select
            C.id,
			C.Anno, 
			C.Autore,
			C.Genere,
			C.Isbn, 
			C.Titolo, 
			(Select count(l.id )
			From libro l where l.catalogo_fk= C.id ) as Totale, 
			(Select count(distinct libro.id) as noleggiati from libro  
				inner join noleggio ON noleggio.libro_fk= libro.id 
				where libro.catalogo_fk= C.id And noleggio.end_rent is null ) as Noleggiati, 
			(Select count(l.id ) From libro l
				where l.catalogo_fk= C.id
				and l.noleggiabile= b'0' ) as NonNoleggiabili , 
			C.UrlImmagine
			FROM catalogo C				 
                  " . $where;
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[searchCatalog] impossibile inizializzare il database" );
			$mysqli->close ();
			return $catalogItems;
		}
		$stmt = $mysqli->stmt_init ();
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[searchCatalog] impossibile" . " inizializzare il prepared statement" );
			$mysqli->close ();
			return $catalogItems;
		}
		
		switch (count ( $par )) {
			case 1 :
				if (! $stmt->bind_param ( $bind, $par [0] )) {
					error_log ( "[searchCatalog] impossibile" . " effettuare il binding in input" );
					$mysqli->close ();
					return $catalogItems;
				}
				break;
			case 2 :
				if (! $stmt->bind_param ( $bind, $par [0], $par [1] )) {
					error_log ( "[searchCatalog] impossibile" . " effettuare il binding in input" );
					$mysqli->close ();
					return $catalogItems;
				}
				break;
			
			case 3 :
				if (! $stmt->bind_param ( $bind, $par [0], $par [1], $par [2] )) {
					error_log ( "[searchCatalog] impossibile" . " effettuare il binding in input" );
					$mysqli->close ();
					return $catalogItems;
				}
				break;
		}
		
		$catalogItems = self::loadCatalogItemsFromStatement ( $stmt );
		$mysqli->close ();
		return $catalogItems;
	}
	/**
	 *
	 * @param mysqli_stmt $stmt        	
	 * @return NULL|catalogo[]
	 */
	private function &loadCatalogItemsFromStatement($stmt) {
		$catalogItems = array ();
		if (! $stmt->execute ()) {
			error_log ( "[loadCataloghiFromStatement] impossibile" . " eseguire lo statement" );
			return null;
		}
		$row = array ();
		$bind = $stmt->bind_result ( $row ['id'], $row ['anno'], $row ['autore'], $row ['genere'], $row ['isbn'], $row ['titolo'], 
				$row ['Totale'], $row ['Noleggiati'], $row ['NonNoleggiabili'], $row ['UrlImmagine'] );
		if (! $bind) {
			error_log ( "[loadCataloghiFromStatement] impossibile" . " effettuare il binding in output" );
			return null;
		}
		while ( $stmt->fetch () ) {
			$catalogItems [] = self::createCatalogoFromArray ( $row );
		}
		$stmt->close ();
		return $catalogItems;
	}
	/**
	 *
	 * @param unknown $row        	
	 * @return catalogo
	 */
	public function createCatalogoFromArray($row) {
		$catalogo = new catalogo ();
		$catalogo->setId ( $row ['id'] );
		$catalogo->setAnno ( $row ['anno'] );
		$catalogo->setAutore ( $row ['autore'] );
		$catalogo->setGenere ( $row ['genere'] );
		$catalogo->setIsbn ( $row ['isbn'] );
		$catalogo->setTitolo ( $row ['titolo'] );
		$catalogo->setTotaleLibri ( $row ['Totale'] );
		$catalogo->setNoleggiati ( $row ['Noleggiati'] );
		$catalogo->setNonNoleggiabili ( $row ['NonNoleggiabili'] );
		$catalogo->setUrlImmagine( $row['UrlImmagine'] );
		return $catalogo;
	}
	/**
	 *
	 * @param catalogo $cat        	
	 * @return number|unknown
	 */
	public function updateCatalogo(catalogo $cat) {
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[updateCatalogo] impossibile inizializzare il database" );
			$mysqli->close ();
			return 0;
		}
		$stmt = $mysqli->stmt_init ();
		$count = 0;
		$query = " update catalogo set
                    anno = ?,
                    titolo = ?,
                    autore = ?,
                    isbn= ?,
                    genere = ?,
					UrlImmagine= ?
                    where catalogo.id = ?
                    ";
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[updateCatalogo] impossibile" . " inizializzare il prepared statement" );
			return 0;
		}
		if (! $stmt->bind_param ( 'ssssssi', $cat->getAnno (), $cat->getTitolo (), $cat->getAutore (), $cat->getIsbn (), $cat->getGenere (), $cat->getUrlImmagine(), $cat->getId () )) {
			error_log ( "[updateCatalogo] impossibile" . " effettuare il binding in input" );
			return 0;
		}
		if (! $stmt->execute ()) {
			error_log ( "[updateCatalogo] impossibile" . " eseguire lo statement" );
			return 0;
		}
		$count = $stmt->affected_rows;
		$stmt->close ();
		$mysqli->close ();
		return $count;
	}
	/**
	 *
	 * @param unknown $catalogo        	
	 * @return number|unknown
	 */
	public function createCatalogo(catalogo $catalogo) {
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[createCatalogo] impossibile inizializzare il database" );
			$mysqli->close ();
			return 0;
		}
		$stmt = $mysqli->stmt_init ();
		$count = 0;
		$query = " insert into catalogo 
                    (anno , titolo , autore ,isbn , genere, UrlImmagine ) values ( ? , ? , ? , ? , ?, ? )";
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[createCatalogo] impossibile" . " inizializzare il prepared statement" );
			return 0;
		}
		if (! $stmt->bind_param ( 'ssssss', $catalogo->getAnno (), $catalogo->getTitolo (), 
				$catalogo->getAutore (), 
				$catalogo->getIsbn (), $catalogo->getGenere (),  $catalogo->getUrlImmagine() )) {
			error_log ( "[createCatalogo] impossibile" . " effettuare il binding in input" );
			return 0;
		}
		if (! $stmt->execute ()) {
			error_log ( "[createCatalogo] impossibile" . " eseguire lo statement" );
			return 0;
		}
		$count = $stmt->affected_rows;
		$stmt->close ();
		$mysqli->close ();
		return $count;
	}
	/**
	 * 
	 * @param unknown $id
	 * @return number|unknown
	 */
	public function deleteCatalogo($id) {
		$mysqli = Db::getInstance ()->connectDb ();
		if (! isset ( $mysqli )) {
			error_log ( "[deleteCatalogo] impossibile inizializzare il database" );
			$mysqli->close ();
			return 0;
		}
		$stmt = $mysqli->stmt_init ();
		$count = 0;
		$query = " delete from catalogo where id= ?";
		$stmt->prepare ( $query );
		if (! $stmt) {
			error_log ( "[deleteCatalogo] impossibile" . " inizializzare il prepared statement" );
			return 0;
		}
		if (! $stmt->bind_param ( 'i', $id )) {
			error_log ( "[deleteCatalogo] impossibile" . " effettuare il binding in input" );
			return 0;
		}
		if (! $stmt->execute ()) {
			error_log ( "[deleteCatalogo] impossibile" . " eseguire lo statement" );
			return 0;
		}
		$count = $stmt->affected_rows;
		$stmt->close ();
		$mysqli->close ();
		return $count;
	}
}

?>
