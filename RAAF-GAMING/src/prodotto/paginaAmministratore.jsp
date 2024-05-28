<%@ page language="java" contentType="text/html; charset=ISO-8859-1"
    pageEncoding="ISO-8859-1" import="java.util.ArrayList,acquisto.OrdineBean,prodotto.ProdottoBean,prodotto.FornitoreBean,prodotto.SoftwarehouseBean,prodotto.CategoriaBean"%>
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
	<!-- <script src="javascript/controlloNuovoProd.js"></script> -->
	
	<meta charset="UTF-8">

	<title>Pagina-Amministrazione</title>
</head>
<body>
<%
	String visitato= (String)request.getAttribute("visitato");
	if(visitato==null){
		String url="servletgestioneadmin";
		url= response.encodeURL(url);
		response.sendRedirect(url);
		return;
	}
	
	//ottengo dalla servletGestioneAdmin gli ordini non consegnati e i prodotti esistenti
	ArrayList<ProdottoBean> prodottiEsistenti= (ArrayList<ProdottoBean>)request.getAttribute("prodottiEsistenti");
	ArrayList<String> disponibilita= (ArrayList<String>) request.getAttribute("disponibilita");
	ArrayList<FornitoreBean> fornitori= (ArrayList<FornitoreBean>)request.getAttribute("fornitori");  
	ArrayList<SoftwarehouseBean> sfh= (ArrayList<SoftwarehouseBean>)request.getAttribute("softwarehouse");
	ArrayList<CategoriaBean> categorie= (ArrayList<CategoriaBean>) request.getAttribute("categorie");
%>	
		<div class="container ml-5 mb-3 mt-2">
			<img src="immagini/logo.png" alt="RAAF-GAMING" style="width:180px; position: static;">
			<a class="btn btn-dark mr-3 mt-5 " style="float:right" href="<%=response.encodeURL("servletlogoutadmin")%>" role="button">LogOut</a>
		</div>
		<h4 class="testo" style="font-family: Acunim Variable Consent;text-align:center">Inserisci prodotto:</h4>
		<%
			String message= (String)request.getAttribute("message");
			String messageOk= (String)request.getAttribute("messageok");
			if(message!=null || messageOk!=null){
				if(messageOk!=null){
					
		%>
					<h4 style="color:green; text-align:center;" name="successo"><%=messageOk%></h4>
		<%
				}
				else if(message!=null){
		%>
					<h4 style="color:red; text-align:center;" name="errore"><%=message%></h4>	
		<%
				}
			}
		%>
		<div class="form-row d-flex justify-content-center">
			<label>Nuovo prodotto
				<input type="radio" id="nuovoProdotto"name="sceltaP" onchange="formEsistente()">
			</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<label>Prodotto esistente
				<input type="radio" id="esistenteProdotto"name="sceltaP" onchange="formEsistente()">
			</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</div>
		<div class="container d-flex justify-content-center" style="background-color: rgba(254,254,233,0.5);border-radius:20px;">
		<div id="esistente" style="display:none">
			
			
			<table class="table">
            	<thead class="thead-dark">
                	<tr>
                    	<th scope="col">Nome</th>
                      	<th scope="col">Prezzo</th>
                      	<th scope="col">Quantita' attuale</th>
                      	<th scope="col">Quantita' da rifornire</th>
                      	<th scope="col"></th>
                    </tr>
           		</thead>
              	<tbody>
                	<%
                          //STAMPO TUTTE LE INFO DEI PRODOTTI ESISTENTI
                          int i=0;
                 
                          for(ProdottoBean prodotto : prodottiEsistenti){
                  	%>
                            <tr id="idrow<%=i%>">
                            	<form action="<%=response.encodeURL("servletformprodesistentiadmin")%>" method="POST">
                              		<input type="hidden" name="prod" id="prod" value="<%=prodotto.getCodice_prodotto() %>">
                              		<td><%=prodotto.getNome() %></td>
                              		<td><%=prodotto.getPrezzo() %></td>
                              		<td><%=disponibilita.get(i) %></td>
                              		<td><input type="number" name="quantita" min="1"></td>
                              		<td><button class="btn btn-dark ml-3" style="border-radius:5px;">Rifornisci</button></td>
                                 </form>
                            </tr>
                           
                    <%
                    		i++;
                          }
                    %>
                   
                  </tbody>
                </table>
                
		</div>
		<div id=nuovo style="display:none">
		<form action="<%=response.encodeURL("servletformprodnuovoadmin")%>" method="POST" name="nuovoProdotto" enctype="multipart/form-data" onsubmit="return controlloProdNuovo();">
			<div class="form-group mt-3">
				<label>VIDEOGIOCO FISICO
				<input type="radio" value="videogioco fisico" id="videogiocoRadio"name="sceltaP" onchange="prodottoForm();"></label>&nbsp;&nbsp;&nbsp;
				<label>VIDEOGIOCO DIGITALE
				<input type="radio" value="videogioco digitale" id="videogiocoRadio2"name="sceltaP" onchange="prodottoForm();"></label>&nbsp;&nbsp;&nbsp;
				<label>CONSOLE
				<input type="radio" value="console" id="consoleRadio"name="sceltaP" onchange="prodottoForm()"></label>&nbsp;&nbsp;&nbsp;
				<label>DLC
				<input type="radio" value="dlc" id="dlcRadio"name="sceltaP" onchange="prodottoForm()"></label>&nbsp;&nbsp;&nbsp;
				<label>ABBONAMENTO
				<input type="radio" value="abbonamento" id="abbonamentoRadio"name="sceltaP" onchange="prodottoForm()"></label>&nbsp;&nbsp;&nbsp;
			</div>
			
			<div class="form-row">
						
			 	<div class="form-group mr-3">
				    <label for="nomeP">Nome:</label>
				    <input type="text" class="form-control" id="nomeProdotto" name="nomeP" style="border-radius:10px"  >
			  	</div>
			  	<div class="form-group">
			    	<label for="prezzoP">Prezzo:</label>
			    	<input type="number" class="form-control" id="prezzoProdotto"name="prezzoP" step="0.01" style="border-radius:10px"  >
			  	</div>
			  	<div class="form-group ml-3">
			    	<label for="scontoP">Sconto:</label>
			    	<input type="number" class="form-control" id="scontoProdotto"name="scontoP" min="0" max="99" style="border-radius:10px" >
			  	</div>
	  		</div>
	   		<div class="form-row">
	   					
			  	<div class="form-group mr-3">
			    	<label for="dataP">Data uscita:</label>
			    	<input type="date" class="form-control" id="uscitaProdotto" name="dataP" style="border-radius:10px"  >
			  	</div>
	  			<div class="form-group">
	    			<label for="fornitoreP">Fornitore:</label><br>
	    			<select class="form-select" style="border-radius:8px;height:35px;width:110px" name="fornitoreP"  >
	    			<%
	    				for(FornitoreBean app: fornitori){
	    			%>
	    					<option value="<%=app.getNome()%>"><%=app.getNome()%></option>
	    			<%
	    				}
	    			%>
	    			</select>
	  			</div>
 				<div class="form-group ml-3">
	    			<label for="quantitaP">Quantita da rifornire:</label>
	    			<input type="number" class="form-control" id="quantitaProdottoNew" name="quantitaP" min="1" style="border-radius:10px"  >
	  			</div>
	  		</div>
	  		<div class="form-row">		
				<div class="form-group">
    				<label for="exampleFormControlFile1">Copertina:</label>
    				<input type="file" class="form-control-file" id="copertinaP" name="copertina" accept="image/*">
    			</div>
  			</div>
			
			<div>
			<div id="videogiocoForm" class="form-group" style="display:none">
			<label>Dimensione:<input type="number" name="dimensioni" id="dim" min="1" style="border-radius:7px"  ></label>
			<label>Pegi:<input type="number" name="pegi" id="pegi" min="3" max="18" style="border-radius:7px"  ></label>
			<label for="ncd" id="labelncd">Numero di cd:</label><input type="number" name="ncd" id="ncd" min="1" style="border-radius:7px">
			<label for="chiave" id="labelchiave">Chiave:</label><input type="text" name="chiave" id="chiave" style="border-radius:7px">
			<label>Software House:<br>
				<select class="form-select" style="border-radius:8px;height:35px;width:110px" name="nomesfh"  >
				<%
					for(SoftwarehouseBean app: sfh){
				%>
						<option value="<%=app.getNomesfh()%>"><%=app.getNomesfh()%></option>
				<%
					}
				%>
				</select>
			</label>
			<label>Edizione limitata:<input type="number" name="limitata" id="limitata" min="0" max="1" style="border-radius:7px" ></label>
			<label>
				Categoria:
				<select class="form-select" style="border-radius:8px;height:35px;width:110px" name="categoria">
				<%
					for(CategoriaBean app: categorie){
				%>
						<option value="<%=app.getNome()%>"><%=app.getNome()%></option>
				<%
					}
				%>
				</select>
			</label>
			<button class="btn btn-dark ml-3" type="submit" style="border-radius:5px">Inserisci</button>
			</div>
			<div id="consoleForm" class="form-group" style="display:none">
			<label>Specifiche:<input type="text" name="specifiche" id="specifiche" style="border-radius:7px" ></label>
			<label>Colore:<input type="text" name="colore" id="colore" style="border-radius:7px" ></label>
			<button class="btn btn-dark ml-3" type="submit" style="border-radius:7px">Inserisci</button>
			</div>
			<div id="dlcForm" class="form-group" style="display:none">
			<label>Descrizione:<input type="text" name="descrizione" id="descDLC" style="border-radius:7px" ></label>
			<label>Dimensione:<input type="number" name="dimensioneDlc" id="dimDLC" min="1" style="border-radius:7px" ></label>
			<button class="btn btn-dark ml-3" type="submit" style="border-radius:7px">Inserisci</button>
			</div>
			<div id="abbonamentoForm" class="form-group" style="display:none">
			<label>Codice:<input type="text" name="codice" id="codiceAbb" style="border-radius:7px" ></label>
			<label>Durata:<input type="number" name="durata" id="durAbb" min="1" max="12" style="border-radius:7px" ></label>
			<button class="btn btn-dark ml-3" type="submit" style="border-radius:5px" >Inserisci</button>
			</div>
			</div>
			</form>
			</div>
		</div>
		<script>
		function prodottoForm(){
		if(document.getElementById("videogiocoRadio").checked){
			document.getElementById("videogiocoForm").style.display="block";
			document.getElementById("chiave").style.display="none";
			document.getElementById("labelchiave").style.display="none";
			document.getElementById("ncd").style.display="inline";
			document.getElementById("labelncd").style.display="inline";
			document.getElementById("consoleForm").style.display="none";
			document.getElementById("dlcForm").style.display="none";
			document.getElementById("abbonamentoForm").style.display="none";
		}
		else if(document.getElementById("videogiocoRadio2").checked){
			document.getElementById("videogiocoForm").style.display="block";
			document.getElementById("chiave").style.display="inline";
			document.getElementById("labelchiave").style.display="inline";
			document.getElementById("ncd").style.display="none";
			document.getElementById("labelncd").style.display="none";
			document.getElementById("consoleForm").style.display="none";
			document.getElementById("dlcForm").style.display="none";
			document.getElementById("abbonamentoForm").style.display="none";			
		}
		else if(document.getElementById("consoleRadio").checked){
			document.getElementById("consoleForm").style.display="block";
			document.getElementById("videogiocoForm").style.display="none";
			document.getElementById("dlcForm").style.display="none";
			document.getElementById("abbonamentoForm").style.display="none";
		}
		else if(document.getElementById("dlcRadio").checked){
			document.getElementById("dlcForm").style.display="block";
			document.getElementById("videogiocoForm").style.display="none";
			document.getElementById("consoleForm").style.display="none";
			document.getElementById("abbonamentoForm").style.display="none";
		}
		else if(document.getElementById("abbonamentoRadio").checked){
			document.getElementById("abbonamentoForm").style.display="block";
			document.getElementById("videogiocoForm").style.display="none";
			document.getElementById("dlcForm").style.display="none";
			document.getElementById("consoleForm").style.display="none";
		}
			
		}	
		</script>
		<script>
		function formEsistente(){
		if(document.getElementById("esistenteProdotto").checked){
			document.getElementById("esistente").style.display="block";
			document.getElementById("nuovo").style.display="none";
			document.getElementById("ordine").style.display="none";
			}else if(document.getElementById("nuovoProdotto").checked){
			document.getElementById("nuovo").style.display="block";
			document.getElementById("esistente").style.display="none";
			document.getElementById("ordine").style.display="none";
			}
				
		}
		</script>
		<script>
		function controlloProdNuovo(){
			
			var nome=$("#nomeProdotto").val().length;
			
			if(nome>0 && nome<=50){ //se la lunghezza del nome del prodotto è compatibile con il DB
				$("#nomeProdotto").css("border","2px solid green");
			}
			else{
				$("#nomeProdotto").css("border","2px solid red");
				return false;	
			}

			var prezzo=$("#prezzoProdotto").val().length; 
			
			if(($("#prezzoProdotto").val()<9999) && ($("#prezzoProdotto").val()>0)){
				
				$("#prezzoProdotto").css("border","2px solid green");
			}
			else{
				$("#prezzoProdotto").css("border","2px solid red");	
				return false;
			}
				
			var sconto=$("#scontoProdotto").val().length;
			
			if(($("#scontoProdotto").val()>=0) && ($("#scontoProdotto").val()<=99) && (sconto>0)){
				$("#scontoProdotto").css("border","2px solid green");
			}
			else{
				$("#scontoProdotto").css("border","2px solid red");
				return false;		
			}
					
			var dataUscita= $("#uscitaProdotto").val().length;
			
			if(dataUscita==10){
				$("#uscitaProdotto").css("border","2px solid green");
			}
			else{
				$("#uscitaProdotto").css("border","2px solid red");
				return false;
			}
					
			if($("#quantitaProdottoNew").val()>=1){
				$("#quantitaProdottoNew").css("border","2px solid green");
			}
			else{
				$("#quantitaProdottoNew").css("border","2px solid red");
				return false;
			}

			if($("#copertinaP").get(0).files.length === 0){	//se non ha inserito l'immagine del prodotto
					$("#copertinaP").css("border","2px solid red");
					return false;
			}
			else{
				$("#copertinaP").css("border","2px solid green");
			}
			
			//controllo che tipo di prodotto vuole mettere
			if(document.getElementById("videogiocoRadio").checked){ //se e' un videogioco fisico

				var dim= $("#dim").val().length; 

				if((dim>0) && dim<=3 && $("#dim").val()>=1 &&  ($("#dim").val()<=900)){//se la dimensione del videogioco e' ok
					$("#dim").css("border","2px solid green");
				}
				else{
					$("#dim").css("border","2px solid red");
					return false;
				}
				
				var pegi=$("#pegi").val().length;

				if(($("#pegi").val()>=3) && ($("#pegi").val()<=18) && (pegi>0)){//se la pegi e' ok
					$("#pegi").css("border","2px solid green");
				}
				else{
					$("#pegi").css("border","2px solid red");
					return false;
				}
				
				var ncd= $("#ncd").val().length;

				if(ncd==0){// se non ha inserito ne cd e key
					$("#ncd").css("border","2px solid red");
					return false;
				}
				else if($("#ncd").val()>0){
					$("#ncd").css("border","2px solid green");
				}
				
					
				var limitata= $("#limitata").val().length;
				if(($("#limitata").val()==0 || $("#limitata").val()==1) && limitata>0){
					$("#limitata").css("border","2px solid green");		
				}
				else{
					$("#limitata").css("border","2px solid red");
					return false;
				}

				return true;
				
			}
			else if(document.getElementById("videogiocoRadio2").checked){ //se e' un videogioco fisico
				$("#quantitaProdottoNew").val(1);
			
				var dim= $("#dim").val().length; 

				if((dim>0) && dim<=3 && $("#dim").val()>=1 &&  ($("#dim").val()<=900)){//se la dimensione del videogioco e' ok
					$("#dim").css("border","2px solid green");
				}
				else{
					$("#dim").css("border","2px solid red");
					return false;
				}
					
				var pegi=$("#pegi").val().length;

				if(($("#pegi").val()>=3) && ($("#pegi").val()<=18) && (pegi>0)){//se la pegi e' ok
					$("#pegi").css("border","2px solid green");
					
				}
				else{
					$("#pegi").css("border","2px solid red");
					return false;
				}
				
				var vkey= $("#chiave").val().length;
				
				if(vkey==0){// se non ha inserito key
					$("#chiave").css("border","2px solid red");
					return false;
				}
				else if(vkey>0 && vkey<=14){
					$("#chiave").css("border","2px solid green");
				}
				else{
					$("#chiave").css("border","2px solid red");
					return false;
				}
				
					
				var limitata= $("#limitata").val().length;
				if(($("#limitata").val()==0 || $("#limitata").val()==1) && limitata>0){
					$("#limitata").css("border","2px solid green");		
				}
				else{
					$("#limitata").css("border","2px solid red");
					return false;
				}

				return true;
				
			}
			else if(document.getElementById("consoleRadio").checked){//se e' una console
				var specifica= $("#specifiche").val().length;
				var colore= $("#colore").val().length;
				if(specifica>0 && specifica<=20){
					
					$("#specifiche").css("border","2px solid green");
					
					if(colore>0 && colore<=8){
						$("#colore").css("border","2px solid green");
					}
					else{
						$("#colore").css("border","2px solid red");
						return false;
					}
				}
				else{
					$("#specifiche").css("border","2px solid red");
					return false;					
				}
				return true;
			}
			else if(document.getElementById("dlcRadio").checked){//se e' un dlc
				var descrizione= $("#descDLC").val().length;
				var dimensione= $("#dimDLC").val().length;

				if(descrizione>0 && descrizione<=50){
					
					$("#descDLC").css("border","2px solid green");						
					
					if(dimensione>0 && dimensione<=2){
						$("#dimDLC").css("border","2px solid green");
					}
					else{
						$("#dimDLC").css("border","2px solid red");
						return false;
					}
				}
				else{
					$("#descDLC").css("border","2px solid red");
					return false;
				}
				return true;
			}
			else if(document.getElementById("abbonamentoRadio").checked){//se e' un abbonamento
				var codAbb= $("#codiceAbb").val().length;
				var durAbb= $("#durAbb").val().length;

				if(codAbb>0 && codAbb<=11){
					$("#codiceAbb").css("border","2px solid green");

					if(durAbb>0 && $("#durAbb").val()>=1 && $("#durAbb").val()<=12){
						$("#durAbb").css("border","2px solid green");						
					}
					else{
						$("#durAbb").css("border","2px solid red");
						return false;						
					}
				}
				else{
					$("#codiceAbb").css("border","2px solid red");
					return false;					
				}
				return true;
			}
			
					
		}
				
		</script>

</body>
</html>