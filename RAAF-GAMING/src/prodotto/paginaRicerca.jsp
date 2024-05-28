<%@ page language="java" contentType="text/html; charset=UTF-8"
    pageEncoding="ISO-8859-1"%>

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
<link rel="stylesheet" href="css/stileCard.css" type="text/css" >


<!-- script per il carrello -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src='https://kit.fontawesome.com/a076d05399.js'></script>





<title>RAAF-GAMING</title>
</head>
<body>
<%
String str = (String) request.getAttribute("visitato");
if(str==null)
{
	str = "servletricerca";
	String url = request.getRequestURL() + "";
	response.sendRedirect(response.encodeURL(url.replaceAll("paginaRicerca.jsp",str)));
	return;
}

%>

<%@include file="../it/unisa/utility/navbar.jsp" %>
<!--inizio descrizione-->

<div class="container pl-5" style="min-height:100vh;">

<h3 class="ml-3 mt-5 mb-0" style="color:white;">Risultato Ricerca</h3>

	<div class="row" style="width:100%">
		<div class="col-md-12 inline-block">
			<ul class="cards ml-1 mr-1 pr-5">
			
			<!-- inizio ciclo -->
			<% ArrayList<ProdottoBean> prodric = (ArrayList<ProdottoBean>)request.getAttribute("prodotti"); 
			 for(int l=0;l<prodric.size();l++){
				
			 %>
	  			<li style="height:250px; max-width:250px;" name="prodottoCard">
	  			
	    			<a href="<%= response.encodeURL("servletprodotto?id="+prodric.get(l).getCodice_prodotto()) %>" class="card">
		      			<img src="servletcard?id=<%=prodric.get(l).getCodice_prodotto()%>" class="card__image" alt="" />
		      			<div class="card__overlay">
		        			<div class="card__header">                  
		          				<i class='fas fa-shopping-cart' style='font-size:27px; color:black;'></i>
		          				<div class="card__header-text">
		            				<h3 class="card__title" name="nomeProdotto<%=l%>"><%=prodric.get(l).getNome()%></h3>            
		            				<%
		            					if(prodric.get(l).getSconto()==0){
		            				%>           
		            						<span class="card__status" style="color:red;"><%=String.format("%.2f",prodric.get(l).getPrezzo())%>&euro;</span>
		            				<%
		            					}
		            					else{
		            						double prezzoBase= prodric.get(l).getPrezzo();
		            						int sconto= prodric.get(l).getSconto();
		            						double psconto= (prezzoBase*sconto)/100;
		            						double prezzoScontato= prezzoBase-psconto;
		            				%>	
		            						<span class="card__status"><span style="color:black; text-decoration:line-through;"><%=prodric.get(l).getPrezzo()%>&euro;</span>&nbsp;&nbsp;<h5 style=" font-weight:bold; color:red;"><%=String.format("%.2f",prezzoScontato)%>&euro;</h5></span>
		            				<%
		            					}
		            				%>
		          				</div>
		        			</div>
		        			<p class="card__description">Data uscita:&nbsp;<%=prodric.get(l).getData_uscita()%><br>Sconto:&nbsp;<%= prodric.get(l).getSconto()%>%</p>
		      			</div>
	    			</a>      
	  			</li>
	  			<% }%>
	  		<!-- fine ciclo -->		
			</ul>
		</div>
	</div>

</div>


<!--fine descrizione-->

<%@include file="../it/unisa/utility/footer.jsp" %>
</body>
</html>



