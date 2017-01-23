
				
<?php
switch ($vd->getSottoPagina ()) {
	case 'catalogo' :
		include_once 'catalogo.php';
		break;
	case 'noleggi' :
		include_once 'noleggi.php';
		break;
	case 'suggestTitolo' :
		include_once 'suggestTitolo.php';
		break;
	case 'suggestAutore' :		
		include_once 'suggestAutore.php';
		break;
		case 'searchCatalogo' :
			include_once 'searchCatalogo.php';
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
