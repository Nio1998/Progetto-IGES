<%@ page language="java" contentType="text/html; charset=UTF-8"
    pageEncoding="UTF-8" import="profilo.ClienteBean,profilo.CartaFedeltaBean"%>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!--Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<!--jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<!--Popper JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<!--Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	
	<!-- javascript nostro -->
	<script src="javascript/controlloCambio.js"></script>
	
	<!-- Css navbar -->
	<link rel="stylesheet" href="css/StileIndex.css" type="text/css">
	
	<!-- script per il carrello -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src='https://kit.fontawesome.com/a076d05399.js'></script>
	
	<title>PROFILO</title>
	<style>
		body{
			width: 100%;
			height: 100%;	
			background: url(immagini/sky.jpg) no-repeat;
			background-attachment: fixed;		
			z-index: -1;	
			-webkit-background-size: cover;	
			-moz-background-size: cover;	
			-o-background-size: cover;	
			background-size: cover;
			color:white;
		}
	</style>
</head>
<body>
<%
	//ottengo il campo visitato per vedere se son passato nella servletAccessoProfilo
	String visitato= (String)request.getAttribute("visitato");
	if(visitato==null){
		//se è null vado in servletAccessoProfilo
		
		String url="servletaccessoprofilo";
		url= response.encodeURL(url);
		response.sendRedirect(url);
		return;
	}
	
	ClienteBean utente= (ClienteBean)request.getAttribute("datiUtenteCarta");
	CartaFedeltaBean carta= (CartaFedeltaBean)request.getAttribute("puntiCarta");
	String str = "servletprofilo";
	String url = response.encodeRedirectURL(str); //faccio l'url rewriting per non perdere la sessione
%>

<%@include file="../it/unisa/utility/navbar.jsp" %>
<div class="container" style="min-height:100vh;">
	<div class="row mt-3">
		<div class="col-md-6">
			<div class="card text-white" style=" border-style:none; border-radius:20px;">
				<img src="immagini/cartafedelta.png" class="rounded float-left card-img" alt="carta fedelta" style="height:350px;">
				<div class="card-img-overlay">
					<br><br><br>
					<ul class="card-text list-inline font-weight-bold"><li class="list-inline-item text-warning">EMAIL:</li>  <li class="list-inline-item"><%=utente.getEmail() %></li></ul>
    				<ul class="card-text list-inline font-weight-bold"><li class="list-inline-item text-warning">NOME:</li>  <li class="list-inline-item"><%=utente.getNome() %></li></ul>
    				<ul class="card-text list-inline font-weight-bold"><li class="list-inline-item text-warning">COGNOME:</li>  <li class="list-inline-item"><%=utente.getCognome() %></li></ul>
    				<ul class="card-text list-inline font-weight-bold"><li class="list-inline-item text-warning">CODICE CARTA DI CREDITO:</li>  <li name="codicecartaselenium" class="list-inline-item" id="cartaAggiornata"><%="****"+utente.getCartadicredito().substring(12,16) %></li></ul>
    				<ul class="card-text list-inline font-weight-bold"><li class="list-inline-item text-warning">CODICE CARTA:</li>  <li class="list-inline-item"><%=utente.getCarta_fedelta() %></li></ul>
    				<ul class="card-text list-inline font-weight-bold"><li class="list-inline-item text-warning">PUNTI CARTA:</li>  <li class="list-inline-item"><%=carta.getPunti() %></li></ul>
				</div>
				
			</div>
		</div>
		<div class="col-md-6">
			
				<div class="form-row">
					<div class="form-group col-md-12">
						<p class="h2 text-center">Vuoi cambiare password e/o IBAN?</p>
						<p id="notifica" style="visibility:hidden; font-weight:bold; background-color:white;"></p>
					</div>
				</div>
		  		<div class="form-row">
		    		<div class="form-group col-md-6">
				      <label for="inputPassword1">Nuova password</label>
				      <input type="password" name="password1" class="form-control w-75" id="inputPassword1" placeholder="*******">
		    		</div>
		  			<div class="form-group col-md-6">
				      <label for="inputPassword2">Conferma password</label>
				      <input type="password" name="password2" class="form-control w-75" id="inputPassword2" placeholder="******">
		  			</div>
		  		</div>
		  		<div class="form-row">
		  			<div class="form-group col-md-4">
		  				<label for="inputCarta1">Numero carta</label>
		  				<input type="text" name="numeroCarta" class="form-control w-50" id="inputCarta1" placeholder="xxxxxxxxxxxxxxxx">
		  			</div>
		  			<div class="form-group col-md-4">
		  				<label for="inputCarta2">CVV</label>
		  				<input type="text" name="cvvCarta" class="form-control w-50" id="inputCarta2" placeholder="xxx">
		  			</div>
		  			<div class="form-group col-md-4">
		  				<label for="inputCarta3">Data scadenza</label>
		  				<input type="date" name="cvvCarta" class="form-control w-50" id="inputCarta3">
		  			</div>
		  		</div>
		  		<div class="form-row">
		  			<div class="col-md-12">
		  				<input type="submit" class="btn btn-primary" value="INVIA" onclick="aggiornamentoCredenziali();">
		  			</div>
		  		</div>
			
		</div>
	</div>
	
	
</div>

<%@include file="../it/unisa/utility/footer.jsp" %>

<%
	String urlj= response.encodeURL("servletprofilo");
%>
<script>
	
	function aggiornamentoCredenziali(){
		var pass1= document.getElementById("inputPassword1");
		var pass2= document.getElementById("inputPassword2");
		var codiceCarta= document.getElementById("inputCarta1");
		var cvv= document.getElementById("inputCarta2");
		var data= document.getElementById("inputCarta3");
		if(controlloCambioCredenziali(pass1,pass2,codiceCarta,cvv,data)){
			var o= {
					passwordNuova: $("#inputPassword1").val(),
					cartaNuova: $("#inputCarta1").val(), //qui bisogna mettere i campi giusti
					codiceNuovo: $("#inputCarta2").val(),
					dataScadNuova: $("#inputCarta3").val()
			};
			var parametriJson= JSON.stringify(o);
			
			$.ajax({
					
					type: "POST",
					url: "<%=urlj %>",
					contentType: "application/json",//tipo di dato che invio
					dataType:"text",//tipo di dati che mi aspetto dal server
					data: encodeURIComponent(parametriJson),
					async: true,
					success:function(data){//funzione invocata in caso di successo della richiesta 
								var risposta= JSON.parse(data);
								if(risposta.errorMessage!=null){
									$("#notifica").css("visibility","visible");
									$("#notifica").html(""+risposta.errorMessage);
									$("#notifica").css("color","red");
									$("#notifica").css("text-align","center");
								}
								else{
									if(risposta.password==true){
										$("#notifica").css("visibility","visible");
										$("#notifica").html("PASSWORD MODIFICATA!");
										$("#notifica").css("color","green");
										$("#notifica").css("text-align","center");
										
									}
									if(risposta.carta!=null)
										$("#cartaAggiornata").html(""+risposta.carta); 
								}
							}
			});
		}
	}
	
	
	
	function controlloCambioCredenziali(pass1,pass2,codiceCarta,cvv,data){
		
		
		if(pass1.value.length>0 && pass2.value.length>0){ //se ha scritto qualcosa nel campo nuova password
		
			if(pass1.value.length>=8){//se la password è valida
				pass1.style.border="2px solid green";
				if(pass1.value==pass2.value){ //lunghezza conferma password corretta e coincide con quella di prima

					pass2.style.border="2px solid green";
				} 
				else if(pass1.value!=pass2.value){
					alert("Le password non corrispondono");
					pass2.value="";
					pass2.style.border="2px solid red";
					return false;
				}
			}
			else{
				alert("password non valida");
			}
		}
		else if(pass1.value.length>0 && pass2.value.length==0){
			alert("Conferma la password");
			pass2.style.border="2px solid red";
			return false;
		}
		else if(pass1.value.length==0 && pass2.value.length>0){
			alert("Conferma la password");
			pass1.style.border="2px solid red";
			return false;
		}	

		
		const d2= new Date(data.value);
		const dataAttuale= new Date();
		
		if(codiceCarta.value.length>0 && cvv.value.length>0 && (d2.getFullYear()!=NaN && data.value.length==10)){ //se ha scritto qualcosa per modificare la carta
			
			if(codiceCarta.value.length==16){//se il codice dell carta è corretto
				codiceCarta.style.border="2px solid green";
			}
			else{
				alert("Hai inserito una carta non valida");
				codiceCarta.value="";
				codiceCarta.style.border="2px solid red";
				return false;	
			}
			
			//CONTROLLO IL CVV
			
			if(cvv.value.length==3){//se il cvv è corretto
				cvv.style.border="2px solid green";
			}
			else{
				alert("Hai inserito un cvv non valido");
				cvv.value="";
				cvv.style.border="2px solid red";
				return false;	
			}
			
			
			//CONTROLLO LA DATA DI SCADENZA
			
			
			if(d2.getFullYear()==dataAttuale.getFullYear()){ //se l'anno di scadenza è uguale all'anno attuale
				if((d2.getMonth()+1)==(dataAttuale.getMonth()+1)){ //se il mese della data di scadenza è uguale a quella attuale
					
					if(d2.getDate()>dataAttuale.getDate()){//se il giorno della data di scadenza è maggiore rispetto a quello di oggi OK
						data.style.border="2px solid green";
					}
					else{//il giorno della scadenza coincide al giorno attuale o prima NO
						alert("La carta di credito e' scaduta");
						data.style.border="2px solid red";
						return false;
						
					}
				}
				else if((d2.getMonth()+1)>(dataAttuale.getMonth()+1)){//il mese della data di scadenza è maggiore al mese attuale OK
					data.style.border="2px solid green";
				}
				else{//il mese della data di scadenza è precendete a quello attuale NO
					alert("La carta di credito e' scaduta");
					data.style.border="2px solid red";
					return false;
				}
			}
			else if(d2.getFullYear()>dataAttuale.getFullYear()){//se l'anno di scadenza e' maggiore rispetto a quello attuale OK
				data.style.border="2px solid green";
			}
			else{//se l'anno di scadenza è minore rispetto all'anno attuale NO
				alert("La carta di credito e' scaduta");
				data.style.border="2px solid red";
				return false;
			}
			
		}
		else if(codiceCarta.value.length>0 && (cvv.value.length==0 || data.value.length==0)){ //se inserisco solo il codice della carta NO
			alert("Dati carta mancante");
			cvv.style.border="2px solid red";
			data.style.border="2px solid red";
			return false;
		}
		else if(cvv.value.length>0 && (codiceCarta.value.length==0 || data.value.length==0)){ //se inserisco solo il CVV della carta NO
			alert("Dati carta mancante");
			codiceCarta.style.border="2px solid red";
			data.style.border="2px solid red";
			return false;
		}
		else if((d2.getFullYear()!=NaN && data.value.length==10) && (codiceCarta.value.length==0 || cvv.value.length==0)){ //se ho inserito solo la data di scadenza NO
			alert("Dati carta mancante");
			codiceCarta.style.border="2px solid red";
			cvv.style.border="2px solid red";
			return false;
		}
		 
		return true;
			
	}	
</script>

</body>
</html>