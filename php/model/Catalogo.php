<?php
class catalogo {
	
	/**
	 * Titolo del libro
	 * 
	 * @var string
	 */
	private $titolo;
	
	/**
	 * Autore del libro
	 * 
	 * @var string
	 */
	private $autore;
	
	/**
	 * Genere del libro
	 * 
	 * @var string
	 */
	private $genere;
	
	/**
	 * Anno uscita libro
	 * 
	 * @var string
	 */
	private $anno;
	
	/**
	 * Immagine copertina libro
	 * 
	 * @var blob
	 */
	private $urlImmagine;
	
	/**
	 * indica il codice del libro
	 * 
	 * @var string
	 *
	 */
	private $isbn;
	
	/**
	 * Identificatore del catalogo
	 * 
	 * @var int
	 */
	private $id;
	private $totaleLibri;
	private $noleggiati;
	private $nonNoleggiabili;
	
	/**
	 * Costruttore
	 */
	public function __construct() {
	}
	
	/**
	 * Restituisce il titolo del libro a catalogo
	 * 
	 * @return string
	 */
	public function getTitolo() {
		return $this->titolo;
	}
	public function getTotaleLibri() {
		return $this->totaleLibri;
	}
	public function getNoleggiati() {
		return $this->noleggiati;
	}
	public function getNonNoleggiabili() {
		return $this->nonNoleggiabili;
	}
	public function setTotaleLibri($totaleLibri) {
		$this->totaleLibri = $totaleLibri;
		return true;
	}
	public function setNoleggiati($noleggiati) {
		$this->noleggiati = $noleggiati;
		return true;
	}
	public function setNonNoleggiabili($nonNoleggiabili) {
		$this->nonNoleggiabili = $nonNoleggiabili;
		return true;
	}
	
	/**
	 * imposta il titolo del libro a catalogo
	 * 
	 * @param string $titolo        	
	 * @return boolean
	 */
	public function setTitolo($titolo) {
		$this->titolo = $titolo;
		return true;
	}
	
	/**
	 * restituisce autore del libro
	 * 
	 * @return string
	 */
	public function getAutore() {
		return $this->autore;
	}
	
	/**
	 * imposta autore del titolo
	 * 
	 * @param string $autore        	
	 * @return boolean
	 */
	public function setAutore($autore) {
		$this->autore = $autore;
		return true;
	}
	
	/**
	 * Restituisce il genere del libro a catalogo
	 * 
	 * @return string
	 */
	public function getGenere() {
		return $this->genere;
	}
	
	/**
	 * imposta il genere del libro a catalogo
	 * 
	 * @param string $genere        	
	 * @return boolean
	 */
	public function setGenere($genere) {
		$this->genere = $genere;
		return true;
	}
	
	/**
	 * Restituisce l'anno del libro a catalogo
	 * 
	 * @return string
	 */
	public function getAnno() {
		return $this->anno;
	}
	
	public function getUrlImmagine() {
		return $this->urlImmagine;
	}
	
	/**
	 * imposta l'anno del libro a catalogo
	 * 
	 * @param string $anno        	
	 * @return boolean
	 */
	public function setAnno($anno) {
		$this->anno = $anno;
		return true;
	}
	
	public function setUrlImmagine($urlImmagine) {
		$this->urlImmagine = $urlImmagine;
		return true;
	}
	
	/**
	 * Restituisce isbn del libro a catalogo
	 * 
	 * @return string
	 */
	public function getIsbn() {
		return $this->isbn;
	}
	
	/**
	 * imposta il titolo del libro a catalogo
	 * 
	 * @param string $isbn        	
	 * @return boolean
	 */
	public function setIsbn($isbn) {
		$this->isbn = $isbn;
		return true;
	}
	/**
	 * Restituisce un identificatore unico per l'utente
	 * 
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Imposta un identificatore unico per il libro a catalogo
	 * 
	 * @param int $id        	
	 * @return boolean true se il valore e' stato aggiornato correttamente,
	 *         false altrimenti
	 */
	public function setId($id) {
		$intVal = filter_var ( $id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE );
		if (! isset ( $intVal )) {
			return false;
		}
		$this->id = $intVal;
	}
	
	/**
	 * Compara due utenti, verificandone l'uguaglianza logica
	 * 
	 * @param User $utente
	 *        	l'utente con cui comparare $this
	 * @return boolean true se i due oggetti sono logicamente uguali,
	 *         false altrimenti
	 */
	public function equals(catalogo $catalogo) {
		return $this->id == $catalogo->id && $this->titolo == $catalogo->titolo && $this->autore == $catalogo->autore;
	}
}

?>