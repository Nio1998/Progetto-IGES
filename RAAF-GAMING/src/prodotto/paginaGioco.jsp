<%@ page language="java" contentType="text/html; charset=UTF-8"
    pageEncoding="ISO-8859-1" import="prodotto.AbbonamentoBean,prodotto.DlcBean,prodotto.ConsoleBean"%>

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
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"> </script><!-- css loghi -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<!-- css mio -->
<link rel="stylesheet" href="css/StileIndex.css" type="text/css">
<link rel="stylesheet" href="css/stileGioco.css" type="text/css">

<title>RAAF-GAMING</title>
</head>
<body>
<%
String str = (String) request.getAttribute("visitato");
if(str==null)
{
	str = "servletprodotto";
	String url = request.getRequestURL() + "";
	response.sendRedirect(response.encodeURL(url.replaceAll("paginaGioco.jsp",str)));
	return;
}

%>
<%@include file="../it/unisa/utility/navbar.jsp" %>
<!--inizio descrizione-->
<div class="container d-flex justify-content-center" style="background-color:rgba(230,230,230,0.5); min-height:100vh; width:100%;">
	<%
		String message= (String)request.getAttribute("message");
		if(message!=null){
	%>
			<h2 style="color:red; style="text-align:center;"><%=message%></h2>
	<%
		}
		else{
			ProdottoBean prod = (ProdottoBean) request.getAttribute("Prodotto");
			String tipo = (String) request.getAttribute("tipo");
			Object obj = request.getAttribute(tipo);
	%>
	<div class="row w-100" style="width:100%;">
		<div class="col-md-6 mt-4 mb-2">
		
				<img src="servletcard?id=<%=prod.getCodice_prodotto() %>" style="border-radius:15px;height:75%;width:75%;">

		</div>
		<div class="col-md-6">
			<div class="container ml-0 mt-4 mr-5" style="background-color:rgba(240,240,230,0.8);height:50%; width:100%; border-radius:15px;overflow:auto;">
						<div class="row justify-content-md-center">
							<div class="row w-110 pl-1" style="background-color:rgb(35,35,35);width:112%">
										<div class="col-md-6">
												<h2 class="nome pl-3 pt-1" style="color:white; font-family:Impact; text-transform:uppercase; font-weight:bold; "><%=prod.getNome() %></h2>
										</div>

										</div>
										
										<div class="row">
											<div class="col-xl-12 ml-0 pr-2">
													<%if(tipo=="videogioco")
													{
														VideogiocoBean bean = (VideogiocoBean) obj;
														%><h6>Edizione limitata: <%if(bean.getEdizione_limitata()){%> SI<%} else{%> NO<%} %>,&nbsp;
														Data Uscita: <%=prod.getData_uscita() %>,&nbsp;
														Dimensione: <%=bean.getDimensione() %>, &nbsp;
														Tipo: <%if(bean.getVkey()==null){%>FISICO con <%=bean.getNcd()%>cd.<%} else {%>DIGITALE<%} %>,&nbsp;
														Software House: <%=bean.getSoftware_house() %>&nbsp;
														Sconto: <%=prod.getSconto() %>%&nbsp;
														PEGI: <%=bean.getPegi() %>.</h6>
														<%
														}
													else if(tipo=="console")
													{
														ConsoleBean bean = (ConsoleBean) obj;
														%><h6>Specifica: <%=bean.getSpecifica() %>,&nbsp;
														Data Uscita: <%=prod.getData_uscita() %>,&nbsp;
														Colore: <%=bean.getColore() %>&nbsp;
														Sconto: <%=prod.getSconto() %>%.</h6>
														<%
													} 
													else if(tipo=="abbonamento")
													{
														AbbonamentoBean bean = (AbbonamentoBean) obj;
														%><h6>Durata Abbonamento: <%=bean.getDurata_abbonamento()%> mesi,&nbsp;
														Data Uscita: <%=prod.getData_uscita() %>,&nbsp;
														Sconto: <%=prod.getSconto() %>%</h6>
														<%
													} 
													else if(tipo=="dlc")
													{
														DlcBean bean = (DlcBean) obj;
														%><h6>Descrizione: <%=bean.getDescrizione()%>,&nbsp;
														Data Uscita: <%=prod.getData_uscita() %>,&nbsp;
														Dimensione: <%=bean.getDimensione() %>, &nbsp;
														Sconto: <%=prod.getSconto() %>%</h6>
														<%
													} 
														%>
											</div>
										</div>
										<div class="row">
												<%
													double prezzoBase= prod.getPrezzo();
				            						int sconto= prod.getSconto();
				            						if(sconto>0){
					            						double psconto= (prezzoBase*sconto)/100;
					            						double prezzoScontato= prezzoBase-psconto;
												%>
														<h4 class="col-md-12 pt-5" style="position:relative; bottom:0; left:0;"><span style="color:black; text-decoration:line-through;"><%=prod.getPrezzo()%>&euro;</span>&nbsp;&nbsp;<span style="font-weight:bold; color:red;"><%=String.format("%.2f",prezzoScontato)%>&euro;</span>
												<%
				            						}
				            						else{
												%>
														<h4 class="col-md-12 pt-5" style="position:relative; bottom:0; left:0;"><%=String.format("%.2f",prod.getPrezzo())%>&euro;
												<%
				            						}
												%>
												<%
												String presentein = (String) request.getAttribute("presente");
												if(presentein!=null)
												{
													%><button style="border:none; outline:none; background-color:rgba(230,230,230,0);" onclick="aggiungiCarrello();"><i class='fas fa-shopping-cart pl-2' style='font-size:27px; color:black;'></i></button></h4>
											<%	}
												
												else
												{
													%><button style="border:none; outline:none; background-color:rgba(230,230,230,0);" onclick="nonpuoiacquistare();"><i class='fas fa-shopping-cart pl-2' style='font-size:27px; color:black;'></i></button></h4>
												<%}
												%>
												
										</div>
						
						</div>
			</div>
			<div class="container" style="height:300px;">
				<h4 style="color:black; font-family:Impact; text-transform:uppercase;">Recensioni</h4>

				<div style="width: 100%; height: 100px; overflow-y: scroll; background-color:rgba(240,240,230,0.8)" id="stampe">
				<%ArrayList<String> lista = (ArrayList<String>) request.getAttribute("clienti"); 
				for(String recensione : lista)
				{
				%><%=recensione%><br>
				<%
				}
				%>
				</div>
				<div class="col-md-6" >
												<span class="star-rating pr-1"><!-- modifica -->
												  <input type="radio" name="rating" value="1"  id="stella1"><i></i>
												  <input type="radio" name="rating" value="2"  id="stella2"><i></i>
												  <input type="radio" name="rating" value="3"  id="stella3"><i></i>
												  <input type="radio" name="rating" value="4"  id="stella4"><i></i>
												  <input type="radio" name="rating" value="5"  id="stella5"><i></i>
												  <input type="radio" name="rating" value="6"  id="stella6"><i></i>
												  <input type="radio" name="rating" value="7"  id="stella7"><i></i>
												  <input type="radio" name="rating" value="8"  id="stella8"><i></i>
												  <input type="radio" name="rating" value="9"  id="stella9"><i></i>
												  <input type="radio" name="rating" value="10" id="stella10"><i></i>
												 </span>
				</div>
				
				<div class="form-group">
				
					<textarea class="form-control" id="commento" rows="3" placeholder="inserisci una recensione..." style="resize:none"></textarea>
				<%Boolean loggato = (Boolean)request.getAttribute("loggato");%>
				<%if(loggato == true){ %>
				<button type="button" class="btn btn-dark" onclick="recensione();">Conferma</button><%} else{%>
				<button type="button" class="btn btn-dark" onclick="nonpuoirecensire();">Conferma</button><%} %>
				</div>
				
				
				
				
				
				
				
			</div>
		</div>
	</div>
	
		<!-- row -->
		<script>
		
		var flag = 0;
		
		function aggiungiCarrello()
		{
		
			var x = {id: <%=prod.getCodice_prodotto()%>};
			
			var jisono = JSON.stringify(x);
			
			$.ajax({
				
				type: "POST",
				url: "<%=response.encodeURL("servletaggiungicarrello")%>",
				contentType: "application/json",//tipo di dato che invio
				dataType:"text",//tipo di dati che mi aspetto dal server
				data: encodeURIComponent(jisono),
				async: true,
				success: function (data){
					var obj = JSON.parse(data);
					alert(obj.message);
					$("#sostituisciCarrello").removeClass("fas fa-shopping-cart");
					$("#sostituisciCarrello").addClass( "fa fa-cart-arrow-down");
					},
				error: function(){alert("lol");}
		});
			
		}
		
		function recensione()
		{
			
			var s;
			if(document.getElementById("stella1").checked==true)
			{
				s="stella1";
			}
			else if(document.getElementById("stella2").checked==true) s="stella2";
			else if(document.getElementById("stella3").checked==true) s="stella3";
			else if(document.getElementById("stella4").checked==true) s="stella4";
			else if(document.getElementById("stella5").checked==true) s="stella5";
			else if(document.getElementById("stella6").checked==true) s="stella6";
			else if(document.getElementById("stella7").checked==true) s="stella7";
			else if(document.getElementById("stella8").checked==true) s="stella8";
			else if(document.getElementById("stella9").checked==true) s="stella9";
			else if(document.getElementById("stella10").checked==true) s="stella10";
			else
			{
				alert("non hai inserito il voto");
				return;
			}
			//--------------------------------------------------------------------------------------------------//
			if(document.getElementById("commento").value==null || document.getElementById("commento").value=="")
			{
				alert("commento non inserito");
				return;
			}
			//--------------------------------------------------------------------------------------------------//
			if(document.getElementById("commento").value!=null && document.getElementById("commento").value!="")	
			{
			var x = {voto: document.getElementById(s).value, id:<%=prod.getCodice_prodotto()%>, commento: document.getElementById("commento").value };
			var jisono = JSON.stringify(x);
			
		$.ajax({
				
				type: "POST",
				url: "<%=response.encodeURL("servletrecensione")%>",
				contentType: "application/json",//tipo di dato che invio
				dataType:"text",//tipo di dati che mi aspetto dal server
				data: encodeURIComponent(jisono),
				async: true,
				success: function (data){
					var obj = JSON.parse(data);
					alert(obj.recensione);
					<%String nomeCliente = (String) request.getAttribute("nomeCliente");%>
					var y = "<%=nomeCliente%>";
					
					
					var str = obj.recensione;

					if(flag==0)
					{
						if(str.localeCompare("Hai gia effettuato una recensione per questo prodotto")==0)
						{
							flag=1;
						}
						if(flag==0){
							$("#stampe").append(y+' voto:'+x.voto+' :'+x.commento+' <br>');
							flag=1;
						}
						
					}
					
					
					
					},
				error: function(){alert("lol");}
		});
			}
		}
		
		function nonpuoiacquistare()
		{
			alert("Prodotto non disponibile in magazzino");
		}
		
		function nonpuoirecensire()
		{
			alert("Effettua l'accesso per recensire!");
		}
		</script>
		<!-- fine -->
	<%
		}
	%>
	
</div>

<!--fine descrizione-->
<%@include file="../it/unisa/utility/footer.jsp" %>
</body>
</html>