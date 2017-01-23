<?php



class utente
{
	
	private $noleggiAperti;
	
	
	/**
	 * Username per l'autenticazione
	 * @var string
	 */
	private $username;
	
	/**
	 * Password per l'autenticazione
	 * @var string
	 */
	private $password;
	
	/**
	 * Nome dell'utente
	 * @var string
	 */
	private $nome;
	
	/**
	 * Cognome dell'utente
	 * @var string
	 */
	private $cognome;
	
	/**
	 * email dell'utente
	 * @var string
	 */
	private $email;
	
	/**
	 * indirizzo utente
	 * @var string
	 * */
	private	$indirizzo;
	
	/**
	 * cellulare
	 * @var string
	 */
	private $cellulare;
	
	/**
	 * indica se l'utente è abilitato
	 * @var boolean
	 */
	private $abilitato;
	
	/**
	 * Identificatore dell'utente
	 * @var int
	 */
	private $id;
	
	
	/**
	 * Costruttore
	 */
	public function __construct() {
	
	}
	
	
	/**
	 * Restituisce lo username dell'utente
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}
	
	public function setUsername($username) {
		// utilizzo la funzione filter var specificando un'espressione regolare
		// che implementa la validazione personalizzata
		//if (!filter_var($username, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/[a-zA-Z]{8,}/')))) {
			//return false;
		//}
		$this->username = $username;
		return true;
	}
	
	/**
	 * nome utente
	 * @return string
	 */
	public function getNome(){
		return $this->nome;
	}
	public function getCognome(){
		return $this->cognome;
	}
	
	/**
	 * imposta nome dell'utente
	 * @param string $nome
	 * @return boolean
	 */
	public function setNome($nome){
		$this->nome=$nome;		
		return true;	
	}
	
	public function setCognome($cognome){
		$this->cognome=$cognome;
		return true;
	}
	
	/**
	 * indica se l'utente è abilitato
	 * @return boolean
	 */
	public function getAbilitato(){
		return $this->abilitato;
	}
	
	
	public function setNoleggiAperti($valore){
		$this->noleggiAperti= $valore;
		return true;
	}
	
	
	public function getNoleggiAperti(){
		return $this->noleggiAperti;
	}
	
	
	/**
	 * 
	 * @param unknown $Abilitato
	 * @return boolean
	 */
	public function setAbilitato($Abilitato){
		$boolVal= filter_var($Abilitato, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
		if(!isset($boolVal)){
			return false;
		}
		$this->abilitato = $boolVal;
		return true;
	}
	
	/**
	 * Restituisce un identificatore unico per l'utente
	 * @return int
	 */
	public function getId(){
		return $this->id;
	}
	
	/**
	 * Imposta un identificatore unico per l'utente
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
		return true;
	}
	
	/**
	 * email operatore
	 * @return string
	 */
	public function getEmail(){
		return $this->email;
	}
	
	/**
	 * imposta email dell'operatore
	 * @param string $email
	 * @return boolean
	 */
	public function setEmail($email){
		$this->email=$email;
		return true;
	}
	
	/**
	 * cellulare operatore
	 * @return string
	 */
	public function getCellulare(){
		return $this->cellulare;
	}
	
	/**
	 * imposta cellulare dell'operatore
	 * @param string $cellulare
	 * @return boolean
	 */
	public function setCellulare($cellulare){
		$this->cellulare=$cellulare;
		return true;
	}
	
	
	/**
	 * Indirizzo operatore
	 * @return string
	 */
	public function getIndirizzo(){
		return $this->indirizzo;
	}
	
	/**
	 * imposta Indirizzo dell'operatore
	 * @param string $Indirizzo
	 * @return boolean
	 */
	public function setIndirizzo($Indirizzo){
		$this->indirizzo=$Indirizzo;
		return true;
	}
	
	
	
	/**
	 * Compara due utenti, verificandone l'uguaglianza logica
	 * @param User $utente l'utente con cui comparare $this
	 * @return boolean true se i due oggetti sono logicamente uguali,
	 * false altrimenti
	 */
	public function equals(utente $utente) {
	
		return  $this->id == $utente->id &&
		$this->nome == $utente->nome &&
		$this->cognome == $utente->cognome;
	}
	
	
}


?>