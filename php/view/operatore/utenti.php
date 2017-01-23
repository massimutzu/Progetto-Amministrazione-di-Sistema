
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
								<button type="button" class="btn btn-circle green"
									onclick="$('#NewUtente').modal('show');">
									Nuovo <i class="icon-plus"></i>
								</button>
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

<div class="modal fade" id="NewUtente" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true"></button>
				<h4 class="modal-title">Nuovo Utente</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<label>Username</label>
					</div>
					<div class="col-md-6">
						<input type="text" id="newUtenteUsername" class="form-control">
					</div>
					<div class="col-md-6">
						<label>Nome</label>
					</div>
					<div class="col-md-6">
						<input type="text" id="newUtenteNome" class="form-control">
					</div>
					<div class="col-md-6">
						<label>Cognome</label>
					</div>
					<div class="col-md-6">
						<input type="text" id="newUtenteCognome" class="form-control">
					</div>
					<div class="col-md-6">
						<label>Email</label>
					</div>
					<div class="col-md-6">
						<input type="text" id="newUtenteEmail" class="form-control">
					</div>
					<div class="col-md-6">
						<label>Cellulare</label>
					</div>
					<div class="col-md-6">
						<input type="text" id="newUtenteCellulare" class="form-control">
					</div>
					<div class="col-md-6">
						<label>Indirizzo</label>
					</div>
					<div class="col-md-6">
						<input type="text" id="newUtenteIndirizzo" class="form-control">
					</div>


					<div class="col-md-6">
						<label>Password</label>
					</div>
					<div class="col-md-6">
						<input type="password" id="newUtentepwd1" class="form-control">
					</div>
					<div class="col-md-6">
						<label>Ripeti Password</label>
					</div>
					<div class="col-md-6">
						<input type="password" id="newUtentepwd2" class="form-control">
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" onclick="createNewUtente()" class="btn blue">Crea</button>
				<button type="button" class="btn default" data-dismiss="modal">Chiudi</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="editUtente" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true"></button>
				<h4 class="modal-title">Modifica Utente</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<label>Nome</label>
					</div>
					<div class="col-md-6">
					<input id="editidutente" type="hidden">
						<input type="text" id="editUtenteNome" class="form-control">
					</div>
					<div class="col-md-6">
						<label>Cognome</label>
					</div>
					<div class="col-md-6">
						<input type="text" id="editUtenteCognome" class="form-control">
					</div>
					<div class="col-md-6">
						<label>Email</label>
					</div>
					<div class="col-md-6">
						<input type="text" id="editUtenteEmail" class="form-control">
					</div>
					<div class="col-md-6">
						<label>Cellulare</label>
					</div>
					<div class="col-md-6">
						<input type="text" id="editUtenteCellulare" class="form-control">
					</div>
					<div class="col-md-6">
						<label>Indirizzo</label>
					</div>
					<div class="col-md-6">
						<input type="text" id="editUtenteIndirizzo" class="form-control">
					</div>
					<div class="col-md-6">
						<label>Password</label>
					</div>
					<div class="col-md-6">
						<input type="password" id="editUtentepwd1" class="form-control">
					</div>
					<div class="col-md-6">
						<label>Ripeti Password</label>
					</div>
					<div class="col-md-6">
						<input type="password" id="editUtentepwd2" class="form-control">
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" onclick="updateUtente()" class="btn blue">Aggiorna</button>
				<button type="button" class="btn default" data-dismiss="modal">Chiudi</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="deleteUtente" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true"></button>
				<h4 class="modal-title">Elimina Utente</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
					<div class="alert alert-danger">
					Sei sicuro di voler eliminare l'utente <span id="usernameToDelete"></span>?
					</div>
					<input id="deleteIdutente" type="hidden">	
					</div>					
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" onclick="confirmDeleteUtente()" class="btn blue">Elimina</button>
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



