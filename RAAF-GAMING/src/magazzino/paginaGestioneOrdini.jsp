<%@ page language="java" contentType="text/html; charset=ISO-8859-1"
    pageEncoding="ISO-8859-1" import="acquisto.OrdineBean, java.util.ArrayList, acquisto.CorriereEspressoBean"%>
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
	
	<meta charset="UTF-8">
<title>Ordini da gestire</title>
</head>
<body>

<%
	String visitato= (String)request.getAttribute("visitato");
	if(visitato==null){
		String url="ServletGestioneOrdiniAdmin";
		url= response.encodeURL(url);
		response.sendRedirect(url);
		return;
	}
	
	ArrayList<OrdineBean> ordiniNonSpediti= (ArrayList<OrdineBean>)request.getAttribute("ordiniNonSpediti");
	ArrayList<CorriereEspressoBean> corrieri= (ArrayList<CorriereEspressoBean>)request.getAttribute("corrieri");
%>
	<div class="container ml-5 mb-3 mt-2">
			<img src="immagini/logo.png" alt="RAAF-GAMING" style="width:180px; position: static;">
			<a class="btn btn-dark mr-3 mt-5 " style="float:right" href="<%=response.encodeURL("servletlogoutadmin")%>" role="button">LogOut</a>
		</div>
		<h4 class="testo" style="font-family: Acunim Variable Consent;text-align:center">Crea spedizione:</h4>
		<%
			String message= (String)request.getAttribute("message");
			String messageOk= (String)request.getAttribute("messageok");
			if(message!=null || messageOk!=null){
				if(messageOk!=null){
					
		%>
					<h4 id="peppe"style="color:green; text-align:center;"><%=messageOk%></h4>
		<%
				}
				 if(message!=null){
		%>
					<h4 style="color:red; text-align:center;"><%=message%></h4>	
		<%
				}
			}
		%>
		
		
		<div class="form-row d-flex justify-content-center">
		<label>Crea spedizione
			<input type="radio" id="spedizioneProdotto"name="sceltaP" onchange="spedizioneProdotto()"></label>
			</div>
		<div class="container d-flex justify-content-center" style="background-color: rgba(254,254,233,0.5);border-radius:20px;">
		<div id="ordine" style="display:none">
		<table class="table">
                  <thead class="thead-dark">
                    <tr>
                      <th scope="col">Codice ordine:</th>
                      <th scope="col">Email utente:</th>
                      <th scope="col">Indirizzo utente</th>
                      <th scope="col">Corriere espresso:</th>
                      <th scope="col">Data consegna:</th>
                      <th scope="col">Conferma:</th>
                      <th scope="col"></th>
                    </tr>
                  </thead>
                  <tbody>
                  <%
                          //STAMPO TUTTE LE INFO DEGLI ORDINI ESISTENTI
                          int i=0;
                  
                          for(OrdineBean app : ordiniNonSpediti){
                      %>
                            <tr>
                            <form action="<%=response.encodeURL("servletformordiniadmin")%>" method="POST" onSubmit="return controlloOrdini('idata<%=i%>');">
                              <td scope="row"><input type="hidden" name="numeroOrdine" id="numeroOrdine" value="<%=app.getCodice() %>"><%=app.getCodice() %></td>
                              <td><%=app.getCliente() %></td>
                              <td><%=app.getIndirizzo_di_consegna() %></td>
                              <td><select style="border-radius:8px;height:35px;width:110px" name="corriere"  >
							  	<%
							    	for(CorriereEspressoBean corr: corrieri){
							    %>
							    		<option value="<%=corr.getNome()%>"><%=corr.getNome()%></option>
							    <%
							    	}
							    %>
								</select></td>
								<td>
								 <input type="date" class="form-control idata<%=i%>" id="consegnaOrdine" name="consegnaO" style="border-radius:10px" >
								</td>
                              <td>
	     						<button class="btn btn-dark ml-3" style="border-radius:5px;">Conferma</button>
	     						</td>
	     						</form>
                            </tr>
                            
                    <%
                        i++;  }
                    %>
                    
                  </tbody>
                </table>
		<script>
		function spedizioneProdotto(){
			if(document.getElementById("spedizioneProdotto").checked){
			document.getElementById("ordine").style.display="block";
			}
		}
		
		function controlloOrdini(x){
			var elements = document.getElementsByClassName(x);
			const dataAttuale= new Date();
			const dataForm= new Date($("."+x).val());
			
			if(dataForm.getFullYear()==dataAttuale.getFullYear()){
				
				if((dataForm.getMonth()+1)>(dataAttuale.getMonth()+1)){
					$("."+x).css("border","2px solid green");
					return true;
				}
				if((dataForm.getMonth()+1)==(dataAttuale.getMonth()+1)){
					if(dataForm.getDate()>=dataAttuale.getDate()){
						$("."+x).css("border","2px solid green");
						return true;				
					}
					else{
						$("."+x).css("border","2px solid red");
						return false;	
					}
				}
				$("."+x).css("border","2px solid red");
				return false;
			}
			else if(dataForm.getFullYear()>dataAttuale.getFullYear())
			{
				
				$("."+x).css("border","2px solid green");
						return true;
			}
			else{
					$("."+x).css("border","2px solid red");
					return false;
				}
		}
		
		</script>
		
</body>
</html>