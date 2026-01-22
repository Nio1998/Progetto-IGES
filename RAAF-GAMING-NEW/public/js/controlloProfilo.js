function controlloCambioCredenziali(){
	
	if($("#inputPassword1").val().length>0 && $("#inputPassword2").val().length>0){ //se ha scritto qualcosa nel campo nuova password
		
		if($("#inputPassword1").val().length>=8){//se la password è valida
			$("#inputPassword1").css("border","2px solid green");
			
			if($("#inputPassword1").val() == $("#inputPassword2").val()){ //lunghezza conferma password corretta e coincide con quella di prima
				
				$("#inputPassword2").css("border","2px solid green");
			} 
			else if($("#inputPassword1").val() != $("#inputPassword2").val()){
				
				alert("Le password non corrispondono");
				$("#inputPassword2").val("");
				$("#inputPassword2").css("border","2px solid red");
				return false;
			}
		}
		else{
			alert("password non valida");
			$("#inputPassword1").val("");
			$("#inputPassword1").css("border","2px solid red");
			$("#inputPassword2").val("");
			return false;
		}
	}
	else if($("#inputPassword1").val().length>0 && $("#inputPassword2").val().length==0){
		
		alert("Conferma la password");
		$("#inputPassword2").css("border","2px solid red");
		return false;
	}	
	

	if($("#inputIban1").val().length>0){ //se ha scritto qualcosa nel campo dell'iban
		
		if($("#inputIban1").val().length==27){//se l'iban è corretto
			$("#inputIban1").css("border","2px solid green");
		}
		else{
			
			alert("Hai inserito un'Iban non valido");
			$("#inputIban1").val("");
			$("#inputIban1").css("border","2px solid red");
			return false;	
		}
	} 
	return true;
		
}