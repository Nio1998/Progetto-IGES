package acquisto;

import java.io.IOException;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Random;

import javax.servlet.RequestDispatcher;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.sql.DataSource;

import magazzino.PresenteInBean;
import magazzino.PresenteInDAO;
import prodotto.ProdottoBean;
import prodotto.ProdottoDAO;
import profilo.CartaDiCreditoBean;
import profilo.CartaDiCreditoDAO;
import profilo.CartaFedeltaBean;
import profilo.CartaFedeltaDAO;
import profilo.ClienteBean;
import profilo.ClienteDAO;


@WebServlet("/servletconfermaacquisto")
public class ServletConfermaAcquisto extends HttpServlet {
	private static final long serialVersionUID = 1L;
       

	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		this.doPost(request, response);
	}

	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		response.setContentType("text/html");
		HttpSession session = request.getSession(true);
		synchronized(session)
		{
			Object obj = session.getAttribute("log");
			if(obj==null)
			{
				String url="servletloginfirst";
				url=response.encodeURL(url);
				response.sendRedirect(url);
				return;
			}
			
			else
			{
				boolean log = (Boolean) obj;
				
				if(log==false)
				{
					String url="servletloginfirst";
					url=response.encodeURL(url);
					response.sendRedirect(url);
					return;
				}
				
				else
				{
					ArrayList<String> carrello = (ArrayList<String>) session.getAttribute("carrello");
					
					if(carrello == null)
					{
						String url="servletcarrello";
						url=response.encodeURL(url);
						response.sendRedirect(url);
						return;
					}
					
					else if(carrello.size()==0)
					{
						String url="servletcarrello";
						url=response.encodeURL(url);
						response.sendRedirect(url);
						return;
					}
					
					else
					{
						DataSource ds = (DataSource)super.getServletContext().getAttribute("DataSource");
						
						ArrayList<PresenteInBean> magazziniDisponibili= new ArrayList<PresenteInBean>(); //per salvarmi i magazzini che hanno disponibilità di quel prodotto da acquistare
						boolean nonDisponibili=false;//serve per mandare il messaggio d'errore se i prodotti non sono più disponibili
						ArrayList<String> carrelloCopia= (ArrayList<String>) carrello.clone(); //copio il carrello per fare le modifiche
						
						for(String idProdotto : carrello) {//controllo se i prodotti nel carrello sono ancora disponibili in magazzino
							
							PresenteInDAO presentedao = new PresenteInDAO(ds);
							ArrayList<PresenteInBean> presenteInMagazzino = null;
							try {
								presenteInMagazzino = presentedao.ricercaPerProdotto(idProdotto); //ottengo tutti i magazzini che tengono il prodotto acquistato
							} catch (SQLException e) {
								// TODO Auto-generated catch block
								e.printStackTrace();
							}
							
							int flag=0;//serve per eliminare il prodotto nel carrello se non è più disponibile
							
							for(PresenteInBean b : presenteInMagazzino)
							{					
								if(b.getQuantita_disponibile()>0) //appena trovo un magazzino che ha il prodotto da acquistare disponibile allora ok 
								{
									magazziniDisponibili.add(b);//mi salvo il magazzino che ha disponibilità
									flag=1;
									break;
								}
							}
							
							if(flag==0) { //il prodotto non è più disponibile e deve essere tolto dal carrello
								
								carrelloCopia.remove(idProdotto);	//elimino il prodotto dal carrello;
								if(carrelloCopia.size()==0)			
									session.setAttribute("carrello", null);
								else
									session.setAttribute("carrello", carrelloCopia);
								nonDisponibili=true;
							}
						}

						if(nonDisponibili) {	//MANDO MESSAGGIO CHE NON SONO DISPONIBILI DEI PRODOTTI
							request.setAttribute("eliminatoProdotto", "");
							String url="/servletcarrello"; //rimostro la pagina del carrello senza quel prodotto con un messaggio
							url=response.encodeURL(url);
							RequestDispatcher dispatcher= super.getServletContext().getRequestDispatcher(url);
							dispatcher.forward(request, response);
							return;
						}
						
						OrdineDAO odao=new OrdineDAO(ds);
						ArrayList<OrdineBean> ordini = null;
						try {
							ordini = odao.allElements("codice asc");
						} catch (SQLException e) {
							e.printStackTrace();
						}
						OrdineBean ordine = new OrdineBean();//è il nuovo ordine
						
						//generiamo un codice randomico da assegnare al nuovo ordine, lo generiamo finchè non otteniamo un codice diverso da quelli nel DB
						Random ran= new Random();
						int i=0;
						String nuovoCodice = null;
						String codice = null;
						while(i<ordini.size())
						{
							if(i==0)
							{
								codice = ""+ran.nextInt(999999999);
								nuovoCodice=codice;
							}
							if(codice.equals(ordini.get(i).getCodice()))
								i=0;
							else
								i++;
						}
						
						//settiamo i dati del nuovo ordine
						ordine.setCodice(nuovoCodice);
						ordine.setCliente((String)session.getAttribute("emailSession"));
						java.sql.Date date = new java.sql.Date(Calendar.getInstance().getTime().getTime());
						ordine.setData_acquisto(date);
						
						ClienteDAO cdao= new ClienteDAO(ds);
						ClienteBean cliente = null;
						
						try {
							cliente = cdao.ricercaPerChiave((String)session.getAttribute("emailSession"));
							
						} catch (SQLException e) {
							e.printStackTrace();
						}
						
						ordine.setMetodo_di_pagamento(cliente.getCartadicredito());
						ordine.setIndirizzo_di_consegna(request.getParameter("indirizzodiconsegna"));
						
						//calcolo il prezzo totale dell'ordine
						double prezzoTotale=0;
						ProdottoDAO proddao= new ProdottoDAO(ds); 
						for(String idProdotto: carrello) { //prendo tutti i prodotti dal carrello
							
							try {
								ProdottoBean prodotto= proddao.ricercaPerChiave(idProdotto);	//ottengo il prodotto dal DB
								double sconto=(prodotto.getPrezzo()*prodotto.getSconto())/100;	//calcolo lo sconto
								prezzoTotale= prezzoTotale+ (prodotto.getPrezzo()-sconto);		//sommo il prezzo al prezzo totale
								
							} catch (SQLException e) {
								e.printStackTrace();
							}
						}
						
						
						ordine.setPrezzo_totale(prezzoTotale);//inserisco il prezzo totale nell'ordine
						ordine.setStato("elaborazione");
						
						try {
							odao.newInsert(ordine);//salvo l'ordine nel DB
						} catch (SQLException e1) {
							e1.printStackTrace();
						}
						
						RiguardaDAO rig = new RiguardaDAO(ds);
						
						for(String id : carrello)
						{
							RiguardaBean riguarda = new RiguardaBean(); //inseriamo nel DB quest ordine quali prodotti contiene
							riguarda.setProdotto(Integer.parseInt(id));
							riguarda.setOrdine(ordine.getCodice());
							riguarda.setQuantita_acquistata(1);
							try {
								rig.newInsert(riguarda); //salvo nel DB
							} catch (SQLException e1) {
								e1.printStackTrace();
							}
							
							PresenteInDAO pdao= new PresenteInDAO(ds);
							for(PresenteInBean b : magazziniDisponibili) //prendo i magazzini calcolati prima e decremento di 1 la quantità disponibile di quel prodotto
							{
								
								try {
									pdao.doUpdate(b);
								} catch (SQLException e) {
									e.printStackTrace();
								}
									
							}
						}
						
						CartaFedeltaDAO cart = new CartaFedeltaDAO(ds);
						try {
							CartaFedeltaBean cartaf = cart.ricercaPerChiave(cliente.getCarta_fedelta());
							cart.doUpdate(cartaf);	//aggiungo 1 punto alla carta fedeltà
						} catch (SQLException e) {
							e.printStackTrace();
						}
						
						session.setAttribute("carrello",null);
						request.setAttribute("confermato","Acquisto Confermato");
						String url="/servletcarrello";
						url=response.encodeURL(url);
						RequestDispatcher dispatcher= super.getServletContext().getRequestDispatcher(url);
						dispatcher.forward(request, response);
						return;
					}
				}
			}
		}
	}

}
