<%@ page language="java" contentType="text/html; charset=UTF-8"
    pageEncoding="UTF-8" import="acquisto.OrdineBean, java.util.ArrayList,acquisto.SpeditoBean"%>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>RAAF-GAMING</title>
	
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!--Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<!--jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<!--Popper JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<!--Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	
	<!-- Css navbar -->
	<link rel="stylesheet" href="css/StileIndex.css" type="text/css">
	
	<!-- script per il carrello -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src='https://kit.fontawesome.com/a076d05399.js'></script>
	
	<!-- css per la grafica della pagina -->
	<link rel="stylesheet" href="css/stileOrdini.css" type="text/css">

	<style>
		body {
			width: 100%;
			height: 100%;
		    font-family: 'Poppins', sans-serif;
		    background: url(immagini/fallOut.jpg) no-repeat;
			background-attachment: fixed;		
			z-index: -1;	
			-webkit-background-size: cover;	
			-moz-background-size: cover;	
			-o-background-size: cover;	
			background-size: cover;
		}
	</style>
	
</head>
<body>

<%
	String visitato= (String)request.getAttribute("visitato");
	if(visitato==null){
		String url="servletordini";
		url= response.encodeURL(url);
		response.sendRedirect(url);
		return;
	}
	
	ArrayList<OrdineBean> ordiniUtente= (ArrayList<OrdineBean>)request.getAttribute("listaOrdini");
%>

<%@include file="../it/unisa/utility/navbar.jsp" %>

<div class="wrapper rounded" style="min-height:100vh;">

  
	<p class="text-center font-weight-bold" id="peppino" style="color:#FF9900; font-size:20px;">I TUOI ORDINI</p>
  		         
    <div class="d-flex justify-content-between align-items-center mt-3">
    	<p style="widht:75%; color:white; border-bottom: 3px solid; border-color: #FF9900;">History</p>
    </div>
    <%
			if(ordiniUtente.size()==0){
   	%>
   				<p style="color:white; text-align:center;">NON HAI EFFETTUATO ANCORA NESSUN ORDINE</p>
   	<%
			}
			else{
				ArrayList<SpeditoBean> spedizioni= (ArrayList<SpeditoBean>) request.getAttribute("spedizioni");
   	%>
			    <div class="table-responsive mt-3">
			    	
			        <table class="table table-dark table-borderless table-hover">
			            <thead>
			                <tr>
			                    <th scope="col" style="color:#00FF7F"><i class='fas fa-shopping-basket' style='font-size:20px; display:inline;'></i> ORDER ID</th>
			                    <th scope="col" style="color:#00FF7F;"><i class="fa fa-home" style="font-size:20px; display:inline;"></i>INDIRIZZO CONSEGNA</th>
			                    <th scope="col" style="color:#00FF7F"><i class='far fa-credit-card' style='font-size:20px; display:inline;'></i> CARTA CREDITO</th>
			                    <th scope="col" style="color:#00FF7F"><i class='fas fa-calendar' style='font-size:20px; display:inline;'></i> DATA ACQUISTO</th>
			                    <th scope="col" style="color:#00FF7F"><i class="fa fa-calendar" style="font-size:20px; display:inline;"></i> DATA CONSEGNA</th>
			                    <th scope="col" style="color:#00FF7F"><i class='fas fa-money-bill-wave' style='font-size:20px; display:inline;'></i>TOTALE</th>
			                </tr>
			            </thead>
			            <tbody>
		            	<%
		            		int indice=0;
							for(OrdineBean app : ordiniUtente){
								SpeditoBean spedizioneApp= spedizioni.get(indice);
		            	%>
			                <tr>
			                    <td scope="row"> <%=app.getCodice()%> </td>
			                    <td><div style="max-width:100%; overflow: auto; white-space: nowrap;"><%=app.getIndirizzo_di_consegna()%></div></td>
			                    <td><%="****"+app.getMetodo_di_pagamento().substring(12,16)%></td>
			                    <td class="text-muted"><%=app.getData_acquisto().toString()%></td>
			                    <%
			                    	if(spedizioneApp==null){
			                    %>
			                    		<td>IN FASE DI ELABORAZIONE</td>
			                    <%
			                    	}
			                    	else{
			                    %>
			                    		<td><%=spedizioneApp.getData_consegna().toString()%></td>
			                    <%
			                    	}
			                    	indice++;
			                    %>
			                    <td class="d-flex justify-content-end align-items-center"><%=app.getPrezzo_totale()%>&euro;</td>
			                </tr>
			                <%
							}
			                %>
			            </tbody>
			        </table>
			    </div>
    <%
			}
    %>
</div>

<%@include file="../it/unisa/utility/footer.jsp" %>

</body>
</html>