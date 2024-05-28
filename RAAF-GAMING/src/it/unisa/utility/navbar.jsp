<%@ page language="java" contentType="text/html; charset=UTF-8"
    pageEncoding="UTF-8" import="prodotto.VideogiocoBean, java.util.ArrayList,prodotto.ProdottoBean" %>

<%

	String str1 = "servletindex";
	String url1 = response.encodeURL(str1);
%>

<nav class="navbar navbar-dark">
	<div class="d-inline-flex mr-0 pr-0">
  <a class="navbar-brand" href="<%=url1%>"><img src="immagini/logo.png" alt="RAAF-GAMING"></a>
   <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" style="border-color: white;">
    <span class="navbar-toggler-icon"></span>
  </button>
  </div>
  <div class="d-inline-flex d-flex justify-content-center ml-0 pl-0 mr-0 pl-0">
  <form action="<%= response.encodeURL("servletricerca") %>" method="get" class="form-inline">
  <input class="form-control" type="text" placeholder="cerca" name="ricerca" style="border-radius:15px;background: url(immagini/lente.png) no-repeat scroll 98% 4px; background-size: 23px 23px ; background-color:white; border-color:black; border-width:2px; width:100%; padding-right: 30px;">
  </form>
  </div>
  <div class="d-inline-flex mr-5 ">
	<div class="dropdown mr-4 ml-0 pl-0">
		<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: 70px; background-color :rgba(35,35,35,0.8); border-color: #D2D2D2; border-width:2px;">
   		<i class='fas fa-user-astronaut' style='font-size:24px'></i>
  		</button>						
  		<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
  		<% ArrayList<String> array = (ArrayList<String>) request.getAttribute("impostazione");
  		ArrayList<String> array2 = (ArrayList<String>) request.getAttribute("impostazione2");
  		int i=0;
  		for(String string : array){
  		%>
  		<a class="dropdown-item" href="<%=response.encodeRedirectURL(array2.get(i)) %>" style="color:white;"><%=string%></a>
  		<% i++;} %>
		</div>
		</div>
		<%
		String carstring = "servletcarrello";
		carstring = response.encodeURL(carstring);
		%>
		  <a href="<%=carstring %>" style="margin-right:10px; margin-top:6px;">
		  <%
		  ArrayList<String> carr = (ArrayList<String>) request.getAttribute("carrello");
		  if(carr==null)
		  {
			  %><i id="sostituisciCarrello" class='fas fa-shopping-cart' style='font-size:27px; color:white;'></i><%
		  }
		  else
		  {
			  %><i class='fa fa-cart-arrow-down' style='font-size:27px; color:white;'></i><%
		  }
		  %>
	
	</a>
		</div>
		<%
			String ricercaurlcategorie = "servletcategorie";
		%>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class=" mr-5 list-inline">
      <li class="nav-item list-inline-item">
      <%
      String appoggio = ricercaurlcategorie + "?per=catalogo";
      %>
        <a class="nav-link" href="<%=response.encodeURL(appoggio)%>"><i class='fas'>&#xf46d;</i>&nbsp;Catalogo</a>
      </li>
      <%
     	appoggio = ricercaurlcategorie + "?per=azione";
      %>
      <li class="nav-item list-inline-item">
        <a class="nav-link" href="<%=response.encodeURL(appoggio)%>"><i class="fa">&#xf05b;</i>&nbsp;Azione</a>
      </li>
      <%
     	appoggio = ricercaurlcategorie + "?per=avventura";
      %>
      <li class="nav-item list-inline-item">
        <a class="nav-link" href="<%=response.encodeURL(appoggio)%>"><i class='fab'>&#xf286;</i>&nbsp;Avventura</a>
      </li>
      <%
     	appoggio = ricercaurlcategorie + "?per=battleroyale";
      %>
      <li class="nav-item list-inline-item">
        <a class="nav-link" href="<%=response.encodeURL(appoggio)%>"><i class='fas'>&#xf445;</i>&nbsp;Battle Royale</a>
      </li>
      <%
     	appoggio = ricercaurlcategorie + "?per=sport";
      %>
      <li class="nav-item list-inline-item">
        <a class="nav-link" href="<%=response.encodeURL(appoggio)%>"><i class="fa">&#xf1e3;</i>&nbsp;Sport</a>
      </li>
      <%
     	appoggio = ricercaurlcategorie + "?per=horror";
      %>
      <li class="nav-item list-inline-item">
        <a class="nav-link" href="<%=response.encodeURL(appoggio)%>"><i class='fas fa-ghost'></i>&nbsp;Horror</a>
      </li>
      <%
     	appoggio = ricercaurlcategorie + "?per=console";
      %>
      <li class="nav-item list-inline-item">
        <a class="nav-link" href="<%=response.encodeURL(appoggio)%>"><i class='fab'>&#xf3df;</i>&nbsp;Console</a>
      </li>
      <%
     	appoggio = ricercaurlcategorie + "?per=videogiochi";
      %>
      <li class="nav-item list-inline-item">
        <a class="nav-link" href="<%=response.encodeURL(appoggio)%>"><i class="fa">&#xf11b;</i>&nbsp;Videogiochi</a>
      </li>
      <%
     	appoggio = ricercaurlcategorie + "?per=abbonamenti";
      %>
      <li class="nav-item list-inline-item">
        <a class="nav-link" href="<%=response.encodeURL(appoggio)%>"><i class="fa">&#xf274;</i>&nbsp;Abbonamenti</a>
      </li>
      <%
     	appoggio = ricercaurlcategorie + "?per=dlc";
      %>
      <li class="nav-item list-inline-item">
        <a class="nav-link" href="<%=response.encodeURL(appoggio)%>"><i class="fa">&#xf067;</i>&nbsp;DLC</a>
      </li>
    </ul>
  </div>
</nav>

