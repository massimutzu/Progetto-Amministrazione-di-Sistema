function ResetSearchParams() {
	$("#sinventario").val("");
	$("#stitolo").val("");
	$("#sautore").val("");
	$("#snome").val("");
	$("#scognome").val("");
	$("#StatusAll").click();
}

function confirmCloseNoleggio(){
	var IdNoleggio=	$("#closeIdNoleggio").val();
	ChiudiNoleggio(IdNoleggio);
	
}

function ChiudiNoleggio(IdNoleggio ){
	
	
	$.ajax({
		url : 'operatore',
		data : {
				'cmd' : 'chiudiNoleggio',
				'IdNoleggio' : IdNoleggio
				},
		dataType : "html",
		type : "POST",
		contentType : "application/x-www-form-urlencoded",
		success : function (data) {			
			var parsedData = $.parseJSON(data);
			if (parsedData.status){
					$("#chiudiNoleggio").modal("hide");
					SearchNoleggi();
			}
			else 
			{
				$("#feedback_error").show();
				$("#feedback_success").hide();
				$("#feedback_success").html('');
				$("#feedback_error").html(parsedData.msg);
				$("#feedback").modal("show");
			}
			
		},
		error : function (response) {
			alert(response);
		},
		failure : function (response) {
			alert(response);
		}
	});
	
	
}

function ShowChiudiNoleggio(idNoleggio, Titolo){
	$("#noleggioTitleToClose").text(Titolo);
	$("#closeIdNoleggio").val(idNoleggio);
	$("#chiudiNoleggio").modal("show");
}

function SearchNoleggi() {
	var titolo = $("#stitolo").val();
	var autore = $("#sautore").val();
	var inventario = $("#sinventario").val();
	var nome = $("#snome").val();
	var cognome = $("#scognome").val();
	var status = $('#statoNoleggi label.active input').val();

	
	$.ajax({
		url : 'operatore',
		data : {
			'cmd' : 'searchNoleggi',
			'titolo' : titolo,
			'autore' : autore,
			'inventario' : inventario,
			'nome' : nome,
			'cognome' : cognome,
			'status' : status		
		},
		dataType : "html",
		type : "POST",
		contentType : "application/x-www-form-urlencoded",
		success : function (data) {
			var parsedData = $.parseJSON(data);
			if (!$.fn.DataTable.isDataTable("#NoleggiTable")) {
				$('#NoleggiTable').DataTable({
					columns : [{
							sTitle : "Id",
							data : "id",
							visible : false
						}, 
						{
							sTitle : "Inventario",
							data : "Inventario"							
						}
						,
						{
							sTitle : "Titolo",
							data : "Titolo"
						}, 
						{
							sTitle : "Autore",
							data   : "Autore"
						}, 
						{
							sTitle : "Utente",
							data   : "Nome",
							render : function ( data, type, full, meta ) {
									var ret="";
									ret= full.Nome + ' ' + full.Cognome;
									return ret;
							}
						}, 
						{
							sTitle : "Inizio",
							data : "StartRent"
						}, 
						{
							sTitle : "Fine",
							data : "EndRent", 
							render: function ( data, type, full, meta ) {
									var ret="";
									if (data!=null){
										ret= data;
									}
									return ret;
							}
						}, 
						{
							sTitle : "Giorni",
							data : "GiorniNoleggio"
						}
						, 
						{
							sTitle : "Azioni",
							sWidth: "20%",
							data : "id",
							render: function ( data, type, full, meta ) {
								var ret ="";	
								if (full.EndRent==null){									
									ret = ret + '<button id="nol_'+ full.id +'" type="button" class="btn btn-circle red" onclick="ShowChiudiNoleggio(' + full.id  + ',\''+ full.Titolo +'\')">CHIUDI</button>';
								}
								return ret;								
							}
						}
					]
				});
			}
			var oTable = $('#NoleggiTable').dataTable();
			oTable.fnClearTable();
			if (parsedData.length > 0) {
				oTable.fnAddData(parsedData);
			}

		},
		error : function (response) {
			alert(response);
		},
		failure : function (response) {
			alert(response);

		}
	});
}


$(document).ready(function () {

	$("#stitolo").autocomplete({
		source : function (request, response) {
			$.ajax({
				url : 'operatore',
				data : {
					'cmd' : 'titleComplete',
					'Text' : escape(request.term)
				},
				dataType : "html",
				type : "POST",
				contentType : "application/x-www-form-urlencoded",
				dataFilter : function (data) {
					return data;
				},
				success : function (data) {
					response($.parseJSON(data));
				},
				error : function (response) {
					alert(response);
				},
				failure : function (response) {
					alert(response);

				}
			});
		},
		minLength : 2
	});
	$("#sautore").autocomplete({
		source : function (request, response) {
			$.ajax({
				url : 'operatore',
				data : {
					'cmd' : 'autoreComplete',
					'Text' : escape(request.term)
				},
				dataType : "html",
				type : "POST",
				contentType : "application/x-www-form-urlencoded",				
				success : function (data) {
					response($.parseJSON(data));
				},
				error : function (response) {
					alert(response);
				},
				failure : function (response) {
					alert(response);
				}
			});
		},
		minLength : 2
	});

	$("#li_rents").addClass('start active');
	$('#spn_sel_rents').addClass('selected');
});
