
<div class="page-content-wrapper">
	<div class="page-content" id="content">
		<div class="row">
			<div class="col-md-8">
				<form action="Operatore/catalogo" method="post"
					class="form-horizontal">
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
					</div>
					<div class="form-actions">
						<div class="row">
							<div class="col-md-offset-3 col-md-9">
								<button onclick="SearchOperatori()" type="button"
									class="btn btn-circle blue">Cerca</button>
								<button type="button" class="btn btn-circle default"
									onclick="ResetOperatoriParams()">Reset</button>
								<button type="button" class="btn btn-circle green"
									onclick="$('#NewOperatore').modal('show');">
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
				<table id="OperatoriTable"></table>

			</div>
		</div>

	</div>
</div>

<div class="modal fade" id="NewOperatore" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true"></button>
				<h4 class="modal-title">Nuovo Operatore</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<label>Username</label>
					</div>
					<div class="col-md-6">
						<input type="text" id="newOperatoreUsername" class="form-control">
					</div>
					<div class="col-md-6">
						<label>Nome</label>
					</div>
					<div class="col-md-6">
						<input type="text" id="newOperatoreNome" class="form-control">
					</div>
					<div class="col-md-6">
						<label>Cognome</label>
					</div>
					<div class="col-md-6">
						<input type="text" id="newOperatoreCognome" class="form-control">
					</div>
					<div class="col-md-6">
						<label>Email</label>
					</div>
					<div class="col-md-6">
						<input type="text" id="newOperatoreEmail" class="form-control">
					</div>
					<div class="col-md-6">
						<label>Cellulare</label>
					</div>
					<div class="col-md-6">
						<input type="text" id="newOperatoreCellulare" class="form-control">
					</div>
					<div class="col-md-6">
						<label>Password</label>
					</div>
					<div class="col-md-6">
						<input type="password" id="newOperatorepwd1" class="form-control">
					</div>
					<div class="col-md-6">
						<label>Ripeti Password</label>
					</div>
					<div class="col-md-6">
						<input type="password" id="newOperatorepwd2" class="form-control">
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" onclick="createNewOperatore()"
					class="btn blue">Crea</button>
				<button type="button" class="btn default" data-dismiss="modal">Chiudi</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="editOperatore" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true"></button>
				<h4 class="modal-title">Modifica Operatore</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<label>Nome</label>
					</div>
					<div class="col-md-6">
						<input id="editidOperatore" type="hidden"> <input type="text"
							id="editOperatoreNome" class="form-control">
					</div>
					<div class="col-md-6">
						<label>Cognome</label>
					</div>
					<div class="col-md-6">
						<input type="text" id="editOperatoreCognome" class="form-control">
					</div>
					<div class="col-md-6">
						<label>Email</label>
					</div>
					<div class="col-md-6">
						<input type="text" id="editOperatoreEmail" class="form-control">
					</div>
					<div class="col-md-6">
						<label>Cellulare</label>
					</div>
					<div class="col-md-6">
						<input type="text" id="editOperatoreCellulare"
							class="form-control">
					</div>					
					<div class="col-md-6">
						<label>Password</label>
					</div>
					<div class="col-md-6">
						<input type="password" id="editOperatorepwd1" class="form-control">
					</div>
					<div class="col-md-6">
						<label>Ripeti Password</label>
					</div>
					<div class="col-md-6">
						<input type="password" id="editOperatorepwd2" class="form-control">
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" onclick="updateOperatore()" class="btn blue">Aggiorna</button>
				<button type="button" class="btn default" data-dismiss="modal">Chiudi</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="deleteOperatore" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true"></button>
				<h4 class="modal-title">Elimina Operatore</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-danger">
							Sei sicuro di voler eliminare l'Operatore <span
								id="usernameToDelete"></span>?
						</div>
						<input id="deleteIdOperatore" type="hidden">
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" onclick="confirmDeleteOperatore()"
					class="btn blue">Elimina</button>
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



