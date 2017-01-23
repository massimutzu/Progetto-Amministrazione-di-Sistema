function ResetSearchParams() {
	$("#titolo").val("");
	$("#autore").val("");
}

function addLibro(){
	
	IdCatalogo= $("#catId").val();
	
	$.ajax({
		url : 'operatore',
		data : {
				'cmd' : 'nuovoLibro',
				'IdCatalogo' : IdCatalogo
				},
		dataType : "html",
		type : "POST",
		contentType : "application/x-www-form-urlencoded",
		success : function (data) {			
			var parsedData = $.parseJSON(data);
			if (parsedData.status){
					caricaListaLibri(IdCatalogo);
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

function SetNoleggiabile(IdLibro){
	
		var nol= 0;
		if ($("#nol_"+ IdLibro).text()=='OFF') nol=1;
		
		$.ajax({
			url : 'operatore',
			data : {
					'cmd' : 'aggiornaNoleggiabile',
					'IdLibro' : IdLibro,
					'nol' : nol
					},
			dataType : "html",
			type : "POST",
			contentType : "application/x-www-form-urlencoded",
			success : function (data) {
			
				var parsedData = $.parseJSON(data);
				if (parsedData.status){
						if (nol==1){
								$("#nol_"+ IdLibro).removeClass("red").addClass("green");
								$("#nol_"+ IdLibro).text("ON");
						}
						else 
						{
								$("#nol_"+ IdLibro).removeClass("green").addClass("red");
								$("#nol_"+ IdLibro).text("OFF");
						}	
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

function deleteLibro(IdLibro,IdCatalogo ){
	
	
	$.ajax({
		url : 'operatore',
		data : {
				'cmd' : 'deleteLibro',
				'IdLibro' : IdLibro
				},
		dataType : "html",
		type : "POST",
		contentType : "application/x-www-form-urlencoded",
		success : function (data) {			
			var parsedData = $.parseJSON(data);
			if (parsedData.status){
					caricaListaLibri(IdCatalogo);
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

function caricaListaLibri(IdCatalogo){
	
		$.ajax({
		url : 'operatore',
		data : {
			'cmd' : 'LibriByCatalogo',
			'IdCatalogo' : IdCatalogo
		},
		dataType : "html",
		type : "POST",
		contentType : "application/x-www-form-urlencoded",
		success : function (data) {
			var parsedData = $.parseJSON(data);
			if (!$.fn.DataTable.isDataTable("#LibriTable")) {
				$('#LibriTable').DataTable({
					columns : [
						{
							sTitle : "Id",
							data : "id",
							visible : false
						}, 
						{
							sTitle : "Inventario",
								sWidth: "30%",
							data : "codice"
							
						}, 
						{
							sTitle : "Note",
								sWidth: "40%",
							data : "note",
							"render": function ( data, type, full, meta ) {
								var ret="";
								var ret = '<span id="noteId_' + full.id + '" idLibro="' + full.id + '" class="MagEditable" style="display: inline;cursor:pointer">' + data+ '</span>';
								return ret;
							}		
						},
						{
							sTitle : "Noleggiabile",
							sWidth: "30%",
							data : "noleggiabile",
							"render": function ( data, type, full, meta ) {
								var ret="";
								if (data)
								{
									ret = ret + '<button id="nol_'+ full.id +'" type="button" class="btn btn-circle green" onclick="SetNoleggiabile(' + full.id  + ')">ON</button>';
								}
								else
								{
									ret = ret + '<button id="nol_'+ full.id +'" type="button" class="btn btn-circle red" onclick="SetNoleggiabile(' + full.id  + ')">OFF</button>';
								}
								var catString= ''+full.idCatalogo;
								ret = ret + '<button type="button" class="btn btn-circle btn-danger" onclick="deleteLibro(' + full.id  + ', '+ catString +')">Elimina <i class="fa fa-times"></i></button>';
								
								return ret;
							}		
						}						
					]
				});
			}
			var oTable = $('#LibriTable').dataTable();
			oTable.fnClearTable();
			if (parsedData.length > 0) {
				oTable.fnAddData(parsedData);
				
				 $("#LibriTable .MagEditable").editable(function (value, settings) {   
							var idLibro = parseInt($(this).attr("idLibro"));				 
							$.ajax({
							url : 'operatore',
							data : {
								'cmd' : 'aggiornaNota',
								'IdLibro' : idLibro,
								'nota' : value
							},
							dataType : "html",
							type : "POST",
							contentType : "application/x-www-form-urlencoded",
							success : function (data) {
							},
							error : function (response) {
								alert(response);
							},
							failure : function (response) {
								alert(response);
							}
							});
							return value;
					}, 
					{
					"type": 'text',
					"indicator": 'Salvataggio...',
					"tooltip": 'Click per modificare...',
					"height": "14px",
					"width": "180px",
					"submit": "<img src='../assets/img/save.png' />",
					"placeholder": "inserisci nota",
					"onblur": "submit"
					});		
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

function apriDettagliCatalogo(id){
	$.ajax({
		url : 'operatore',
		data : {
			'cmd' : 'searchCatalogo',
			'id' : id
		},
		dataType : "html",
		type : "POST",
		contentType : "application/x-www-form-urlencoded",
		success : function (data) {
			var parsedData = $.parseJSON(data);
			if (parsedData.length>0){
				var cat= parsedData[0];
				$("#catId").val(cat.id);
				$("#catTitolo").val(cat.Titolo);
				$("#catAutore").val(cat.Autore);
				$("#catIsbn").val(cat.Isbn);
				$("#catAnno").val(cat.Anno);
				$("#catGenere").val(cat.Genere);
				$("#catUrl").val(cat.UrlImmagine);
				$("#editCatalogo").modal('show');	
				$("#catImgUrl").attr("src", cat.UrlImmagine);
			}
			caricaListaLibri(id);
			
			
		},
		error : function (response) {
			alert(response);
		},
		failure : function (response) {
			alert(response);

		}
	});
}

function createNewCatalogo(){
	var titolo = $("#newTitolo").val();
	var autore = $("#newAutore").val();
	var isbn = $("#newIsbn").val();
	var genere = $("#newGenere").val();
	var anno = $("#newAnno").val();
	var imgurl = $("#newImgurl").val();
	$.ajax({
		url : 'operatore',
		data : {
			'cmd' : 'nuovoCatalogo',
			'titolo' : titolo,
			'autore' : autore,
			'anno':anno,
			'isbn': isbn,
			'genere': genere,
			'imgurl': imgurl
		},
		dataType : "html",
		type : "POST",
		contentType : "application/x-www-form-urlencoded",		
		success : function (data) {			
			var parsedData = $.parseJSON(data);
			if (parsedData.status){
				SearchCatalogo();
				$("#NewCatalogo").modal("hide");
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

function SearchCatalogo() {
	var titolo = $("#titolo").val();
	var autore = $("#autore").val();
	$.ajax({
		url : 'operatore',
		data : {
			'cmd' : 'searchCatalogo',
			'titolo' : titolo,
			'autore' : autore, 
			'id':''
		},
		dataType : "html",
		type : "POST",
		contentType : "application/x-www-form-urlencoded",
		success : function (data) {
			var parsedData = $.parseJSON(data);
			if (!$.fn.DataTable.isDataTable("#CatalogoTable")) {
				$('#CatalogoTable').DataTable({
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
							sTitle : "Totale",
							sWidth: "20%",
							"data" : "Totale",
							"render": function ( data, type, full, meta ) {
								var TotDisp= full.Totale ;//- (full.NonNoleggiabili + full.Noleggiati);
								var ret="";
								if (TotDisp>0){
									    ret = ret + '<button type="button" class="btn btn-circle green">' + TotDisp + '</button>';
								}
								else 
								{
									    ret = ret + '<button type="button" class="btn btn-circle red">' + TotDisp + '</button>';
								}
								ret = ret + '<button type="button" onclick="apriDettagliCatalogo(' + full.id +')" class="btn btn-circle btn-primary">Dettagli <i class="fa fa-search"></i></button>';
								return ret;
							}
						}
					]
				});
			}
			var oTable = $('#CatalogoTable').dataTable();
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

function updateCatalogo(){
	var id = $("#catId").val();
	var titolo = $("#catTitolo").val();
	var autore = $("#catAutore").val();
	var isbn = $("#catIsbn").val();
	var genere = $("#catGenere").val();
	var anno = $("#catAnno").val();
	var imgurl = $("#catUrl").val();
	
	$.ajax({
		url : 'operatore',
		data : {
			'cmd' : 'updateCatalogo',
			'id' : id,
			'titolo' : titolo,
			'autore' : autore,
			'anno':anno,
			'isbn': isbn,
			'genere': genere,
			'imgurl': imgurl
		},
		dataType : "html",
		type : "POST",
		contentType : "application/x-www-form-urlencoded",
		success : function (data) {
			var parsedData = $.parseJSON(data);
			if (parsedData.status){
				$("#feedback_error").hide();
				$("#feedback_success").show();
				$("#feedback_success").html(parsedData.msg);
				$("#feedback_error").html('');
				$("#feedback").modal("show");
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

function deleteCatalogo(){
	
		IdCatalogo= $("#catId").val();
	$("#deleteIdCatalogo").val(IdCatalogo);
	$("#deleteCatalogo").modal('show');
}

function confirmDeleteCatalogo(){
	IdCatalogo= $("#deleteIdCatalogo").val();
		$.ajax({
		url : 'operatore',
		data : {
				'cmd' : 'deleteCatalogo',
				'IdCatalogo' : IdCatalogo
				},
		dataType : "html",
		type : "POST",
		contentType : "application/x-www-form-urlencoded",
		success : function (data) {		
			$("#deleteCatalogo").modal('hide');		
			var parsedData = $.parseJSON(data);
			if (parsedData.status){
				$("#feedback_error").hide();
				$("#feedback_success").show();
				$("#feedback_success").html(parsedData.msg);
				$("#feedback_error").html('');
				$("#editCatalogo").modal('hide');
				SearchCatalogo();
			}
			else 
			{
				$("#feedback_error").show();
				$("#feedback_success").hide();
				$("#feedback_success").html('');
				$("#feedback_error").html(parsedData.msg);				
			}
			$("#feedback").modal("show");
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
	$("#autore,#newAutore").autocomplete({
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

	$("#catUrl").change( function (){
		$("#catImgUrl").attr("src", $("#catUrl").val());
	});
	

	
	$("#li_catalogo").addClass('start active');
	$('#spn_sel_catalogo').addClass('selected');

});
