
<div class="page-content-wrapper">
	<div class="page-content" id="content">
		<div class="row">
			<div class="col-md-8">
				<form action="utente/catalogo" method="post" class="form-horizontal">
					<div class="form-body">
						<div class="form-group">
							<label class="col-md-3 control-label">Codice Inventario</label>
							<div class="col-md-8">
								<input type="hidden" name="cmd" value="searchNoleggio"> <input
									type="text" class="form-control input-circle"
									placeholder="Inserire Codice Inventario" id="sinventario">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Titolo</label>
							<div class="col-md-8">
								<input type="text" class="form-control input-circle"
									placeholder="Inserire Titolo" id="stitolo">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Autore</label>
							<div class="col-md-8">
								<input type="text" class="form-control input-circle"
									placeholder="Inserire Autore" id="sautore">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Nome Utente</label>
							<div class="col-md-8">
								<input type="text" class="form-control input-circle"
									placeholder="Inserire Nome" id="snome">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Cognome Utente</label>
							<div class="col-md-8">
								<input type="text" class="form-control input-circle"
									placeholder="Inserire Cognome" id="scognome">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Stato Noleggio</label>
							<div class="col-md-8">
								<div class="btn-group" data-toggle="buttons" id="statoNoleggi">
									<label class="btn blue active"> <input id="StatusAll" type="radio" class="toggle" value="-1">Tutti</label> 
									<label class="btn blue"> <input id="StatusOpen" type="radio" class="toggle"  value="1">Aperti</label> 
									<label class="btn blue"> <input id="StatusClosed" type="radio" class="toggle"  value="0">Chiusi</label>
								</div>
							</div>
						</div>
					</div>
					<div class="form-actions">
						<div class="row">
							<div class="col-md-offset-3 col-md-9">
								<button onclick="SearchNoleggi()" type="button"
									class="btn btn-circle blue">Cerca</button>
								<button type="button" class="btn btn-circle default"
									onclick="ResetSearchParams()">Reset</button>								
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="col-md-2"></div>
		</div>
		<div class="row">
			<div class="cols-md-8">
				<table id="NoleggiTable"></table>

			</div>
		</div>

	</div>
</div>


<div class="modal fade" id="chiudiNoleggio" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true"></button>
				<h4 class="modal-title">Chiusura Noleggio</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-danger">
							Sei sicuro di voler chiudere il noleggio del libro "<span
								id="noleggioTitleToClose"></span>"?
						</div>
						<input id="closeIdNoleggio" type="hidden">
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" onclick="confirmCloseNoleggio()"
					class="btn blue">Chiudi Noleggio</button>
				<button type="button" class="btn default" data-dismiss="modal">Annulla</button>
			</div>
		</div>
	</div>
</div>



<div class="modal fade" id="feedback" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
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
						<div class="alert alert-danger" id="feedback_error"></div>
					</div>
					<div class="col-md-12">
						<div class="alert alert-success" id="feedback_success"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn default" data-dismiss="modal">Chiudi</button>
			</div>
		</div>
	</div>
</div>



