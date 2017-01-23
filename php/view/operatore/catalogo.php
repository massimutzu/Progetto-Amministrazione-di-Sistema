
<div class="page-content-wrapper">
	<div class="page-content" id="content">
		<div class="row">
			<div class="col-md-8">
				<form action="utente/catalogo" method="post" class="form-horizontal">
					<div class="form-body">
						<div class="form-group">
							<label class="col-md-3 control-label">Titolo</label>
							<div class="col-md-8">
								<input type="hidden" name="cmd" value="searchCatalogo"> <input
									type="text" class="form-control input-circle"
									placeholder="Inserire Titolo" id="titolo">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Autore</label>
							<div class="col-md-8">
								<input type="text" class="form-control input-circle"
									placeholder="Inserire Autore" id="autore">
							</div>
						</div>
					</div>
					<div class="form-actions">
						<div class="row">
							<div class="col-md-offset-3 col-md-9">
								<button onclick="SearchCatalogo()" type="button"
									class="btn btn-circle blue">Cerca</button>
								<button type="button" class="btn btn-circle default"
									onclick="ResetSearchParams()">Reset</button>
								<button type="button" class="btn btn-circle green"
									onclick="$('#NewCatalogo').modal('show');">
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
				<table id="CatalogoTable"></table>

			</div>
		</div>

	</div>
</div>

<div class="modal fade" id="NewCatalogo" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true"></button>
				<h4 class="modal-title">Nuovo Catalogo</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<label>Titolo</label>
					</div>
					<div class="col-md-6">
						<input type="text" id="newTitolo" class="form-control">
					</div>
					<div class="col-md-6">
						<label>Autore</label>
					</div>
					<div class="col-md-6">
						<input type="text" id="newAutore" class="form-control">
					</div>
					<div class="col-md-6">
						<label>isbn</label>
					</div>
					<div class="col-md-6">
						<input type="text" id="newIsbn" class="form-control">
					</div>
					<div class="col-md-6">
						<label>Genere</label>
					</div>
					<div class="col-md-6">
						<input type="text" id="newGenere" class="form-control">
					</div>
					<div class="col-md-6">
						<label>Anno</label>
					</div>
					<div class="col-md-6">
						<input type="text" id="newAnno" class="form-control"> <input
							type="hidden" id="newImgurl" class="form-control">
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" onclick="createNewCatalogo()"
					class="btn btn-circle blue">Crea</button>
				<button type="button" class="btn btn-circle default"
					data-dismiss="modal">Chiudi</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="editCatalogo" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true"></button>
				<h4 class="modal-title">Modifica Catalogo</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-4 col-sm-12">
						<div class="thumbnail">
							<img id="catImgUrl"
								src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
								style="height: 200px" alt="">
						</div>
					</div>
					<div class="col-md-8 col-sm-12">
						<div class="col-md-4">
							<label>Titolo</label>
						</div>
						<div class="col-md-8">
							<input type="hidden" id="catId" class="form-control"> <input
								type="text" id="catTitolo" class="form-control">
						</div>
						<div class="col-md-4">
							<label>Autore</label>
						</div>
						<div class="col-md-8">
							<input type="text" id="catAutore" class="form-control">
						</div>
						<div class="col-md-4">
							<label>Isbn</label>
						</div>
						<div class="col-md-8">
							<input type="text" id="catIsbn" class="form-control">
						</div>
						<div class="col-md-4">
							<label>Anno</label>
						</div>
						<div class="col-md-8">
							<input type="text" id="catAnno" class="form-control">
						</div>
						<div class="col-md-4">
							<label>Genere</label>
						</div>
						<div class="col-md-8">
							<input type="text" id="catGenere" class="form-control">
						</div>
						<div class="col-md-4">
							<label>Url Immagine</label>
						</div>
						<div class="col-md-8">
							<input type="text" id="catUrl" class="form-control">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="cols-md-12 cols-sm-12">
						<table id="LibriTable"></table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="addLibro()"
					class="btn btn-circle green">Nuovo Libro <i class="icon-plus"></i></button>
				<button type="button" onclick="updateCatalogo()"
					class="btn btn-circle blue">Aggiorna</button>
					<button type="button" onclick="deleteCatalogo()"
					class="btn btn-circle red">Elimina Voce</button>
				<button type="button" class="btn btn-circle default"
					data-dismiss="modal">Chiudi</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="deleteCatalogo" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true"></button>
				<h4 class="modal-title">Elimina Catalogo</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-danger">
							Sei sicuro di voler eliminare
						</div>
						<input id="deleteIdCatalogo" type="hidden">
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" onclick="confirmDeleteCatalogo()"
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



