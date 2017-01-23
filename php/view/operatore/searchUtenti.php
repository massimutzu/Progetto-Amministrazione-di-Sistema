<?php

$json = array();
foreach($utenti as $utente){
	$element = array();
	$element['id'] = $utente->getId();
	$element['Nome'] = $utente->getNome();
	$element['Cognome'] = $utente->getCognome();
	$element['Username'] = $utente->getUsername();
	$element['Email'] =$utente->getEmail();
	$element['Cellulare'] =$utente->getCellulare();
	$element['Abilitato'] =$utente->getAbilitato();
	$element['Indirizzo'] =$utente->getIndirizzo();
	$element['Noleggiati'] =$utente->getNoleggiAperti();
    $json[]= $element;    
}
echo json_encode($json);
?>
