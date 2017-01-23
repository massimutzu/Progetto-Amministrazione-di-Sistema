
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
									placeholder="Inserire Titolo" name="titolo" id="titolo">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Autore</label>
							<div class="col-md-8">
								<input type="text" class="form-control input-circle"
									placeholder="Inserire Autore" name="autore" id="autore">
							</div>
						</div>
					</div>
					<div class="form-actions">
						<div class="row">
							<div class="col-md-offset-3 col-md-9">
								<button
								onclick="SearchCatalog()" 
								 type="button" class="btn btn-circle blue">Cerca</button>
								<button type="button" class="btn btn-circle default"
									onclick="ResetCatalogoParams()">Reset</button>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="col-md-2"></div>
		</div>
		<div class="row">
			<div class="cols-md-8">
				<table id="CatalogTable"></table>

			</div>
		</div>

	</div>
</div>