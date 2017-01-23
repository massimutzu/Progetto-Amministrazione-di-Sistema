<?php
include_once basename ( __DIR__ ) . '/../view/ViewDescriptor.php';
include_once basename ( __DIR__ ) . '/../model/Utente.php';
include_once basename ( __DIR__ ) . '/../model/Operatore.php';
include_once basename ( __DIR__ ) . '/../model/Catalogo.php';
include_once basename ( __DIR__ ) . '/../model/Libro.php';
include_once basename ( __DIR__ ) . '/../model/Noleggio.php';
include_once basename ( __DIR__ ) . '/../model/UtenteFactory.php';
include_once basename ( __DIR__ ) . '/../model/OperatoreFactory.php';
include_once basename ( __DIR__ ) . '/../model/CatalogoFactory.php';
include_once basename ( __DIR__ ) . '/../model/NoleggioFactory.php';
include_once basename ( __DIR__ ) . '/../model/LibroFactory.php';

/**
 * @author Massimiliano Mutzu Martis
 * Controller che gestisce gli utenti non autenticati,
 */
class BaseController {
	const user = 'user';
	const role = 'role';
	const impersonato = '_imp';
	
	/**
	 * Costruttore
	 */
	public function __construct() {
	}
	
	/**
	 * Gestione Input
	 *
	 * @param type $request
	 *        	la richiesta da gestire
	 */
	public function handleInput(&$request) {
		$vd = new ViewDescriptor ();
		
		// imposta la pagina
		$vd->setPagina ( $request ['page'] );
		
		// gestion dei comandi
		// tutte le variabili che vengono create senza essere utilizzate
		// direttamente in questo switch, sono quelle che vengono poi lette
		// dalla vista, ed utilizzano le classi del modello
		
		if (isset ( $request ["cmd"] )) {
			// abbiamo ricevuto un comando
			switch ($request ["cmd"]) {
				case 'login' :
					$username = isset ( $request ['user'] ) ? $request ['user'] : '';
					$password = isset ( $request ['password'] ) ? $request ['password'] : '';
					$this->login ( $vd, $username, $password );
					break;
				default :
					$this->showLoginPage ();
			}
		} else {
			
			if ($this->loggedIn ()) {
				
				if ($request ["page"] == "logout") {
					$this->logout ( $vd );
				} else {
					switch ($this->userRole ()) {
						case 'utente' :
							$id = $this->loggedUserId ();
							$utente = utenteFactory::instance ()->getUtenteById ( $id );
							$this->showHomeUtente ( $vd, $utente );
							break;
						case 'operatore' :
							$id = $this->loggedUserId ();
							$utente = OperatoreFactory::instance ()->getOperatoreById ( $id );
							$this->showHomeOperatore ( $vd, $utente );
							break;
					}
				}
			} else {
				// utente non autenticato
				$this->showLoginPage ( $vd );
			}
		}
		// richiamo la vista
		require basename ( __DIR__ ) . '/../view/master.php';
	}
	
	/**
	 * Verifica se lo user sia correttamente autenticato
	 * @return boolean true se lo user era gia' autenticato, false altrimenti
	 */
	protected function loggedIn() {
		return isset ( $_SESSION ) && array_key_exists ( self::user, $_SESSION );
	}
	/**
	 * Restituisce il ruolo in sessione se presente
	 * @return string|NULL
	 */
	protected function userRole() {
		if (isset ( $_SESSION ) && array_key_exists ( self::user, $_SESSION )) {
			return $_SESSION [self::role];
		}
		return null;
	}
	/**
	 * Restituisce l'ID dello user in sessione se presente
	 * @return integer|NULL
	 */
	protected function loggedUserId() {
		if (isset ( $_SESSION ) && array_key_exists ( self::user, $_SESSION )) {
			return $_SESSION [self::user];
		}
		return null;
	}
	/**
	 * Imposta la vista master, include gli stili ed imposta il body class e visualizza la pagina di login 
	 * @param ViewDescriptor $vd        	
	 */
	protected function showLoginPage($vd) {
		$vd->addStyle ( '../assets/global/css/login.css' );
		$vd->addStyle ( '../assets/global/css/components.css' );
		$vd->addStyle ( '../assets/global/css/plugins.css' );
		$vd->addStyle ( '../assets/global/css/layout.css' );
		$vd->addStyle ( '../assets/global/css/themes/default.css' );
		$vd->addStyle ( '../assets/global/css/custom.css' );
		$vd->setBodyClass ( 'login' );
		$vd->setTitolo ( "Biblioteca - login" );
		$vd->setLogoFile ( basename ( __DIR__ ) . '/../view/login/logo.php' );
		$vd->setContentFile ( basename ( __DIR__ ) . '/../view/login/content.php' );
	}
	
	/**
	 * Mostra la pagina dell'utente
	 * @param ViewDescriptor $vd
	 * @param utente $utente
	 */
	protected function showHomeUtente($vd, utente $utente) {
		if (isset ( $utente )) {
			$vd->setTitolo ( "Liber - Pannello Utente " . $utente->getNome () . " " . $utente->getCognome () );
		} else {
			$vd->setTitolo ( "Liber - Pannello Utente " );
		}
		$vd->setBodyClass ( "page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-sidebar-closed-hide-logo" );
		$vd->setLogoFile ( basename ( __DIR__ ) . '/../view/utente/logo.php' );
		$vd->setLeftBarFile ( basename ( __DIR__ ) . '/../view/utente/sidebar.php' );
		$vd->setContentFile ( basename ( __DIR__ ) . '/../view/utente/content.php' );
	}
	/**
	 * Mostra la pagina dell'operatore
	 * @param ViewDescriptor $vd
	 * @param operatore $utente
	 */
	protected function showHomeOperatore($vd, operatore $utente) {
		if (isset ( $utente )) {
			$vd->setTitolo ( "Liber - Pannello Operatore " . $utente->getNome () . " " . $utente->getCognome () );
		} else {
			$vd->setTitolo ( "Liber - Pannello Operatore " );
		}
		$vd->setBodyClass ( "page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-sidebar-closed-hide-logo" );
		$vd->setLogoFile ( basename ( __DIR__ ) . '/../view/operatore/logo.php' );
		$vd->setLeftBarFile ( basename ( __DIR__ ) . '/../view/operatore/sidebar.php' );
		$vd->setContentFile ( basename ( __DIR__ ) . '/../view/operatore/content.php' );
	}
	/**
	 * Procedura di login utente o operatore
	 * @param ViewDescriptor $vd
	 * @param string $username
	 * @param string $password
	 */
	protected function login($vd, $username, $password) {
		//verifichiamo se è un utente
		$utente = utenteFactory::instance ()->authenticateUtente ( $username, $password );
		if (isset ( $utente ) && $utente->getAbilitato ()) {
			$_SESSION [self::user] = $utente->getId ();
			$_SESSION [self::role] = 'utente';
			$this->showHomeUtente ( $vd, $utente );
		} else {
			//non è un utente proviamo a verificare che sia un operatore
			$operatore = OperatoreFactory::instance ()->authenticateOperatore ( $username, $password );
			if (isset ( $operatore ) && $operatore->getAbilitato ()) {
				
				$_SESSION [self::user] = $operatore->getId ();
				$_SESSION [self::role] = 'operatore';
				$this->showHomeOperatore ( $vd, $operatore );
			} else {
				//non è nè utente nè operatore
				$vd->setMessaggioErrore ( "Utente sconosciuto o password errata" );
				$this->showLoginPage ( $vd );
			}
		}
	}
	
	/**
	 * Procedura di logout
	 * @param ViewDescriptor $vd
	 */
	protected function logout($vd) {
		// reset array $_SESSION
		$_SESSION = array ();
		// termino la validita' del cookie di sessione
		if (session_id () != '' || isset ( $_COOKIE [session_name ()] )) {
			// imposto il termine di validita' al mese scorso
			setcookie ( session_name (), '', time () -(60*60*24*30), '/' );
		}
		// distruggo il file di sessione
		session_destroy ();
		$this->showLoginPage ( $vd );
	}
	
	/**
	 * Aggiorno la password di un utente o un operatore
	 * @param User $user
	 * @param array $request
	 * @param array $msg
	 */
	protected function aggiornaPassword($idUser, &$request, &$msg) {
		//Inizializzo il numero di righe coinvolte nell'operazione
		$rows = -1;
		if (isset ( $request ['oldpassword'] ) && isset ( $request ['newpassword1'] ) && isset ( $request ['newpassword2'] )) {
			if ($request ['newpassword1'] == $request ['newpassword2']) {
				$oldPassword = $request ['oldpassword'];
				$newPassword = $request ['newpassword2'];
				switch ($this->userRole ()) {
					case 'utente' :
						$rows = utenteFactory::instance ()->changePassword ( $idUser, $oldPassword, $newPassword );
						break;
					case 'operatore' :
						$rows = OperatoreFactory::instance ()->changePassword ( $idUser, $oldPassword, $newPassword );
						break;
				}
			} else {
				$msg [] = '<li>Le due password non coincidono</li>';
			}
		}
		if ($rows == -1) {
			$msg [] = '<li>Salvataggio non riuscito</li>';
		}
	}
	/**
	 * 
	 * @param string $user
	 * @param array $request
	 * @param array $msg
	 * @return number|NULL
	 */
	protected function aggiornaDatiUtente($user, &$request, &$msg) {
		if (empty ( $request ['email'] )) {
			$msg [] = '<li>L\'indirizzo email specificato non &egrave; corretto</li>';
		}
		if (empty ( $request ['nome'] )) {
			$msg [] = '<li>Il nome specificato non &egrave; corretto</li>';
		}
		if (empty ( $request ['cognome'] )) {
			$msg [] = '<li>Il cognome specificato non &egrave; corretto</li>';
		}
		
		if (count ( $msg ) > 0) {
			
			return 0;
		}
		
		switch ($this->userRole ()) {
			case 'utente' :
				if (empty ( $request ['cellulare'] )) {
					$msg [] = '<li>L\'indirizzo specificato non &egrave; corretto</li>';
					return 0;
				}
				if (empty ( $request ['indirizzo'] )) {
					$msg [] = '<li>L\'indirizzo specificato non &egrave; corretto</li>';
					return 0;
				}
				$rows = utenteFactory::instance ()->changeUtenteData ( $user->getId (), $request ['nome'], $request ['cognome'], $request ['email'], $request ['cellulare'], $request ['indirizzo'] );
				return $rows;
				break;
			case 'operatore' :
				$rows = OperatoreFactory::instance ()->changeOperatoreData ( $user->getId (), $request ['nome'], $request ['cognome'], $request ['email'], $request ['cellulare'] );
				return $rows;
				break;
		}
		return 0;
	}
	
	/**
	 * Crea un messaggio di feedback per l'utente
	 * @param array $msg
	 * @param ViewDescriptor $vd
	 * @param string $okMsg
	 *        	il messaggio da mostrare nel caso non ci siano errori
	 */
	protected function creaFeedbackUtente(&$msg, $vd, $okMsg) {
		if (count ( $msg ) > 0) {
			// L'array dei messaggi d'errore non è vuoto
			$error = "Si sono verificati i seguenti errori \n<ul>\n";
			foreach ( $msg as $m ) {
				$error = $error . $m . "\n";
			}
			// Imposto il messaggio d'errore
			$vd->setMessaggioErrore ( $error );
		} else {
			// Non ci sono messaggi di errore
			$vd->setMessaggioConferma ( $okMsg );
		}
	}
	
	/**
	 *
	 * @return utente|operatore|NULL
	 */
	protected function loggedUserData() {
		if (! isset ( $currentUser )) {
			if (isset ( $_SESSION ) && array_key_exists ( self::user, $_SESSION )) {
				switch ($this->userRole ()) {
					case 'utente' :
						$idUser = $_SESSION [BaseController::user];
						$currentUser = utenteFactory::instance ()->getUtenteById ( $idUser );
						break;
					case 'operatore' :
						$idUser = $_SESSION [BaseController::user];
						$currentUser = OperatoreFactory::instance ()->getOperatoreById ( $idUser );
						break;
				}
			} else {
				return null;
			}
		}
		return $currentUser;
	}
}

?>
