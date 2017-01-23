<?php
include_once 'ViewDescriptor.php';
include_once basename ( __DIR__ ) . '/../Settings.php';

if (! $vd->isJson ()) {
	?>
<!DOCTYPE html>
<!-- 
         pagina master, contiene tutto il layout della applicazione 
         le varie pagine vengono caricate a "pezzi" a seconda della zona
         del layout:
         - logo (header)
         - menu (i tab)
         - leftBar (sidebar sinistra)
         - content (la parte centrale con il contenuto)
         - rightBar (sidebar destra)
         - footer (footer)

          Queste informazioni sono manentute in una struttura dati, chiamata ViewDescriptor
          la classe contiene anche le stringhe per i messaggi di feedback per 
          l'utente (errori e conferme delle operazioni)
    -->
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?= $vd->getTitolo() ?></title>
<base href="<?= Settings::getApplicationPath() ?>php/" />
<meta name="keywords" content="AMM esami docente" />
<meta name="description"
	content="Una pagina per gestire le funzioni di libreria" />
<link rel="shortcut icon" type="image/x-icon"
	href="../images/favicon.ico" />
<link
	href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=all"
	rel="stylesheet" type="text/css">
<link
	href="../assets/global/plugins/font-awesome/css/font-awesome.min.css"
	rel="stylesheet" type="text/css">
<link
	href="../assets/global/plugins/simple-line-icons/simple-line-icons.min.css"
	rel="stylesheet" type="text/css">
<link href="../assets/global/plugins/jquery-ui/jquery-ui.min.css"
	rel="stylesheet" type="text/css">
<link href="../assets/global/plugins/bootstrap/css/bootstrap.min.css"
	rel="stylesheet" type="text/css">
<link href="../assets/global/plugins/uniform/css/uniform.default.css"
	rel="stylesheet" type="text/css">
<link href="../assets/global/css/components.css" id="style_components"
	rel="stylesheet" type="text/css">
<link href="../assets/global/css/components-rounded.css"
	id="style_components" rel="stylesheet" type="text/css">
<link
	href="../assets/global/plugins/datatables/media/css/jquery.dataTables.min.css"
	rel="stylesheet" type="text/css">

<link href="../assets/global/css/plugins.css" rel="stylesheet"
	type="text/css">
<link href="../assets/layout/css/layout.css" rel="stylesheet"
	type="text/css">

<link href="../assets/layout/css/custom.css" rel="stylesheet"
	type="text/css">
<link href="../assets/layout/css/themes/grey.css" rel="stylesheet"
	type="text/css" id="style_color">
<?php

foreach ( $vd->getStyles () as $script ) {
		?>
<link href="<?= $script ?>" rel="stylesheet" type="text/css"
	media="screen" />
<?php } ?>


<style type="text/css">
.ui-widget-content {
	/*  escamotage per rendere visibile l'autosuggest anche in caso di dialog di bootstrap , altrimenti starebbe dietro al modal-backdrop */
	z-index: 30000;
}
</style>
</head>
<body class="<?=$vd->getBodyClass ()?>">


	<?php
	$logo = $vd->getLogoFile ();
	require "$logo";
	?>
	<div class="clearfix"></div>


	<?php
	if ($vd->getMenuFile () != null) {
		$menu = $vd->getMenuFile ();
		require "$menu";
	}
	?>

	<div class="container">
		<div class="page-container">
			<?php
			if ($vd->getLeftBarFile () != null) {
		$left = $vd->getLeftBarFile ();
		require "$left";
	}
	?>




			<?php if ($vd->getMessaggioErrore () != null || $vd->getMessaggioConferma () != null) {?>
			<div class="modal fade" id="ResultFeedback" tabindex="-1"
				role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"
								aria-hidden="true"></button>
							<h4 class="modal-title">Risultato</h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-12">
									<?php 	if ($vd->getMessaggioErrore () != null) {?>
									<div class="alert alert-danger" id="feedback_error">
										<?=$vd->getMessaggioErrore ();?>
									</div>
									<?php } else {?>
									<div class="alert alert-success" id="feedback_success">
										<?=$vd->getMessaggioConferma ();?>
									</div>
									<?php }		?>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn default" data-dismiss="modal">Chiudi</button>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>

			<?php
			$content = $vd->getContentFile ();
			require "$content";
			?>
		</div>
	</div>

	<div class="clear"></div>

	<div class="container">
		<div class="page-container" style="text-align:center">
		
				<div class="page-footer">
					<div class="row">
						<div class="cols-md-12">Mutzu Martis Massimiliano Matr. 41615</div>
					</div>
				</div>
			
		</div>
	</div>

	<script src="../assets/global/plugins/jquery.min.js"
		type="text/javascript"></script>
	<script src="../assets/global/plugins/jquery-migrate.min.js"
		type="text/javascript"></script>
	<script src="../assets/global/plugins/bootstrap/js/bootstrap.min.js"
		type="text/javascript"></script>
	<script src="../assets/global/plugins/jquery.blockui.min.js"
		type="text/javascript"></script>
	<script src="../assets/global/plugins/uniform/jquery.uniform.min.js"
		type="text/javascript"></script>
	<script src="../assets/global/plugins/jquery.cokie.min.js"
		type="text/javascript"></script>
	<!-- END CORE PLUGINS -->
	<!-- BEGIN PAGE LEVEL PLUGINS -->
	<script
		src="../assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
	<script src="../assets/global/plugins/jquery-ui/jquery-ui.min.js"></script>
	<script src="../assets/global/plugins/jquery.ui.autocomplete.js"></script>
	<script src="../assets/global/plugins/jquery.jeditable.js"></script>
	<script src="../assets/global/plugins/datatables/all.min.js"></script>
	<script src="../assets/global/scripts/ui-alert-dialog-api.js"></script>

	<!-- END PAGE LEVEL PLUGINS -->
	<!-- BEGIN PAGE LEVEL SCRIPTS -->
	<script src="../assets/global/scripts/metronic.js"
		type="text/javascript"></script>
	<script src="../assets/layout/scripts/layout.js" type="text/javascript"></script>
	<script src="../assets/layout/scripts/demo.js" type="text/javascript"></script>

	<?php
	foreach ( $vd->getScripts () as $script ) {
		?>
	<script type="text/javascript" src="<?= $script ?>"></script>
	<?php
	}
	?>

	<!-- ALERT SCRIPT -->
	<?php if ($vd->getMessaggioErrore () != null || $vd->getMessaggioConferma () != null) {?>
	<script type="text/javascript">
		$(function (){
				$("#ResultFeedback").modal('show');
			}
		);
	 </script>
	<?php } 	?>


</body>
</html>
<?php
} else {

	header ( 'Cache-Control: no-cache, must-revalidate' );
	header ( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
	header ( 'Content-type: application/json' );
	$content = $vd->getContentFile ();
	require "$content";
}
?>





