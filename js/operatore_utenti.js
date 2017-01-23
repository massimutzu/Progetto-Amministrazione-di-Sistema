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
		dataFilter : function (data) {
			return data;
		},
		success : function (data) {
			var parsedData = $.parseJSON(data);
			if (!$.fn.DataTable.isDataTable("#UserTable")) {
				$('#UserTable').DataTable({
					columns : [{
							sTitle : "Id",
							data : "id",
							visible : false
						}, 
						{
							sTitle : "",
							data : "Abilitato",
							"render": function ( data, type, full, meta ) {
									var ret="";
								if (data)
								{
									   ret = ret + '<button id="abilitato_'+ full.id +'" type="button" class="btn green" onclick="ToggleAbilitato(' + full.id  + ')">ON</button>';
								}
								else
								{
									ret = ret + '<button id="abilitato_'+ full.id +'" type="button" class="btn red" onclick="ToggleAbilitato(' + full.id  + ')">OFF</button>';
								}
								return ret;
							}		}
						,
						{
							sTitle : "Nome",
							data : "Nome"
						}, {
							sTitle : "Cognome",
							"data" : "Cognome"
						}, {
							sTitle : "Username",
							"data" : "Username"
						}, {
							sTitle : "Email",
							"data" : "Email"
						}, {
							sTitle : "Cellulare",
							"data" : "Cellulare"
						}, {
							sTitle : "Indirizzo",
							"data" : "Indirizzo"
						}, 
						{
							sTitle : "",
							sWidth: "16%", 
							data : "Azioni",
							"render": function ( data, type, full, meta ) {
								var ret="";								
								ret = ret + '<button type="button" class="btn btn-info" onclick="ShowEditUtente(' + full.id  + ',\''+ full.Nome +'\',\''+  full.Cognome +'\' ,\''+  full.Email +'\',\''+  full.Cellulare +'\' ,\''+  full.Indirizzo +'\' )">Edit</button>';
								ret = ret + '<button type="button" class="btn btn-danger" onclick="ShowDeleteUtente(' + full.id + ',\''+ full.Username +'\')">Del</button>';
								return ret;
							}
							
						}
					]
				});
			}
			var oTable = $('#UserTable').dataTable();
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

function ShowDeleteUtente(id, username){
	 $("#usernameToDelete").text(username);
	 $("#deleteIdutente").val(id);
	 $("#deleteUtente").modal('show');
	
}
function confirmDeleteUtente(){
	
	var idUtente= $("#deleteIdutente").val();
		$.ajax({
		url : 'operatore',
		data : {
			'cmd' : 'deleteUtente',
			'IdUtente':idUtente		
		},
		dataType : "html",
		type : "POST",
		contentType : "application/x-www-form-urlencoded",
		dataFilter : function (data) {
			return data;
		},
		success : function (data) {
			var parsedData = $.parseJSON(data);
			if (parsedData.status){
				$("#feedback_error").hide();
				$("#feedback_success").show();
				$("#feedback_success").html(parsedData.msg);
				$("#feedback_error").html('');
				$("#deleteUtente").modal('hide');		
				SearchUtenti();
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



function ShowEditUtente(id,nome,cognome,email,cellulare,indirizzo){
	 $("#editidutente").val(id);
	 $("#editUtenteNome").val(nome);
	 $("#editUtenteCognome").val(cognome);
	 $("#editUtenteIndirizzo").val(indirizzo);
	 $("#editUtenteEmail").val(email);
	 $("#editUtenteCellulare").val(cellulare);	
	 $("#editUtente").modal('show');
	
}

function updateUtente(){
	var idutente = $("#editidutente").val();
	var nome = $("#editUtenteNome").val();
	var cognome = $("#editUtenteCognome").val();
	var indirizzo = $("#editUtenteIndirizzo").val();
	var email = $("#editUtenteEmail").val();
	var cellulare = $("#editUtenteCellulare").val();
	var password1 = $("#editUtentepwd1").val();
	var password2= $("#editUtentepwd2").val();
	$.ajax({
		url : 'operatore',
		data : {
			'cmd' : 'editUtente',
			'IdUtente':idutente,
			'nome' : nome,
			'cognome' : cognome,
			'indirizzo' : indirizzo,
			'email' : email,
			'cellulare' : cellulare,
			'password1' : password1,
			'password2' : password2			
		},
		dataType : "html",
		type : "POST",
		contentType : "application/x-www-form-urlencoded",
		dataFilter : function (data) {
			return data;
		},
		success : function (data) {
			var parsedData = $.parseJSON(data);
			if (parsedData.status){
				$("#feedback_error").hide();
				$("#feedback_success").show();
				$("#feedback_success").html(parsedData.msg);
				$("#feedback_error").html('');
				$("#editUtente").modal('hide');		
				SearchUtenti();
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


function ToggleAbilitato(IdUtente){
	
	$.ajax({
		url : 'operatore',
		data : {
			'cmd' : 'toggleUtenteAbilitato',
			'IdUtente':IdUtente		
		},
		dataType : "html",
		type : "POST",
		contentType : "application/x-www-form-urlencoded",
		dataFilter : function (data) {
			return data;
		},
		success : function (data) {
			var parsedData = $.parseJSON(data);
			if (parsedData.status){
					if (parsedData.newValue){
							$("#abilitato_"+ IdUtente).removeClass("red").addClass("green");
							$("#abilitato_"+ IdUtente).text("ON");
					}
					else 
					{
							$("#abilitato_"+ IdUtente).removeClass("green").addClass("red");
							$("#abilitato_"+ IdUtente).text("OFF");
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

function createNewUtente(){
	var username = $("#newUtenteUsername").val();
	var nome = $("#newUtenteNome").val();
	var cognome = $("#newUtenteCognome").val();
	var indirizzo = $("#newUtenteIndirizzo").val();
	var email = $("#newUtenteEmail").val();
	var cellulare = $("#newUtenteCellulare").val();
	var password1 = $("#newUtentepwd1").val();
	var password2= $("#newUtentepwd2").val();
	
	
	$.ajax({
		url : 'operatore',
		data : {
			'cmd' : 'addUtente',
			'username':username,
			'nome' : nome,
			'cognome' : cognome,
			'indirizzo' : indirizzo,
			'email' : email,
			'cellulare' : cellulare,
			'password1' : password1,
			'password2' : password2			
		},
		dataType : "html",
		type : "POST",
		contentType : "application/x-www-form-urlencoded",
		dataFilter : function (data) {
			return data;
		},
		success : function (data) {
			var parsedData = $.parseJSON(data);
			if (parsedData.status){
				$("#feedback_error").hide();
				$("#feedback_success").show();
				$("#feedback_success").html(parsedData.msg);
				$("#feedback_error").html('');
				$("#NewUtente").modal('hide');				
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
		
	$("#scognome").autocomplete({
		source : function (request, response) {
			$.ajax({
				url : 'operatore',
				data : {
					'cmd' : 'CognomeComplete',
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
	$("#li_users").addClass('start active');
	$('#spn_sel_users').addClass('selected');

});
