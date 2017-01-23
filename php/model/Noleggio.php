<?php

include_once 'Utente.php';
include_once 'Operatore.php';
include_once 'Libro.php';

class noleggio
{
	
	
	private $libro_fk;
	private $operatore_startrent_fk;
	private $operatore_endrent_fk;
	
	
	/**
	 * Data inizio noleggio
	 * @var datetime
	 */
	private $start_rent;
	
	/**
	 * Data fine noleggio
	 * @var datetime
	 */
	private $end_rent;
		
	/**
	 * Identificatore del noleggio
	 * @var int
	 */
	private $id;
	
	
	private $codiceInventario;
	private $titolo;
	private $autore;
	private $Nome;
	private $Cognome;
	private $idUtente;
	private $giorniNoleggio;
	
	
	
	public function getCodiceInventario() {
		return $this->codiceInventario;
	}
	public function getTitolo() {
		return $this->titolo;
	}
	public function getAutore() {
		return $this->autore;
	}
	public function getNome() {
		return $this->Nome;
	}
	public function getCognome() {
		return $this->Cognome;
	}
	public function getIdUtente() {
		return $this->idUtente;
	}
	public function getGiorniNoleggio() {
		return $this->giorniNoleggio;
	}
	
	public function setNome($nome){
		$this->Nome=$nome;
		return true;
	}
	public function setCognome($cognome){
		$this->Cognome= $cognome;
		return true;
	}
	public function setCodiceInventario($codiceinventario){
		$this->codiceInventario=$codiceinventario;
		return true;
	}
	public function setTitolo($valore){
		$this->titolo=$valore;
		return true;
	}
	public function setautore($valore){
		$this->autore=$valore;
		return true;
	}
	public function setIdUtente($valore){
		$this->idUtente=$valore;
		return true;
	}
	public function setGiorniNoleggio($valore){
		$this->giorniNoleggio=$valore;
		return true;
	}
	public function getStart_rent() {
		return $this->start_rent;
	}
	public function getEnd_rent() {
		return $this->end_rent;
	}
	public function setStart_rent($valore){
		$this->start_rent=$valore;
		return true;
	}
	public function setEnd_rent($valore){
		$this->end_rent=$valore;
		return true;
	}
	
	
	public function getLibroFk() {
		return $this->libro_fk;
	}
	public function setLibroFk($valore){
		$this->libro_fk=$valore;
		return true;
	}
	
	public function getOperatore_startrent_fk() {
		return $this->operatore_startrent_fk;
	}
	public function setOperatore_startrent($valore){
		$this->operatore_startrent_fk=$valore;
		return true;
	}
	
	public function getOperatore_endrentFk() {
		return $this->operatore_endrent_fk;
	}
	public function setOperatore_endrent($valore){
		$this->operatore_endrent_fk=$valore;
		return true;
	}
	
	
	
	
	
	
	/**
	 * Costruttore
	 */
	public function __construct() {
	
	}
	
	/**
	 * Restituisce un identificatore unico per il noleggio
	 * @return int
	 */
	public function getId(){
		return $this->id;
	}
	
	/**
	 * Imposta un identificatore unico per il noleggio
	 * @param int $id
	 * @return boolean true se il valore e' stato aggiornato correttamente,
	 * false altrimenti
	 */
	public function setId($id){
		$intVal = filter_var($id, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
		if(!isset($intVal)){
			return false;
		}
		$this->id = $intVal;
	}	
}


?>