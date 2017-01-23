<?php

$json = array();
foreach($noleggi as $noleggio){
	$element = array();
	$element['id'] = $noleggio->getId();
	$element['Autore'] = $noleggio->getAutore();
	$element['Inventario'] = $noleggio->getCodiceInventario();
	$element['Cognome'] = $noleggio->getCognome();
	$element['EndRent'] =$noleggio->getEnd_rent();
	$element['GiorniNoleggio'] =$noleggio->getGiorniNoleggio();
	$element['IdUtente'] =$noleggio->getIdUtente();
	$element['Nome'] =$noleggio->getNome();
	$element['StartRent'] = $noleggio->getStart_rent();
	$element['Titolo'] = $noleggio->getTitolo();
    $json[]= $element;    
}
echo json_encode($json);
?>
