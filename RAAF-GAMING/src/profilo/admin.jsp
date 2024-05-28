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
	
	<!-- css nostri -->
	<link rel="stylesheet" href="css/stileAdmin.css" type="text/css">
	
	<!-- javascript nostro -->
	<script src="javascript/controlloLogin.js"></script>
	
	<meta charset="UTF-8">

	<title>LOGIN-ADMIN</title>
</head>
<body>
<%
	String visitato= (String) request.getAttribute("visitato");
	if(visitato==null){
		String str="servletaccessoadmin";
		String url = request.getRequestURL() + "";
		response.sendRedirect(response.encodeURL(url.replaceAll("admin.jsp",str)));
		return;
	}
	
%>
		<div class="container ml-5 mb-3 mt-2">
		<img src="immagini/logo.png" alt="RAAF-GAMING" style="width:180px; position: static;">
		</div>
		<div class="container d-flex justify-content-center" style="background-color: rgba(254,254,233,0.5); width:40%;" >
			<form action="<%= response.encodeURL("servletAdmin") %>" method="POST" id="stileForm" onSubmit="return controlloValori(this);">
						<h3 class="testo mt-3 ml-2" style="font-family: Acunim Variable Consent;">Raaf-Gaming.it</h3>
							<%
							String str=(String)request.getAttribute("message");
							if(str!=null){
								%>
								<h5 name="messaggioerrore" style="color:red; text-align:center;">Email/Password errata!</h5>
								<%
							}
							%>
	  <div class="form-group mt-4">
	    <label for="exampleInputEmail1"></label>
	    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email" name="email">
	  </div>
	  <div class="form-group">
	    <label for="exampleInputPassword1"></label>
	    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="************" name="password">
	  </div>
	  
	  <button type="submit" class="btn btn-dark mb-4">Accedi</button>
	</form>
	</div>
</body>
</html>