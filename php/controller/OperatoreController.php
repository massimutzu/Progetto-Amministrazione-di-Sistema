<?php
include_once 'BaseController.php';

/**
 *
 * @author Massimiliano Mutzu Martis
 *        
 */
class OperatoreController extends BaseController {
	private $currentUser;
	
	/**
	 * Costruttore
	 */
	public function __construct() {
		$currentUser = null;
		parent::__construct ();
	}
	
	/**
	 * Metodo per gestire le funzioni dell'operatore
	 *
	 * @param array $request        	
	 */
	public function handleInput(&$request) {
		// creo il descrittore della vista
		$vd = new ViewDescriptor ();
		// imposto la pagina
		$vd->setPagina ( $request ['page'] );
		if (! $this->loggedIn ()) {
			// utente non autenticato, rimando alla pagina di login
			$this->showLoginPage ( $vd );
		} else {
			if ($this->userRole () == 'operatore') {
				// operatore autenticato
				$user = $this->loggedUserData ();
				if (isset ( $request ["subpage"] )) {
					switch ($request ["subpage"]) {
						case 'utenti' :
							// gestione degli utenti
							$vd->setSottoPagina ( 'utenti' );
							$vd->addScript ( '../js/operatore_utenti.js' );
							break;
						case 'operatori' :
							if ($this->operatoreIsAdmin ()) {
								// gestione degli operatori
								$vd->setSottoPagina ( 'operatori' );
								$vd->addScript ( '../js/operatore_operatori.js' );
							} else {
								$vd->setSottoPagina ( 'home' );
							}
							break;
						case 'catalogo' :
							// gestione del catalogo
							$vd->setSottoPagina ( 'catalogo' );
							$vd->addScript ( '../js/operatore_catalogo.js' );
							break;
						case 'gestioneNoleggi' :
							$vd->setSottoPagina ( 'gestioneNoleggi' );
							$vd->addScript ( '../js/operatore_gestioneNoleggi.js' );
							break;
						case 'nuovoNoleggio' :
							// pagina per creare un nuovo noleggio
							$vd->setSottoPagina ( 'nuovoNoleggio' );
							$vd->addScript ( '../js/operatore_nuovoNoleggio.js' );
							break;
						case 'scadenzeNoleggi' :
							// lista noleggi in scadenza fra 4 giorni
							$elenco_noleggi = NoleggioFactory::instance ()->getExpiringNoleggio ( 4 );
							$vd->setSottoPagina ( 'scadenzeNoleggi' );
							$vd->addScript ( '../js/operatore_ScadenzeNoleggi.js' );
							break;
						default :
							$vd->setSottoPagina ( 'home' );
							break;
					}
				}
				// gestione dei comandi inviati dall'utente
				if (isset ( $request ["cmd"] )) {
					$newValue="";
					// abbiamo ricevuto un comando
					switch ($request ["cmd"]) {
						// logout
						case 'logout' :
							$this->logout ( $vd );
							break;
						case 'aggiornaNoleggiabile' :
							$id = $request ["IdLibro"];
							$nol = $request ["nol"];
							$result = LibroFactory::instance ()->updateNoleggiabile ( $id, $nol );
							if ($result >= 0) {
								$status = true;
								$msg = " Libro Aggiornato";
							} else {
								$status = false;
								$msg = "Aggiornamento Fallito";
							}
							$vd->toggleJson ();
							$vd->setSottoPagina ( 'operationResponse' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/operatore/content.php' );
							break;
						case 'aggiornaNota' :
							$id = $request ["IdLibro"];
							$nota = $request ["nota"];
							$result = LibroFactory::instance ()->updateNote ( $id, $nota );
							if ($result >= 0) {
								$status = true;
								$msg = " Libro Aggiornato";
							} else {
								$status = false;
								$msg = "Aggiornamento Fallito";
							}
							$vd->toggleJson ();
							$vd->setSottoPagina ( 'operationResponse' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/operatore/content.php' );
							break;
						case 'deleteLibro' :
							$id = $request ["IdLibro"];
							$result = LibroFactory::instance ()->deleteLibro ( $id );
							if ($result >= 0) {
								$status = true;
								$msg = " Libro Eliminato";
							} else {
								$status = false;
								$msg = "Cancellazione Fallita";
							}
							$vd->toggleJson ();
							$vd->setSottoPagina ( 'operationResponse' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/operatore/content.php' );
							break;
						case 'nuovoLibro' :
							$id = $request ["IdCatalogo"];
							$libro = new libro ();
							$libro->setCatalogo_fk ( $id );
							$libro->setcodice_inventario ( '' );
							$libro->setnoleggiabile ( true );
							$libro->setnote ( '' );
							$result = LibroFactory::instance ()->addLibroToCatalogo ( $libro );
							if ($result >= 0) {
								$status = true;
								$msg = " Libro inserito";
							} else {
								$status = false;
								$msg = "Inserimento Fallito";
							}
							$vd->toggleJson ();
							$vd->setSottoPagina ( 'operationResponse' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/operatore/content.php' );
							break;
						case 'LibriByCatalogo' :
							$id = $request ["IdCatalogo"];
							$libri = LibroFactory::instance ()->searchLibriByParam ( $id, null, null );
							$vd->toggleJson ();
							$vd->setSottoPagina ( 'LibriByCatalogo' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/operatore/content.php' );
							
							break;
						case 'updateCatalogo' :
							$catalogo = new catalogo ();
							$catalogo->setId ( $request ["id"] );
							$catalogo->setAnno ( $request ["anno"] );
							$catalogo->setAutore ( $request ["autore"] );
							$catalogo->setTitolo ( $request ["titolo"] );
							$catalogo->setIsbn ( $request ["isbn"] );
							$catalogo->setUrlImmagine ( $request ["imgurl"] );
							$catalogo->setGenere ( $request ["genere"] );
							$result = CatalogoFactory::instance ()->updateCatalogo ( $catalogo );
							if ($result >= 0) {
								$status = true;
								$msg = " Catalogo modificato";
							} else {
								$status = false;
								$msg = "Modifica fallita";
							}
							$vd->toggleJson ();
							$vd->setSottoPagina ( 'operationResponse' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/operatore/content.php' );
							break;
						case 'deleteCatalogo' :
							$IdCatalogo = $request ["IdCatalogo"];
							$result = CatalogoFactory::instance ()->deleteCatalogo($IdCatalogo);
							if ($result > 0) {
								$status = true;
								$msg = " Catalogo eliminato";
							} else {
								$status = false;
								$msg = "Cancellazione fallita, verificare che non ci siano libri ancora associati e riprovare";
							}
							$vd->toggleJson ();
							$vd->setSottoPagina ( 'operationResponse' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/operatore/content.php' );
							break;
						case 'nuovoCatalogo' :
							$catalogo = new catalogo ();
							$catalogo->setAnno ( $request ["anno"] );
							$catalogo->setAutore ( $request ["autore"] );
							$catalogo->setTitolo ( $request ["titolo"] );
							$catalogo->setIsbn ( $request ["isbn"] );
							$catalogo->setUrlImmagine ( $request ["imgurl"] );
							$catalogo->setGenere ( $request ["genere"] );
							$result = CatalogoFactory::instance ()->createCatalogo ( $catalogo );
							if ($result > 0) {
								$status = true;
								$msg = " Catalogo inserito";
							} else {
								$status = false;
								$msg = "Inserimento fallito";
							}
							$vd->toggleJson ();
							$vd->setSottoPagina ( 'operationResponse' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/operatore/content.php' );
							break;
						
						case 'searchCatalogo' :
							$cataloghi = $this->searchCatalogo ( $request );
 							$vd->toggleJson ();
							$vd->setSottoPagina ( 'searchCatalogo' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/operatore/content.php' );
							break;
						case 'deleteUtente' :
							$id = $request ["IdUtente"];
							$result = utenteFactory::instance ()->deleteUtente ( $id );
							if ($result >= 0) {
								$status = true;
								$msg = " Utente eliminato";
							} else {
								$status = false;
								$msg = "Cancellazione fallita";
							}
							$vd->toggleJson ();
							$vd->setSottoPagina ( 'operationResponse' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/operatore/content.php' );
							break;
						case 'editUtente' :
							$id = $request ["IdUtente"];
							$nome = $request ["nome"];
							$cognome = $request ["cognome"];
							$email = $request ["email"];
							$indirizzo = $request ["indirizzo"];
							$cellulare = $request ["cellulare"];
							$result = utenteFactory::instance ()->changeUtenteData ( $id, $nome, $cognome, $email, $cellulare, $indirizzo );
							$password1 = $request ["password1"];
							$password2 = $request ["password2"];
							if ($result >= 0) {
								if (! empty ( $password1 ) && ! empty ( $password2 )) {
									
									if ($password1 == $password2) {
										$result = utenteFactory::instance ()->resetPassword ( $id, $password1 );
										if ($result >= 0) {
											$status = true;
											$msg = " Utente e Password Aggiornata";
										} else {
											$status = false;
											$msg = "Aggiornamento fallito";
										}
									} else {
										$status = false;
										$msg = "Le password non combaciano";
									}
								} else {
									$status = true;
									$msg = " Utente Aggiornato";
								}
							} else {
								$status = false;
								$msg = "Aggiornamento fallito";
							}
							$vd->toggleJson ();
							$vd->setSottoPagina ( 'operationResponse' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/operatore/content.php' );
							
							break;
						case 'toggleUtenteAbilitato' :
							$id = $request ["IdUtente"];
							$oldUtente = utenteFactory::instance ()->getUtenteById ( $id );
							$oldValue = $oldUtente->getAbilitato ();
							if ($oldValue == false) {
								$result = utenteFactory::instance ()->enableUtente ( $id );
								$newValue = true;
							} else {
								$result = utenteFactory::instance ()->disableUtente ( $id );
								$newValue = false;
							}
							
							if ($result >= 0) {
								$status = true;
								$msg = "Utente Abilitato";
							} else {
								$status = false;
								$msg = "Modifica fallita";
							}
							$vd->toggleJson ();
							$vd->setSottoPagina ( 'operationResponse' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/operatore/content.php' );
							
							break;
						case 'searchUtenti' :
							$nome = $request ["nome"];
							$cognome = $request ["cognome"];
							$indirizzo = $request ["indirizzo"];
							$utenti = utenteFactory::instance ()->searchUtenteByParam ( $nome, $cognome, $indirizzo );
							$vd->toggleJson ();
							$vd->setSottoPagina ( 'searchUtenti' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/operatore/content.php' );
							break;
						case 'addUtente' :
							
							$username = $request ["username"];
							$nome = $request ["nome"];
							$cognome = $request ["cognome"];
							$email = $request ["email"];
							$indirizzo = $request ["indirizzo"];
							$cellulare = $request ["cellulare"];
							$password = $request ["password1"];
							$password2 = $request ["password2"];
							if ($password == $password2) {
								$result = utenteFactory::instance ()->createNewUtente ( $username, $nome, $cognome, $email, $indirizzo, $cellulare, 1, $password );
								if ($result >= 0) {
									$status = true;
									$msg = "Utente inserito";
								} else {
									$status = false;
									$msg = "Inserimento fallito";
								}
							} else {
								$status = false;
								$msg = "Le password non combaciano";
							}
							$vd->toggleJson ();
							$vd->setSottoPagina ( 'operationResponse' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/operatore/content.php' );
							break;
						
						case 'deleteOperatore' :
							if ($this->operatoreIsAdmin ()) {
								$id = $request ["IdOperatore"];
								$result = OperatoreFactory::instance ()->deleteOperatore ( $id );
								if ($result >= 0) {
									$status = true;
									$msg = " Operatore eliminato";
								} else {
									$status = false;
									$msg = "Cancellazione fallita";
								}
							} else {
								$status = false;
								$msg = "Operazione non consentita";
							}
							$vd->toggleJson ();
							$vd->setSottoPagina ( 'operationResponse' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/operatore/content.php' );
							break;
						case 'editOperatore' :
							if ($this->operatoreIsAdmin ()) {
								$id = $request ["IdOperatore"];
								
								$nome = $request ["nome"];
								$cognome = $request ["cognome"];
								$email = $request ["email"];
								$indirizzo = $request ["indirizzo"];
								$cellulare = $request ["cellulare"];
								$result = OperatoreFactory::instance ()->changeOperatoreData ( $id, $nome, $cognome, $email, $cellulare );
								$password1 = $request ["password1"];
								$password2 = $request ["password2"];
								if ($result >= 0) {
									if (! empty ( $password1 ) && ! empty ( $password2 )) {
										
										if ($password1 == $password2) {
											$result = OperatoreFactory::instance ()->resetPassword ( $id, $password1 );
											if ($result >= 0) {
												$status = true;
												$msg = " Operatore e Password Aggiornata";
											} else {
												$status = false;
												$msg = "Aggiornamento fallito";
											}
										} else {
											$status = false;
											$msg = "Le password non combaciano";
										}
									} else {
										$status = true;
										$msg = " Operatore Aggiornato";
									}
								} else {
									$status = false;
									$msg = "Aggiornamento fallito";
								}
							} else {
								$status = false;
								$msg = "Operazione non consentita";
							}
							$vd->toggleJson ();
							$vd->setSottoPagina ( 'operationResponse' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/operatore/content.php' );
							
							break;
						case 'toggleOperatoreAbilitato' :
							if ($this->operatoreIsAdmin ()) {
								$id = $request ["IdOperatore"];
								if ($id != $user->getId ()) {
									$oldOperatore = OperatoreFactory::instance ()->getOperatoreById ( $id );
									$oldValue = $oldOperatore->getAbilitato ();
									if ($oldValue == false) {
										$result = OperatoreFactory::instance ()->enableOperatore ( $id );
										$newValue = true;
									} else {
										$result = OperatoreFactory::instance ()->disableOperatore ( $id );
										$newValue = false;
									}
									if ($result >= 0) {
										$status = true;
										$msg = "Abilitazione Operatore Impostata";
									} else {
										$status = false;
										$msg = "Modifica fallita";
									}
								} else {
									$status = false;
									$msg = "Operazione non consentita";
								}
							} else {
								$status = false;
								$msg = "Operazione non consentita";
							}
							$vd->toggleJson ();
							$vd->setSottoPagina ( 'operationResponse' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/operatore/content.php' );
							
							break;
						case 'toggleOperatoreAdmin' :
							if ($this->operatoreIsAdmin ()) {
								$id = $request ["IdOperatore"];
								if ($id != $user->getId ()) {
									$oldOperatore = OperatoreFactory::instance ()->getOperatoreById ( $id );
									$oldValue = $oldOperatore->getIsAdmin ();
									if ($oldValue == false) {
										$result = OperatoreFactory::instance ()->enableOperatoreAdmin ( $id );
										$newValue = true;
									} else {
										$result = OperatoreFactory::instance ()->disableOperatoreAdmin ( $id );
										$newValue = false;
									}
									if ($result >= 0) {
										$status = true;
										$msg = "Amministrazione Operatori Impostata";
									} else {
										$status = false;
										$msg = "Modifica fallita";
									}
								} else {
									$status = false;
									$msg = "Operazione non consentita";
								}
							} else {
								$status = false;
								$msg = "Operazione non consentita";
							}
							$vd->toggleJson ();
							$vd->setSottoPagina ( 'operationResponse' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/operatore/content.php' );
							
							break;
						case 'searchOperatori' :
							if ($this->operatoreIsAdmin ()) {
								$nome = $request ["nome"];
								$cognome = $request ["cognome"];
								$operatori = OperatoreFactory::instance ()->searchOperatoriByParam ( $nome, $cognome );
								$vd->toggleJson ();
								$vd->setSottoPagina ( 'searchOperatori' );
								$vd->setContentFile ( basename ( __DIR__ ) . '/../view/operatore/content.php' );
							} else {
								$this->showLoginPage ( $vd );
							}
							break;
						case 'addOperatore' :
							if ($this->operatoreIsAdmin ()) {
								$username = $request ["username"];
								$nome = $request ["nome"];
								$cognome = $request ["cognome"];
								$email = $request ["email"];
								$cellulare = $request ["cellulare"];
								$password = $request ["password1"];
								$password2 = $request ["password2"];
								if ($password == $password2) {
									// di default l'operatore � inserito abilitato ma non con il profilo admin.. deve essere attivato manualmente
									$result = OperatoreFactory::instance ()->createNewOperatore ( $username, $nome, $cognome, $email, $cellulare, 1, $password, 0 );
									if ($result > 0) {
										$status = true;
										$msg = "Utente inserito";
									} else {
										$status = false;
										$msg = "Inserimento fallito";
									}
								} else {
									$status = false;
									$msg = "Le password non combaciano";
								}
							} else {
								$status = false;
								$msg = "Operazione non consentita";
							}
							$vd->toggleJson ();
							$vd->setSottoPagina ( 'operationResponse' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/operatore/content.php' );
							break;
						
						case 'searchCatalogo' :
							$cataloghi = $this->searchCatalogo ( $request );
							$vd->toggleJson ();
							$vd->setSottoPagina ( 'searchCatalogo' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/operatore/content.php' );
							break;
						
						case 'searchNoleggi' :
							$noleggi = $this->searchNoleggi ( $request );
							$vd->toggleJson ();
							$vd->setSottoPagina ( 'searchNoleggi' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/operatore/content.php' );
							break;
						case 'apriNoleggio':
							$idUtente = $request['idUtente'];
							$inventario = $request ["inventario"];							
							$libri=	LibroFactory::instance()->searchLibriByParam(null, $inventario, null);
							if (count( $libri)>0){
								$libro= $libri[0];
								if ($libro->getnoleggiabile())
								{
									if (!$libro->getNoleggiato())
									{
										$IdOperatore= $user->getId();
										$noleggio = new noleggio();
										$startRent= date("Y-m-j H:i:s");
										$noleggio->setStart_rent($startRent);
										$noleggio->setOperatore_startrent($IdOperatore);
										$noleggio->setIdUtente($idUtente);
										$noleggio->setLibroFk($libro->getId());											
										$result =NoleggioFactory::instance()->createNoleggio($noleggio);
										if ($result > 0) {
											$status = true;
											$msg = "Noleggio Creato Correttamente";
										} else {
											$status = false;
											$msg = "Creazione Noleggio fallita";
										}
									}
									else
									{
										$status = false;
										$msg = "Libro gia' noleggiato";
									}
								}
								else
								{
									$status = false;
									$msg = "Libro non noleggiabile";
								}
							}
							else 
							{
								$status = false;
								$msg = "Inventario non valido";
							}
							$vd->toggleJson ();
							$vd->setSottoPagina ( 'operationResponse' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/operatore/content.php' );
							break;
						case 'chiudiNoleggio' :
							$idNoleggio = $request ["IdNoleggio"];
							$IdOperatore= $user->getId();
							$noleggio = new noleggio();
							$endRent= date("Y-m-j H:i:s");
							$noleggio->setEnd_rent($endRent);
							$noleggio->setId($idNoleggio);
							$noleggio->setOperatore_endrent($IdOperatore);
							$result =NoleggioFactory::instance()->updateNoleggio($noleggio);
							if ($result >= 0) {
								$status = true;
								$msg = "Noleggio Chiuso Correttamente";
							} else {
								$status = false;
								$msg = "Modifica fallita";
							}							
							$vd->toggleJson ();
							$vd->setSottoPagina ( 'operationResponse' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/operatore/content.php' );
							break;
						case 'CognomeComplete' :
							$testo = urldecode ( $request ["Text"] );
							$lista = utenteFactory::instance ()->AutoCompleteCognome ( $testo, 10 );
							$vd->toggleJson ();
							$vd->setSottoPagina ( 'suggestCognome' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/operatore/content.php' );
							break;
						
						case 'titleComplete' :
							$testo = urldecode ( $request ["Text"] );
							$lista = CatalogoFactory::instance ()->AutoCompleteTitolo ( $testo, 10 );
							$vd->toggleJson ();
							$vd->setSottoPagina ( 'suggestTitolo' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/operatore/content.php' );
							break;
						
						case 'autoreComplete' :
							$testo = urldecode ( $request ["Text"] );
							$lista = CatalogoFactory::instance ()->AutoCompleteAutore ( $testo, 10 );
							$vd->toggleJson ();
							$vd->setSottoPagina ( 'suggestAutore' );
							$vd->setContentFile ( basename ( __DIR__ ) . '/../view/operatore/content.php' );
							break;
						case 'password' :
							$msg = array ();
							$this->aggiornaPassword ( $user->getId (), $request, $msg );
							$this->creaFeedbackUtente ( $msg, $vd, "Password Aggiornata" );
							$this->showHomeOperatore ( $vd, $user );
							break;
						// cambio email
						case 'mydata' :
							$msg = array ();
							$this->aggiornaDatiUtente ( $user, $request, $msg );
							$this->creaFeedbackUtente ( $msg, $vd, "Dati aggiornati" );
							$this->showHomeOperatore ( $vd, $user );
							break;
						default :
							$this->showLoginPage ( $vd );
					}
				} else {
					// nessun comando
					$this->showHomeOperatore ( $vd, $user );
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
	protected function searchCatalogo($request) {
		$titolo = $request ["titolo"];
		$autore = $request ["autore"];
		$idCatalogo = $request ["id"];
		if (empty ( $titolo )) {
			$titolo = null;
		}
		if (empty ( $autore )) {
			$autore = null;
		}
		if (empty ( $idCatalogo )) {
			$idCatalogo = null;
		}
		$cataloghi = CatalogoFactory::instance ()->searchCatalog ( $titolo, $autore, $idCatalogo );
		
		return $cataloghi;
	}
	protected function searchNoleggi(&$request) {
		$titolo = $request ["titolo"];
		$autore = $request ["autore"];
		$inventario = $request ["inventario"];
		$nome = $request ["nome"];
		$cognome = $request ["cognome"];
		$status = $request ["status"];
		if (empty ( $titolo )) {
			$titolo = null;
		}
		if (empty ( $autore )) {
			$autore = null;
		}
		if (empty ( $nome )) {
			$nome = null;
		}
		if (empty ( $cognome )) {
			$cognome = null;
		}
		if (empty ( $inventario )) {
			$inventario = null;
		}
		$noleggi = NoleggioFactory::instance ()->searchNoleggio ( $inventario, $titolo, $autore, $nome, $cognome, $status );
		return $noleggi;
	}
	/**
	 * indica se l'operatore � admin
	 *
	 * @return boolean
	 */
	private function operatoreIsAdmin() {
		return $this->loggedUserData ()->getIsAdmin ();
	}
}

?>
