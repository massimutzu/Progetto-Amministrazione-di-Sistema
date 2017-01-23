function ResetCatalogoParams() {
	$("#titolo").val("");
	$("#autore").val("");
}

function SearchCatalog() {
	var titolo = $("#titolo").val();
	var autore = $("#autore").val();
	$.ajax({
		url : 'utente',
		data : {
			'cmd' : 'searchCatalogo',
			'titolo' : titolo,
			'autore' : autore
		},
		dataType : "html",
		type : "POST",
		contentType : "application/x-www-form-urlencoded",
		dataFilter : function (data) {
			return data;
		},
		success : function (data) {
			var parsedData = $.parseJSON(data);
			if (!$.fn.DataTable.isDataTable("#CatalogTable")) {
				$('#CatalogTable').DataTable({
					columns : [{
							sTitle : "Id",
							data : "id",
							visible : false
						}, 
						{
							sTitle : "",
							data : "UrlImmagine",
							"render": function ( data, type, full, meta ) {
								if (data!=null)
								{
									ret= '<img width="32px"  src="' + data  + '" />';
								}
								else
								{
									ret="";
								}
								return ret;
							}
							
						}
						,
						{
							sTitle : "Titolo",
							data : "Titolo"
						}, {
							sTitle : "Autore",
							"data" : "Autore"
						}, {
							sTitle : "Genere",
							"data" : "Genere"
						}, {
							sTitle : "Anno",
							"data" : "Anno"
						}, {
							sTitle : "Isbn",
							"data" : "Isbn"
						}
						, {
							sTitle : "Disponibili",
							"data" : "Totale",
							"render": function ( data, type, full, meta ) {
								var TotDisp= full.Totale - (full.NonNoleggiabili + full.Noleggiati);
								var ret="";
								if (TotDisp>0){
									    ret = ret + '<button type="button" class="btn green">' + TotDisp + '</button>';
								}
								else 
								{
									    ret = ret + '<button type="button" class="btn red">' + TotDisp + '</button>';
								}
								return ret;
							}
						}
					]
				});
			}
			var oTable = $('#CatalogTable').dataTable();
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

	$("#titolo").autocomplete({
		source : function (request, response) {
			$.ajax({
				url : 'utente',
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
	$("#autore").autocomplete({
		source : function (request, response) {
			$.ajax({
				url : 'utente',
				data : {
					'cmd' : 'autoreComplete',
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

	$("#li_catalogo").addClass('start active');
	$('#spn_sel_catalogo').addClass('selected');

});
