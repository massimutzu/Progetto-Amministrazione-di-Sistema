<?php
include_once 'BaseController.php';

/**
 * 
 * @author Massimiliano Mutzu Martis
 * 
 */
class UtenteController extends BaseController {
	private $currentUser;
	
	/**
	 * Costruttore
	 */
	public function __construct() {
		$currentUser = null;
		parent::__construct ();
	}
	
	/**
	 * Metodo per gestire l'input dell'utente.
	 * @param type $request
	 */
	public function handleInput(&$request) {
		// creo il descrittore della vista
		$vd = new ViewDescriptor ();
		// imposto la pagina
		$vd->setPagina ( $request ['page'] );	
		// gestione dei comandi		
		if (! $this->loggedIn ()) {
			// se l'utente non è autenticato, rimando alla pagina di login
			$this->showLoginPage ( $vd );
		} else {
			if ($this->userRole () == 'utente') {
				// utente autenticato
				$user = $this->loggedUserData ();
				if (isset ( $request ["subpage"] )) {
					switch ($request ["subpage"]) {
						// pagina di gestione dei dati di catalogo
						case 'catalogo' :
							$vd->setSottoPagina ( 'catalogo' );
							$vd->addScript ( '../js/utente_catalogo.js' );
							break;
						// pagina di gestione dei dati di noleggio
						case 'noleggi' :							
							$vd->setSottoPagina ( 'noleggi' );
							$vd->addScript ( '../js/utente_noleggi.js' );
							$elenco_noleggi= NoleggioFactory::instance()->searchNoleggioByUtente($user->getId(), null);
							break;
						default :							
							$vd->setSottoPagina ( 'home' );
							break;
					}
				}				
				// gestione dei comandi inviati dall'utente
				if (isset ( $request ["cmd"] )) {
					// abbiamo ricevuto un comando
					switch ($request ["cmd"]) {
						// logout
						case 'logout' :
							$this->logout ( $vd );
							break;
						// ricerca nl catalogo
						case 'searchCatalogo' :
							$cataloghi = $this->searchCatalogo ( $request );
							$vd->toggleJson ();
							$vd->setSottoPagina ( 'searchCatalogo' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/utente/content.php' );
							break;
						// autocomplete del titolo
						case 'titleComplete' :
							$testo = urldecode ( $request ["Text"] );
							$titoli = CatalogoFactory::instance ()->AutoCompleteTitolo ( $testo, 10 );
							$vd->toggleJson ();
							$vd->setSottoPagina ( 'suggestTitolo' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/utente/content.php' );
							break;
						// autocomplete dell'autore						
						case 'autoreComplete' :
							$testo = urldecode ( $request ["Text"] );
							$autori = CatalogoFactory::instance ()->AutoCompleteAutore ( $testo, 10 );
							$vd->toggleJson ();
							$vd->setSottoPagina ( 'suggestAutore' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/utente/content.php' );
							break;
						// modifica password
						case 'password' :
							$msg = array ();
							$this->aggiornaPassword ( $user->getId (), $request, $msg );
							$this->creaFeedbackUtente ( $msg, $vd, "Password Aggiornata" );
							$this->showHomeUtente ( $vd, $user );
							break;
						// modifica dati utente
						case 'mydata' :
							$msg = array ();
							$this->aggiornaDatiUtente ( $user, $request, $msg );
							$this->creaFeedbackUtente ( $msg, $vd, "Dati aggiornati" );
							$this->showHomeUtente ( $vd, $user );
							break;
						default :
							$this->showLoginPage ( $vd );
					}
				} else {
					// nessun comando
					$this->showHomeUtente ( $vd, $user );
				}
			}
		}
		
		// includo la vista
		require basename ( __DIR__ ) . '/../view/master.php';
	}
	/**
	 * 
	 * @param array $request
	 * @return NULL|catalogo[]
	 */
	protected function searchCatalogo(&$request) {
		$titolo = $request ["titolo"];
		$autore = $request ["autore"];
		$cataloghi = CatalogoFactory::instance ()->searchCatalog ( $titolo, $autore, null );
		
		return $cataloghi;
	}
}

?>
