
<div class="page-content-wrapper">
	<div class="page-content" id="content">
		<div class="row">
			<div class="col-md-8">
				<form action="utente/catalogo" method="post" class="form-horizontal">
					<div class="form-body">
						<div class="form-group">
							<label class="col-md-3 control-label">Nome</label>
							<div class="col-md-8">
								<input type="hidden" name="cmd" value="searchCatalogo"> <input
									type="text" class="form-control input-circle"
									placeholder="Inserire Nome" name="nome" id="snome">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Cognome</label>
							<div class="col-md-8">
								<input type="text" class="form-control input-circle"
									placeholder="Inserire Cognome" name="cognome" id="scognome">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Indirizzo</label>
							<div class="col-md-8">
								<input type="text" class="form-control input-circle"
									placeholder="Inserire Indirizzo" name="indirizzo"
									id="sindirizzo">
							</div>
						</div>
					</div>
					<div class="form-actions">
						<div class="row">
							<div class="col-md-offset-3 col-md-9">
								<button onclick="SearchUtenti()" type="button"
									class="btn btn-circle blue">Cerca</button>
								<button type="button" class="btn btn-circle default"
									onclick="ResetUtentiParams()">Reset</button>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="col-md-2"></div>
		</div>
		<div class="row">
			<div class="cols-md-8">
				<table id="UserTable"></table>
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


<div class="modal fade" id="NewNoleggio" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true"></button>
				<h4 class="modal-title">Nuovo Noleggio</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
					<div class="alert alert-success">
					Nuovo noleggio dell'utente <span id="newRentUser"></span>
					</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label>Codice Inventario</label>
					</div>
					<div class="col-md-6">
						<input type="hidden" id="idUtente"> <input type="text"
							id="inventario" class="form-control">
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" onclick="createNewNoleggio()" class="btn blue">Crea</button>
				<button type="button" class="btn default" data-dismiss="modal">Chiudi</button>
			</div>
		</div>
	</div>
</div>



