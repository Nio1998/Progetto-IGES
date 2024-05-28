<%@ page language="java" contentType="text/html; charset=UTF-8"
    pageEncoding="UTF-8"%>
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
	
	<!-- CSS per le card -->
	<link rel="stylesheet" href="css/stileCard.css" type="text/css" >
	
	<!-- script per il carrello -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src='https://kit.fontawesome.com/a076d05399.js'></script>
	
</head>
<body>

<%
String str = (String) request.getAttribute("visitato");
if(str==null)
{
	str = "servletindex";
	String url = request.getRequestURL() + "";
	response.sendRedirect(response.encodeURL(url.replaceAll("homepage.jsp",str)));
	return;
}
ArrayList<ProdottoBean> prod =(ArrayList<ProdottoBean>) request.getAttribute("Prodotti");
%>

<%@include file="../it/unisa/utility/navbar.jsp" %>

<div class="container" style="background-color:rgba(230,230,230,0.5); min-height:100vh;">
	<div class="row">
		<div class="col-md-12 inline-block">
			<ul class="cards">
	  			<li style="height:250px; max-width:250px;">
	  			<%
	  				String stringa0 = "servletprodotto?id="+prod.get(0).getCodice_prodotto();
	  				String stringa1 = "servletprodotto?id="+prod.get(1).getCodice_prodotto();
	  				String stringa2 = "servletprodotto?id="+prod.get(2).getCodice_prodotto();
	  				String stringa3 = "servletprodotto?id="+prod.get(3).getCodice_prodotto();
	  				String stringa4 = "servletprodotto?id="+prod.get(4).getCodice_prodotto();
	  				String stringa5 = "servletprodotto?id="+prod.get(5).getCodice_prodotto();
	  				stringa0 = response.encodeURL(stringa0);
	  				stringa1 = response.encodeURL(stringa1);
	  				stringa2 = response.encodeURL(stringa2);
	  				stringa3 = response.encodeURL(stringa3);
	  				stringa4 = response.encodeURL(stringa4);
	  				stringa5 = response.encodeURL(stringa5);
	  			%>
	    			<a href="<%=stringa0 %>" class="card">
		      			<img src="servletcard?id=<%=prod.get(0).getCodice_prodotto()%>" class="card__image" alt="" />
		      			<div class="card__overlay">
		        			<div class="card__header">                  
		          				<i class='fas fa-shopping-cart' style='font-size:27px; color:black;'></i>
		          				<div class="card__header-text">
		            				<h3 class="card__title"><%=prod.get(0).getNome()%></h3> 
		            				<%
		            					if(prod.get(0).getSconto()==0){
		            				%>           
		            						<span class="card__status" style="color:red;"><%=String.format("%.2f",prod.get(0).getPrezzo())%>&euro;</span>
		            				<%
		            					}
		            					else{
		            						double prezzoBase= prod.get(0).getPrezzo();
		            						int sconto= prod.get(0).getSconto();
		            						double psconto= (prezzoBase*sconto)/100;
		            						double prezzoScontato= prezzoBase-psconto;
		            				%>	
		            						<span class="card__status"><span style="color:black; text-decoration:line-through;"><%=prod.get(0).getPrezzo()%>&euro;</span>&nbsp;&nbsp;<h5 style=" font-weight:bold; color:red;"><%=String.format("%.2f",prezzoScontato)%>&euro;</h5></span>
		            				<%
		            					}
		            				%>
		          				</div>
		        			</div>
		        			<p class="card__description">Data uscita:&nbsp;<%=prod.get(0).getData_uscita()%><br>Sconto:&nbsp;<%=prod.get(0).getSconto()%>%</p>
		      			</div>
	    			</a>      
	  			</li>
	  			<li style="height:250px; max-width:250px;">
	    			<a href="<%=stringa1 %>" class="card">
		      			<img src="servletcard?id=<%=prod.get(1).getCodice_prodotto()%>" class="card__image" alt="" />
		      			<div class="card__overlay">
		        			<div class="card__header">                  
		          				<i class='fas fa-shopping-cart' style='font-size:27px; color:black;'></i>
		          				<div class="card__header-text">
		            				<h3 class="card__title"><%=prod.get(1).getNome()%></h3>            
		            				<%
		            					if(prod.get(1).getSconto()==0){
		            				%>           
		            						<span class="card__status" style="color:red;"><%=String.format("%.2f",prod.get(1).getPrezzo())%>&euro;</span>
		            				<%
		            					}
		            					else{
		            						double prezzoBase= prod.get(1).getPrezzo();
		            						int sconto= prod.get(1).getSconto();
		            						double psconto= (prezzoBase*sconto)/100;
		            						double prezzoScontato= prezzoBase-psconto;
		            				%>	
		            						<span class="card__status"><span style="color:black; text-decoration:line-through;"><%=prod.get(1).getPrezzo()%>&euro;</span>&nbsp;&nbsp;<h5 style=" font-weight:bold; color:red;"><%=String.format("%.2f",prezzoScontato)%>&euro;</h5></span>
		            				<%
		            					}
		            				%>
		          				</div>
		        			</div>
		        			<p class="card__description">Data uscita:&nbsp;<%=prod.get(1).getData_uscita()%><br>Sconto:&nbsp;<%=prod.get(1).getSconto()%>%</p>
		      			</div>
	    			</a>      
	  			</li> 
	  			<li style="height:250px; max-width:250px;">
	    			<a href="<%=stringa2 %>" class="card">
		      			<img src="servletcard?id=<%=prod.get(2).getCodice_prodotto()%>" class="card__image" alt="" />
		      			<div class="card__overlay">
		        			<div class="card__header">                  
		          				<i class='fas fa-shopping-cart' style='font-size:27px; color:black;'></i>
		          				<div class="card__header-text">
		            				<h3 class="card__title"><%=prod.get(2).getNome()%></h3>            
		            				<%
		            					if(prod.get(2).getSconto()==0){
		            				%>           
		            						<span class="card__status" style="color:red;"><%=String.format("%.2f",prod.get(2).getPrezzo())%>&euro;</span>
		            				<%
		            					}
		            					else{
		            						double prezzoBase= prod.get(2).getPrezzo();
		            						int sconto= prod.get(2).getSconto();
		            						double psconto= (prezzoBase*sconto)/100;
		            						double prezzoScontato= prezzoBase-psconto;
		            				%>	
		            						<span class="card__status"><span style="color:black; text-decoration:line-through;"><%=prod.get(2).getPrezzo()%>&euro;</span>&nbsp;&nbsp;<h5 style=" font-weight:bold; color:red;"><%=String.format("%.2f",prezzoScontato)%>&euro;</h5></span>
		            				<%
		            					}
		            				%>
		          				</div>
		        			</div>
		        			<p class="card__description">Data uscita:&nbsp;<%=prod.get(2).getData_uscita()%><br>Sconto:&nbsp;<%=prod.get(2).getSconto()%>%</p>
		      			</div>
	    			</a>      
	  			</li>
			</ul>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 inline-block">
			<ul class="cards">
	  			<li style="height:250px; max-width:250px;">
	    			<a href="<%=stringa3 %>" class="card">
		      			<img src="servletcard?id=<%=prod.get(3).getCodice_prodotto()%>" class="card__image" alt="" />
		      			<div class="card__overlay">
		        			<div class="card__header">                  
		          				<i class='fas fa-shopping-cart' style='font-size:27px; color:black;'></i>
		          				<div class="card__header-text">
		            				<h3 class="card__title"><%=prod.get(3).getNome()%></h3>            
		            				<%
		            					if(prod.get(3).getSconto()==0){
		            				%>           
		            						<span class="card__status" style="color:red;"><%=String.format("%.2f",prod.get(3).getPrezzo())%>&euro;</span>
		            				<%
		            					}
		            					else{
		            						double prezzoBase= prod.get(3).getPrezzo();
		            						int sconto= prod.get(3).getSconto();
		            						double psconto= (prezzoBase*sconto)/100;
		            						double prezzoScontato= prezzoBase-psconto;
		            				%>	
		            						<span class="card__status"><span style="color:black; text-decoration:line-through;"><%=prod.get(3).getPrezzo()%>&euro;</span>&nbsp;&nbsp;<h5 style=" font-weight:bold; color:red;"><%=String.format("%.2f",prezzoScontato)%>&euro;</h5></span>
		            				<%
		            					}
		            				%>
		          				</div>
		        			</div>
		        			<p class="card__description">Data uscita:&nbsp;<%=prod.get(3).getData_uscita()%><br>Sconto:&nbsp;<%=prod.get(3).getSconto()%>%</p>
		      			</div>
	    			</a>      
	  			</li>
	  			<li style="height:250px; max-width:250px;">
	    			<a href="<%=stringa4 %>" class="card">
		      			<img src="servletcard?id=<%=prod.get(4).getCodice_prodotto()%>" class="card__image" alt="" />
		      			<div class="card__overlay">
		        			<div class="card__header">                  
		          				<i class='fas fa-shopping-cart' style='font-size:27px; color:black;'></i>
		          				<div class="card__header-text">
		            				<h3 class="card__title"><%=prod.get(4).getNome()%></h3>            
		            				<%
		            					if(prod.get(4).getSconto()==0){
		            				%>           
		            						<span class="card__status" style="color:red;"><%=String.format("%.2f",prod.get(4).getPrezzo())%>&euro;</span>
		            				<%
		            					}
		            					else{
		            						double prezzoBase= prod.get(4).getPrezzo();
		            						int sconto= prod.get(4).getSconto();
		            						double psconto= (prezzoBase*sconto)/100;
		            						double prezzoScontato= prezzoBase-psconto;
		            				%>	
		            						<span class="card__status"><span style="color:black; text-decoration:line-through;"><%=prod.get(4).getPrezzo()%>&euro;</span>&nbsp;&nbsp;<h5 style=" font-weight:bold; color:red;"><%=String.format("%.2f",prezzoScontato)%>&euro;</h5></span>
		            				<%
		            					}
		            				%>
		          				</div>
		        			</div>
		        			<p class="card__description">Data uscita:&nbsp;<%=prod.get(4).getData_uscita()%><br>Sconto:&nbsp;<%=prod.get(4).getSconto()%>%</p>
		      			</div>
	    			</a>      
	  			</li>
	  			<li style="height:250px; max-width:250px;">
	    			<a href="<%=stringa5 %>" class="card">
		      			<img src="servletcard?id=<%=prod.get(5).getCodice_prodotto()%>" class="card__image" alt="" />
		      			<div class="card__overlay">
		        			<div class="card__header">                  
		          				<i class='fas fa-shopping-cart' style='font-size:27px; color:black;'></i>
		          				<div class="card__header-text">
		            				<h3 class="card__title"><%=prod.get(5).getNome()%></h3>            
		            				<%
		            					if(prod.get(5).getSconto()==0){
		            				%>           
		            						<span class="card__status" style="color:red;"><%=String.format("%.2f",prod.get(5).getPrezzo())%>&euro;</span>
		            				<%
		            					}
		            					else{
		            						double prezzoBase= prod.get(5).getPrezzo();
		            						int sconto= prod.get(5).getSconto();
		            						double psconto= (prezzoBase*sconto)/100;
		            						double prezzoScontato= prezzoBase-psconto;
		            				%>	
		            						<span class="card__status"><span style="color:black; text-decoration:line-through;"><%=prod.get(5).getPrezzo()%>&euro;</span>&nbsp;&nbsp;<h5 style=" font-weight:bold; color:red;"><%=String.format("%.2f",prezzoScontato)%>&euro;</h5></span>
		            				<%
		            					}
		            				%>
		          				</div>
		        			</div>
		        			<p class="card__description">Data uscita:&nbsp;<%=prod.get(5).getData_uscita()%><br>Sconto:&nbsp;<%=prod.get(5).getSconto()%>%</p>
		      			</div>
	    			</a>      
	  			</li>
  			</ul>
		</div>
	</div>
</div>

<%@include file="../it/unisa/utility/footer.jsp" %>

</body>
</html>