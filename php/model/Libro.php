<?php

include_once 'Catalogo.php';

class libro
{
	
	/**
	 * @var string
	 */
	private $codice_inventario;
	
	/**
	 * Noleggiabile o meno
	 * @var bit
	 */
	private $noleggiabile;
	
	/**
	 * Note sul libro
	 * @var string
	 */
	private $note;
	
	/**
	 * Identificatore del libro
	 * @var int
	 */
	private $id;
	
	
	private $catalogo_fk;
	
	/**
	 * 
	 * @var boolean
	 */
	private $noleggiato;
	/**
	 * 
	 * @var Catalogo
	 */
	private $Catalogo;
	
	
	/**
	 * Costruttore
	 */
	public function __construct() {
	
	}
	


	public function getCatalogo_fk(){
		return $this->catalogo_fk;
	}	
	
	public function getNoleggiato(){
		return $this->noleggiato;
	}
	
	public function getCatalogo(){		
		return $this->Catalogo;
	}
	
	public function setCatalogo_fk($catalogo_fk) {
		$this->catalogo_fk=$catalogo_fk;
		return true;
	}
	
	
	public function setNoleggiato($noleggiato) {
		$boolVal= filter_var($noleggiato, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
		if(!isset($boolVal)){
			return false;
		}
		$this->noleggiato = $boolVal;
		return true;
	}
	
	
	public function setCatalogo(catalogo $Catalogo) {
		$this->Catalogo=$Catalogo;
		return true;
	}
	
	
	
	/**
	 * Restituisce il codice_inventario del libro
	 * @return string
	 */
	public function getcodice_inventario() {
		return $this->codice_inventario;
	}
	/**
	 * imposta il codice_inventario
	 * @param string $codice_inventario
	 * @return boolean
	 */
	public function setcodice_inventario($codice_inventario) {
		$this->codice_inventario=$codice_inventario;
		return true;
	}
	
	/**
	 * Restituisce le note sul libro
	 * @return string
	 */
	public function getnote(){
		return $this->note;
	}
	
	/**
	 * imposta note sul libro
	 * @param string $note
	 * @return boolean
	 */
	public function setnote($note){
		$this->note=$note;		
		return true;	
	}
	
	/**
	 * indica se il libro sia noleggiabile
	 * @return boolean
	 */
	public function getnoleggiabile(){
		return $this->noleggiabile;
	}
	
	/**
	 * 
	 * @param unknown $noleggiabile
	 * @return boolean
	 */
	public function setnoleggiabile($noleggiabile){
		$boolVal= filter_var($noleggiabile, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
		if(!isset($boolVal)){
			return false;
		}
		$this->noleggiabile = $boolVal;
	}
	
	/**
	 * Restituisce un identificatore unico per il libro
	 * @return int
	 */
	public function getId(){
		return $this->id;
	}
	
	/**
	 * Imposta un identificatore unico per il libro
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
	
	
	/**
	 * Compara due libri, verificandone l'uguaglianza logica
	 * @param codice_inventario $codice_inventario il codice_inventario con cui comparare $this
	 * @return boolean true se i due oggetti sono logicamente uguali,
	 * false altrimenti
	 */
	public function equals(codice_inventario $codice_inventario) {
	
		return  $this->id == $codice_inventario->id &&
		$this->codice_inventario == $codice_inventario->codice_inventario;
	}
	
	
}


?>