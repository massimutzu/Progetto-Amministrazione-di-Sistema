function ResetUtentiParams() {
	$("#snome").val("");
	$("#scognome").val("");
	$("#sindirizzo").val("");
}

function SearchUtenti() {
	var nome = $("#snome").val();
	var cognome = $("#scognome").val();
	var indirizzo = $("#sindirizzo").val();
	$.ajax({
				url : 'operatore',
				data : {
					'cmd' : 'searchUtenti',
					'nome' : nome,
					'cognome' : cognome,
					'indirizzo' : indirizzo
				},
				dataType : "html",
				type : "POST",
				contentType : "application/x-www-form-urlencoded",
				dataFilter : function(data) {
					return data;
				},
				success : function(data) {
					var parsedData = $.parseJSON(data);
					if (!$.fn.DataTable.isDataTable("#UserTable")) {
						$('#UserTable')
								.DataTable(
										{
											columns : [
													{
														sTitle : "Id",
														data : "id",
														visible : false
													},
													{
														sTitle : "Nome",
														data : "Nome"
													},
													{
														sTitle : "Cognome",
														"data" : "Cognome"
													},
													{
														sTitle : "Username",
														"data" : "Username"
													},
													{
														sTitle : "Email",
														"data" : "Email"
													},
													{
														sTitle : "Cellulare",
														"data" : "Cellulare"
													},
													{
														sTitle : "Indirizzo",
														"data" : "Indirizzo"
													},
													{
														sTitle : "Noleggi Aperti",
														data : "Noleggiati",
														"render" : function(
																data, type,
																full, meta) {
															var ret = "";
															ret = ret
																	+ '<button type="button" class="btn btn-info">'
																	+ data
																	+ '</button>';
															return ret;
														}
													},
													{
														sTitle : "",
														sWidth : "16%",
														data : "Azioni",
														"render" : function(
																data, type,
																full, meta) {
															var ret = "";

															ret = ret
																	+ '<button type="button" class="btn btn-info" onclick="ShowNewNoleggio('
																	+ full.id
																	+ ',\''
																	+ full.Nome
																	+ '\',\''
																	+ full.Cognome
																	+ '\')">Nuovo Noleggio</button>';
															return ret;
														}

													} ]
										});
					}
					var oTable = $('#UserTable').dataTable();
					oTable.fnClearTable();
					if (parsedData.length > 0) {
						oTable.fnAddData(parsedData);
					}

				},
				error : function(response) {
					alert(response);
				},
				failure : function(response) {
					alert(response);

				}
			});

}

function ShowNewNoleggio(id, nome, cognome) {
	$("#idUtente").val(id);
	$("#newRentUser").text(nome + " " + cognome);
	$("#NewNoleggio").modal('show');
}

function createNewNoleggio() {
	var idUtente = $("#idUtente").val();
	var inventario = $("#inventario").val();

	$.ajax({
		url : 'operatore',
		data : {
			'cmd' : 'apriNoleggio',
			'idUtente' : idUtente,
			'inventario' : inventario
		},
		dataType : "html",
		type : "POST",
		contentType : "application/x-www-form-urlencoded",
		dataFilter : function(data) {
			return data;
		},
		success : function(data) {
			$("#NewNoleggio").modal('hide');
			var parsedData = $.parseJSON(data);
			if (parsedData.status) {
				$("#feedback_error").hide();
				$("#feedback_success").show();
				$("#feedback_success").html(parsedData.msg);
				$("#feedback_error").html('');
				SearchUtenti();
			} else {
				$("#feedback_error").show();
				$("#feedback_success").hide();
				$("#feedback_success").html('');
				$("#feedback_error").html(parsedData.msg);
			}
			$("#feedback").modal("show");

		},
		error : function(response) {
			alert(response);
		},
		failure : function(response) {
			alert(response);

		}
	});

}

$(document).ready(function() {

	$("#scognome").autocomplete({
		source : function(request, response) {
			$.ajax({
				url : 'operatore',
				data : {
					'cmd' : 'CognomeComplete',
					'Text' : escape(request.term)
				},
				dataType : "html",
				type : "POST",
				contentType : "application/x-www-form-urlencoded",
				dataFilter : function(data) {
					return data;
				},
				success : function(data) {
					response($.parseJSON(data));
				},
				error : function(response) {
					alert(response);
				},
				failure : function(response) {
					alert(response);

				}
			});
		},
		minLength : 2
	});
	$("#li_rents").addClass('start active');
	$('#spn_sel_rents').addClass('selected');

});
