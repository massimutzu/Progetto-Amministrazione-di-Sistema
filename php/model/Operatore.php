<?php
class operatore {
	
	/**
	 * Username per l'autenticazione
	 *
	 * @var string
	 */
	private $username;
	
	/**
	 * Password per l'autenticazione
	 *
	 * @var string
	 */
	private $password;
	
	/**
	 * Nome dell'operatore
	 *
	 * @var string
	 */
	private $nome;
	
	/**
	 * Cognome dell'operatore
	 *
	 * @var string
	 */
	private $cognome;
	
	/**
	 * email dell'operatore
	 *
	 * @var string
	 */
	private $email;
	
	/**
	 * cellulare
	 *
	 * @var string
	 */
	private $cellulare;
	
	/**
	 * indica se l'operatore è abilitato ad operare nel portale
	 *
	 * @var unknown
	 */
	private $abilitato;
	
	/**
	 * indica se l'utente è amministratore di altri operatori
	 *
	 * @var bool
	 */
	private $admin;
	
	/**
	 * Identificatore dell'operatore
	 *
	 * @var int
	 */
	private $id;
	
	/**
	 * Costruttore
	 */
	public function __construct() {
	}
	
	/**
	 * Restituisce lo username dell'operatore
	 *
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}
	public function setUsername($username) {
		// utilizzo la funzione filter var specificando un'espressione regolare
		// che implementa la validazione personalizzata
		if (! filter_var ( $username, FILTER_VALIDATE_REGEXP, array (
				'options' => array (
						'regexp' => '/[a-zA-Z]{8,}/' 
				) 
		) )) {
			return false;
		}
		$this->username = $username;
		return true;
	}
	
	/**
	 * nome operatore
	 *
	 * @return string
	 */
	public function getNome() {
		return $this->nome;
	}
	
	/**
	 * imposta nome dell'operatore
	 *
	 * @param string $nome        	
	 * @return boolean
	 */
	public function setNome($nome) {
		$this->nome = $nome;
		return true;
	}
	
	/**
	 * cognome operatore
	 *
	 * @return string
	 */
	public function getCognome() {
		return $this->cognome;
	}
	
	/**
	 * imposta cognome dell'operatore
	 *
	 * @param string $cognome        	
	 * @return boolean
	 */
	public function setCognome($cognome) {
		$this->cognome = $cognome;
		return true;
	}
	/**
	 *
	 * @param unknown $abilitato        	
	 * @return boolean
	 */
	public function setAbilitato($value) {
		
		$boolVal= filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
		if(!isset($boolVal)){
			return false;
		}
		$this->abilitato = $boolVal;
		return true;
	}
	/**
	 */
	public function getAbilitato() {
		return $this->abilitato;
	}
	
	/**
	 * email operatore
	 *
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}
	
	/**
	 * imposta email dell'operatore
	 *
	 * @param string $email        	
	 * @return boolean
	 */
	public function setEmail($email) {
		$this->email = $email;
		return true;
	}
	
	/**
	 * cellulare operatore
	 *
	 * @return string
	 */
	public function getCellulare() {
		return $this->cellulare;
	}
	
	/**
	 * imposta cellulare dell'operatore
	 *
	 * @param string $cellulare        	
	 * @return boolean
	 */
	public function setCellulare($cellulare) {
		$this->cellulare = $cellulare;
		return true;
	}
	
	/**
	 * indica se l'operatore è anche amministratore
	 * 
	 * @return boolean
	 */
	public function getIsAdmin() {
		return $this->admin;
	}
	
	/**
	 * imposta admin profile
	 * 
	 * @param boolean $value        	
	 * @return boolean
	 */
	public function setIsAdmin($value) {
		$boolVal= filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
		if(!isset($boolVal)){
			return false;
		}
		$this->admin = $boolVal;
		return true;
	}
	
	/**
	 * Restituisce un identificatore unico per l'operatore
	 *
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Imposta un identificatore unico per l'operatore
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
		return true;
	}
	
	/**
	 * Compara due operatori, verificandone l'uguaglianza logica
	 *
	 * @param User $operatore
	 *        	l'operatore con cui comparare $this
	 * @return boolean true se i due oggetti sono logicamente uguali,
	 *         false altrimenti
	 */
	public function equals(operatore $operatore) {
		return $this->id == $operatore->id && $this->nome == $operatore->nome && $this->cognome == $operatore->cognome;
	}
}

?>