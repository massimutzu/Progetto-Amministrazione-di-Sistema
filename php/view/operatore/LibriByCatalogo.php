<?php

$json = array();
foreach($libri as $libro){
	$element = array();
	$element['id'] = $libro->getId();
	$element['codice'] = $libro->getcodice_inventario();
	$element['noleggiabile'] = $libro->getnoleggiabile();
	$element['note'] = $libro->getnote();
	$element['idCatalogo'] = $libro->getCatalogo_fk();
    $json[]= $element;    
}
echo json_encode($json);
?>
