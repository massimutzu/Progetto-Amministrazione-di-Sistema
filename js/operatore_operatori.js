function ResetOperatoriParams() {
	$("#snome").val("");	
	$("#scognome").val("");		
}

function SearchOperatori() {
	var nome = $("#snome").val();
	var cognome = $("#scognome").val();
	$.ajax({
		url : 'operatore',
		data : {
			'cmd' : 'searchOperatori',
			'nome' : nome,
			'cognome' : cognome
		},
		dataType : "html",
		type : "POST",
		contentType : "application/x-www-form-urlencoded",
		dataFilter : function (data) {
			return data;
		},
		success : function (data) {
			var currentId= $("#CurrentId").val();
			var parsedData = $.parseJSON(data);
			if (!$.fn.DataTable.isDataTable("#OperatoriTable")) {
				$('#OperatoriTable').DataTable({
					columns : [{
							sTitle : "Id",
							data : "id",
							visible : false
						}, 
						{
							sTitle : "",
							sWidth:"25%",
							data : "Abilitato",
							"render": function ( data, type, full, meta ) {
								var ret="";
								var optionalDisabled= "";
								if (currentId== full.id){
									optionalDisabled= "disabled";
									
								}
								if (data)
								{
									   ret = ret + '<button id="abilitato_'+ full.id +'" ' + optionalDisabled + '  type="button" class="btn btn-circle green" onclick="ToggleAbilitato(' + full.id  + ')">ON</button>';
								}
								else
								{
									ret = ret + '<button id="abilitato_'+ full.id +'" ' + optionalDisabled + ' type="button" class="btn btn-circle red" onclick="ToggleAbilitato(' + full.id  + ')">OFF</button>';
								}
								if (full.Admin)
								{
									ret = ret + '<button id="admin_'+ full.id +'" ' + optionalDisabled + ' type="button" class="btn btn-circle green" onclick="ToggleAdmin(' + full.id  + ')">ADMIN <i class="fa fa-unlock"></i></button>';
								}
								else 
								{
									ret = ret + '<button id="admin_'+ full.id +'" ' + optionalDisabled + ' type="button" class="btn btn-circle red" onclick="ToggleAdmin(' + full.id  + ')">ADMIN <i class="fa fa-lock"></i></button>';
								}
								return ret;
							}		
						}
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
						},
						{
							sTitle : "",
							sWidth: "16%", 
							data : "Azioni",
							"render": function ( data, type, full, meta ) {
								var ret="";								
								ret = ret + '<button type="button" class="btn btn-info" onclick="ShowEditOperatore(' + full.id  + ',\''+ full.Nome +'\',\''+  full.Cognome +'\' ,\''+  full.Email +'\',\''+  full.Cellulare +'\' ,\''+  full.Indirizzo +'\' )">Edit</button>';
								ret = ret + '<button type="button" class="btn btn-danger" onclick="ShowDeleteOperatore(' + full.id + ',\''+ full.Username +'\')">Del</button>';
								return ret;
							}
							
						}
					]
				});
			}
			var oTable = $('#OperatoriTable').dataTable();
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

function ShowDeleteOperatore(id, username){
	 $("#usernameToDelete").text(username);
	 $("#deleteIdOperatore").val(id);
	 $("#deleteOperatore").modal('show');
	
}

function confirmDeleteOperatore(){
	
	var idOperatore= $("#deleteIdOperatore").val();
		$.ajax({
		url : 'operatore',
		data : {
			'cmd' : 'deleteOperatore',
			'IdOperatore':idOperatore		
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
				$("#deleteOperatore").modal('hide');		
				SearchOperatori();
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

function ShowEditOperatore(id,nome,cognome,email,cellulare,indirizzo){
	 $("#editidOperatore").val(id);
	 $("#editOperatoreNome").val(nome);
	 $("#editOperatoreCognome").val(cognome);	 
	 $("#editOperatoreEmail").val(email);
	 $("#editOperatoreCellulare").val(cellulare);	
	 $("#editOperatore").modal('show');
	
}

function updateOperatore(){
	var idOperatore = $("#editidOperatore").val();
	var nome = $("#editOperatoreNome").val();
	var cognome = $("#editOperatoreCognome").val();
		var email = $("#editOperatoreEmail").val();
	var cellulare = $("#editOperatoreCellulare").val();
	var password1 = $("#editOperatorepwd1").val();
	var password2= $("#editOperatorepwd2").val();
	$.ajax({
		url : 'operatore',
		data : {
			'cmd' : 'editOperatore',
			'IdOperatore':idOperatore,
			'nome' : nome,
			'cognome' : cognome,
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
				$("#editOperatore").modal('hide');		
				SearchOperatori();
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

function ToggleAbilitato(IdOperatore){
	
	$.ajax({
		url : 'operatore',
		data : {
			'cmd' : 'toggleOperatoreAbilitato',
			'IdOperatore':IdOperatore		
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
							$("#abilitato_"+ IdOperatore).removeClass("red").addClass("green");
							$("#abilitato_"+ IdOperatore).text("ON");
					}
					else 
					{
							$("#abilitato_"+ IdOperatore).removeClass("green").addClass("red");
							$("#abilitato_"+ IdOperatore).text("OFF");
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

function ToggleAdmin(IdOperatore){
	
	$.ajax({
		url : 'operatore',
		data : {
			'cmd' : 'toggleOperatoreAdmin',
			'IdOperatore':IdOperatore		
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
							$("#admin_"+ IdOperatore).removeClass("red").addClass("green");
							$("#admin_"+ IdOperatore).empty();
							$("#admin_"+ IdOperatore).html('ADMIN <i class="fa fa-unlock">');
					}
					else 
					{
							$("#admin_"+ IdOperatore).removeClass("green").addClass("red");
							$("#admin_"+ IdOperatore).empty();
							$("#admin_"+ IdOperatore).html('ADMIN <i class="fa fa-lock">');
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

function createNewOperatore(){
	var username = $("#newOperatoreUsername").val();
	var nome = $("#newOperatoreNome").val();
	var cognome = $("#newOperatoreCognome").val();
	var email = $("#newOperatoreEmail").val();
	var cellulare = $("#newOperatoreCellulare").val();
	var password1 = $("#newOperatorepwd1").val();
	var password2= $("#newOperatorepwd2").val();
	$.ajax({
		url : 'operatore',
		data : {
			'cmd' : 'addOperatore',
			'username':username,
			'nome' : nome,
			'cognome' : cognome,
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
				$("#NewOperatore").modal('hide');		
				SearchOperatori();				
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
		
	$("#li_operatori").addClass('start active');
	$('#spn_operatori').addClass('selected');

});
