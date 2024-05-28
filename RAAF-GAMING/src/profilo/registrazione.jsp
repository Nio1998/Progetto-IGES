<%@ page language="java" contentType="text/html; charset=UTF-8"
    pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!--Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<!--jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<!--Popper JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<!--Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	
	<!-- css loghi -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://kit.fontawesome.com/a076d05399.js"></script>
	
	<!-- css nostro -->
	<link rel="stylesheet" href="css/stileRegistrazione.css" type="text/css">
	
	<!-- javascript nostro -->
	<script src="javascript/controlloRegistrazione.js"></script>
	
	<meta charset="UTF-8">

	<title>REGISTRAZIONE</title>
</head>
<body>
	<%String visitato = (String)request.getAttribute("visitato");
	if(visitato==null)
	{
		String url = "ServletRegistrazione";
		url = response.encodeURL(url);
		response.sendRedirect(url);
		return;
	}
	%>
	
	<div class="row" style="width:70%;">
		<div class="col-md-12 ml-3 mt-3"><!-- immagine raff-gaming -->
			<img src="immagini/logo.png" alt="RAAF-GAMING" class="rounded float-left" style="width:180px; position: static;">
		</div>
	</div>
	
	<div class="container" style="background-color: rgba(254,254,233,0.5); width:50%">
	<%
		String urlreg = "ServletRegistrazione";
	urlreg = response.encodeURL(urlreg);
	%>
		<form action="<%=urlreg %>" method="POST" class="needs-validation" onSubmit="return controlloRegistrazione(this);" novalidate>
			<div class="form-row">
				<div class="col-md-12 mb-3 mt-2 d-flex justify-content-center">
					<p class="h2" style="font-family: Acunim Variable Consent;">INSERISCI I TUOI DATI</p>
				</div>
			</div>
			<%
				String messaggio= (String)request.getAttribute("message");
				if(messaggio!=null){
			%>
					<div class="form-row">
						<div class="col-md-12 mb-1 mt-1 d-flex justify-content-center">
							<p name="messaggioerrore" class="h5" style="color:red;"><%=messaggio %></p>
						</div>
					</div>
			<%
				}
			%>
			<div class="form-row">
		    	<div class="col-md-6 mb-3">
			      <label for="validationCustom01">Nome:</label>
			      <input type="text" name="nome" class="form-control" id="validationCustom01" placeholder="Rocco" required style="width:80%;">
		    	</div>
		    	<div class="col-md-6 mb-3">
		      		<label for="validationCustom02">Cognome:</label>
		      		<input type="text" name="cognome" class="form-control" id="validationCustom02" placeholder="Iuliano" required style="width:80%;">
		    	</div>
		  </div>
		  <div class="form-row">
		  		<div class="col-md-6 mb-3">
		  			<label for="dataNascita">Data di nascita:</label>
			      	<input id="datadinascita" type="date" name="data" class="form-control" id="dataNascita" required style="width:80%;">
		  		</div>
		  		<div class="col-md-6 mb-3">
		  			<label for="validationCustom06">Codice carta di credito:</label>
			      	<input type="text" name="codicecarta" class="form-control" id="validationCustom07" placeholder="**** **** **** ****" required style="width:80%;">
		  		</div>
		  </div>
		   <div class="form-row">
		  		<div class="col-md-6 mb-3">
		  			<label for="dataNascita">Data di scadenza carta:</label>
			      	<input id="data_scadenza" type="date" name="data_scadenza" class="form-control" required style="width:80%;">
		  		</div>
		  		<div class="col-md-6 mb-3">
		  			<label for="validationCustom06">CVV:</label>
			      	<input type="text" name="codice_cvv" class="form-control" id="validationCustom08" placeholder="***" required style="width:80%;">
		  		</div>
		  </div>
		  <div class="form-row">
		  	<div class="col-md-6 mb-3">
		    	<label for="validationCustomUsername">Email</label>
		      	<div class="input-group">
		        	<div class="input-group-prepend">
		          		<span class="input-group-text" id="inputGroupPrepend">@</span>
		        	</div>
		        	<input type="text" name="email" class="form-control" id="validationCustomUsername" placeholder="r.iuliano13@gmail.com" aria-describedby="inputGroupPrepend" required style="width:80%; border-radius:0px 5px 5px 0px;">
		      	</div>
		    </div>
		    <div class="col-md-6 mb-3">
		    	<label for="validationCustom04">Password</label>
		    	<div class="input-group">
		    		<div class="input-group-prepend">
		          		<i class="input-group-text fa fa-lock" style="font-size:24px;" id="inputGroupPrepend"></i>
		        	</div>
			      	<input type="password" name="password" class="form-control was-validated" id="validationCustom04" placeholder="**************" required style="width:80%; border-radius:0px 5px 5px 0px;">
		      	</div>
		    </div>
		  </div>
		  <div class="form-row">
		  	<div class="col-md-12 d-flex justify-content-center mb-3">
		  		<input type="submit" value="REGISTRATI" class="invio" style="font-family: Eras Demi ITC;
					background-color: #FF6600; width: 60%;font-weight: bold;color:white;border-radius: 15px;">
		  	</div>
		  </div>
		  
		</form>
	</div>


</body>
</html>