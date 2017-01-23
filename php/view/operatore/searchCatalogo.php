<?php

$json = array();
foreach($cataloghi as $catalogo){
	$element = array();
	$element['id'] = $catalogo->getId();
	$element['Anno'] = $catalogo->getAnno();
	$element['Autore'] = $catalogo->getAutore();
	$element['Genere'] = $catalogo->getGenere();
	$element['Isbn'] =$catalogo->getIsbn();
	$element['Titolo'] =$catalogo->getTitolo();
	$element['Totale'] =$catalogo->getTotaleLibri();
	$element['Noleggiati'] =$catalogo->getNoleggiati();
	$element['NonNoleggiabili'] = $catalogo->getNonNoleggiabili();
	$element['UrlImmagine'] = $catalogo->getUrlImmagine();
    $json[]= $element;    
}
echo json_encode($json);
?>
