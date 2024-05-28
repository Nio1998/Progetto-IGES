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
	<link rel="stylesheet" href="css/stileLogin.css" type="text/css">
	
	<!-- javascript nostro -->
	<script src="javascript/controlloLogin.js"></script>
	
	<meta charset="UTF-8">

	<title>LOGIN</title>
</head>

<%
	String str1 = (String) request.getAttribute("visita");
	if(str1==null)
	{
		str1 = "servletloginfirst";
		String url = request.getRequestURL() + "";
		response.sendRedirect(response.encodeURL(url.replaceAll("login.jsp",str1)));
		return;
	}
%>

<body>
	<div class="container ml-5 mb-3 mt-2">
		<img src="immagini/logo.png" alt="RAAF-GAMING" style="width:180px; position: static;">
		</div>
		<div class="container d-flex justify-content-center" style="background-color: rgba(254,254,233,0.5); width:50%;" >
		<%
		String str2 = "servletlogin";
		String url2 = response.encodeRedirectURL(str2);
		%>
			<form action="<%=url2 %>" method="POST" id="stileForm" onSubmit="return controlloValori(this);">
						<h4 class="h4 mt-3 ml-2" style="font-family: Acunim Variable Consent;">BENVENUTO GIOCATORE!</h4>
							<%
							String str=(String)request.getAttribute("message");
							if(str!=null){
								%>
								<h5 style="color:red; text-align:center;" name="messaggioerrore">Email/Password errata!</h5>
								<%
							}
							%>
	 	  <div class="input-group mb-3 mt-3 ml-4 w-75">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">
                            <i class='fas fa-user-alt' style='font-size:20px'></i>
                        </span>
                      </div>
                      <input type="text" class="form-control" style="border-radius:0px 5px 5px 0px;" placeholder="Email" name="email">
                  </div>

                  <div class="input-group mb-3 mt-3 ml-4 w-75">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon2">
                            <i class="fa fa-lock" style="font-size:20px"></i>
                        </span>
                      </div>
                      <input type="password" name="password" class="form-control" style="border-radius:0px 5px 5px 0px;" placeholder="************"><br>
                  </div>
                  
         
                  
                  <input type="submit" value="ACCEDI" class="invio ml-5" style="font-family: Eras Demi ITC;
					background-color: #FF6600; width: 60%;font-weight: bold;color:white;border-radius: 15px;"><br>
					<% String reg= response.encodeURL("ServletRegistrazione"); %>
             	  <p class="mr-2">oppure<a href="<%=reg %>" target="_self">&nbsp;REGISTRATI</a></p>
	</form>
	</div>
</body>
</html>