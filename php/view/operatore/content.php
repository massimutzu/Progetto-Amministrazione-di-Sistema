
				
<?php
switch ($vd->getSottoPagina ()) {	
	case 'LibriByCatalogo' :
		include_once 'LibriByCatalogo.php';
		break;
	case 'catalogo' :
		include_once 'catalogo.php';
		break;
	case 'utenti' :
		include_once 'utenti.php';
		break;
	case 'operatori' :
		include_once 'operatori.php';
		break;
	case 'gestioneNoleggi' :
		include_once 'gestioneNoleggi.php';
		break;
	case 'nuovoNoleggio' :
		include_once 'nuovoNoleggio.php';
		break;
	case 'scadenzeNoleggi' :
		include_once 'scadenzeNoleggi.php';
		break;
	case 'suggestTitolo' :
	case 'suggestCognome' :
	case 'suggestAutore' :
		include_once 'suggestGenerico.php';
		break;
	case 'searchCatalogo' :
		include_once 'searchCatalogo.php';
		break;
	case 'searchNoleggi' :
		include_once 'searchNoleggi.php';
		break;
	case 'searchUtenti' :
		include_once 'searchUtenti.php';
		break;
	case 'searchOperatori' :
		include_once 'searchOperatori.php';
		break;
	case 'operationResponse' :
		include_once 'operationResponse.php';
		break;
	default :
		?>
<div class="page-content-wrapper">
	<div class="page-content" id="content">
		<p>
		            Benvenuto, <?=  $this->loggedUserData()->getNome()?>
		        </p>
	</div>
</div>

<?php break;}?>
