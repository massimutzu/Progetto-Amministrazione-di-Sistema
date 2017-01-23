<?php

$json = array();
foreach($operatori as $operatore){
	$element = array();
	$element['id'] = $operatore->getId();
	$element['Nome'] = $operatore->getNome();
	$element['Cognome'] = $operatore->getCognome();
	$element['Username'] = $operatore->getUsername();
	$element['Email'] =$operatore->getEmail();
	$element['Cellulare'] =$operatore->getCellulare();
	$element['Abilitato'] =$operatore->getAbilitato();
	$element['Admin'] =$operatore->getIsAdmin();	
    $json[]= $element;    
}
echo json_encode($json);
?>
