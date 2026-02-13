package prodotto;

import java.io.IOException;
import java.sql.Date;
import java.sql.SQLException;
import java.util.ArrayList;import java.util.Calendar;

import javax.servlet.RequestDispatcher;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.sql.DataSource;
import magazzino.MagazzinoBean;
import magazzino.MagazzinoDAO;
import magazzino.PresenteInBean;
import magazzino.PresenteInDAO;

@WebServlet("/servletformprodesistentiadmin")
public class ServletFormProdEsistentiAdmin extends HttpServlet {
	private static final long serialVersionUID = 1L;
       
    public ServletFormProdEsistentiAdmin() {
        super();
        
    }

	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		doPost(request,response);
	}

	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		HttpSession sessione = request.getSession(true);
		synchronized(sessione) {
			Object logAdminB= sessione.getAttribute("logAdmin");
			if(logAdminB!=null) {
				//l'admin potrebbe essere loggato
				
				String logAdmin= (String) logAdminB;
				
				if(logAdmin.equals("prodotto")) {
				//l'admin e' loggato e puo eseguire il form
					DataSource ds=(DataSource)super.getServletContext().getAttribute("DataSource");
					ProdottoDAO proddao= new ProdottoDAO(ds);
					
					String codiceProd= (String) request.getParameter("prod");//ottengo il valore dell'input type hidden che contiene l'id del prodotto
					
					int quantitaDaRifornire= Integer.parseInt(request.getParameter("quantita"));
					if(quantitaDaRifornire<0) {
						request.setAttribute("message", "Quantita' non valida");
						String url="/servletgestioneadmin";
						RequestDispatcher view=super.getServletContext().getRequestDispatcher(response.encodeURL(url));
						view.forward(request, response);
						return;
					}
					
					ProdottoBean daRifornire=null;
					try {
						daRifornire= proddao.ricercaPerChiave(codiceProd);
					} catch (SQLException e2) {
						// TODO Auto-generated catch block
						e2.printStackTrace();
					}
					
					if(daRifornire!=null) {//SE IL PRODOTTO DA RIFORNIRE ESISTE
						
						MagazzinoDAO magazzinodao=new MagazzinoDAO(ds);
						ArrayList<MagazzinoBean> mb=null;
						try {
							mb=magazzinodao.allElements("indirizzo asc");//ottengo tutti i magazzini
						} catch (SQLException e1) {
							e1.printStackTrace();
						}
						
						PresenteInDAO presentedao= new PresenteInDAO(ds);
						ArrayList<PresenteInBean> presenteMagazzini=null;
						int quantitaOccupata=0;
						ArrayList<Integer> occupatoPerMagazzino= new ArrayList<Integer>();//per tenere traccia dello spazio occupato del corrispettivo magazzino
						
						for(MagazzinoBean magazzino : mb) {
							
							try {
								presenteMagazzini= presentedao.ricercaPerMagazzino(magazzino.getIndirizzo());//ottengo tutti i prodotti che sono in questo magazzino
							
								for(PresenteInBean presentebean : presenteMagazzini) {
									quantitaOccupata+=presentebean.getQuantita_disponibile();//calcolo la quantità occupata in quel determinato magazzino
								}
								occupatoPerMagazzino.add(quantitaOccupata);//mi salvo nell'array lo spazio occupato per quel magazzino
								
								quantitaOccupata=0;
								
							} catch (SQLException e) {
								e.printStackTrace();
							}
						}
						
						
						ArrayList<PresenteInBean> magazziniCheHannoProdotto=null;
						try {
							magazziniCheHannoProdotto= presentedao.ricercaPerProdotto(""+daRifornire.getCodice_prodotto());//ottengo tutti i magazzini che hanno quel prodotto
						} catch (SQLException e) {
							e.printStackTrace();
						}
					
						//int quantitaDaRifornire= (int) request.getAttribute("quantita"+daRifornire.getCodice_prodotto());
						
						ArrayList<MagazzinoBean> magazziniCheNonHanno= new ArrayList<MagazzinoBean>();//per tenere traccia di tutti i magazzini che non hanno il prodotto da rifornire
						
						for(PresenteInBean presente : magazziniCheHannoProdotto) {
							
							int i=0;
							for(;i<mb.size();i++) {
								MagazzinoBean magazzino=mb.get(i);
								
								if(magazzino.getIndirizzo().equals(presente.getMagazzino())) {//ottengo il magazzino che ha già quel prodotto
									
									int capienzaOccupata=occupatoPerMagazzino.get(i);//ottengo la capienza occupata del magazzino che già il prodotto
									
									int capienzaDisponibile= magazzino.getCapienza()-capienzaOccupata; //ottengo la capienza disponibile
									
									if(capienzaDisponibile>=(capienzaOccupata+quantitaDaRifornire)) {//se ha spazio per rifornire OK
										
										presente.setQuantita_disponibile(presente.getQuantita_disponibile()+quantitaDaRifornire);//aggiorno la quantità
										try {
											presentedao.rifornitura(presente);//salvo nel DB
										} catch (SQLException e) {
											e.printStackTrace();
										}
										
										request.setAttribute("messageok", "Prodotto Rifornito con Successo");
										String url="/servletgestioneadmin";
										RequestDispatcher view=super.getServletContext().getRequestDispatcher(response.encodeURL(url));
										view.forward(request, response);
										return;
									}
								}
								else {
									magazziniCheNonHanno.add(magazzino);
								}
							}
							
						}
						
						int i=0;
						for(MagazzinoBean magazzinoNon : magazziniCheNonHanno) {
							
							for(;i<mb.size();i++) {
								MagazzinoBean magazzino= mb.get(i);
								
								if(magazzino.getIndirizzo().equals(magazzinoNon.getIndirizzo())) {
									int capienzaOccupata=occupatoPerMagazzino.get(i);//ottengo la capienza occupata del magazzino che NON ha il prodotto
									
									int capienzaDisponibile= magazzinoNon.getCapienza()-capienzaOccupata; //ottengo la capienza disponibile
									
									if(capienzaDisponibile>=(capienzaOccupata+quantitaDaRifornire)) {//se ha disponibilità
										//CREO UNA NUOVA RIGA IN PRESENTE PERCHE' NON ESISTE PER QUESTO MAGAZZINO DATO CHE NON HA IL PRODOTTO
										PresenteInBean nuovoPresente= new PresenteInBean();
										nuovoPresente.setMagazzino(magazzinoNon.getIndirizzo());
										nuovoPresente.setProdotto(daRifornire.getCodice_prodotto());
										nuovoPresente.setQuantita_disponibile(quantitaDaRifornire);
										
										try {
											presentedao.newInsert(nuovoPresente);
										} catch (SQLException e) {
											e.printStackTrace();
										}
										
										request.setAttribute("messageok", "Prodotto Rifornito con Successo");
										String url="/servletgestioneadmin";
										RequestDispatcher view=super.getServletContext().getRequestDispatcher(response.encodeURL(url));
										view.forward(request, response);
										return;
									}
								}
							}
						}
						
						//SE NESSUN MAGAZZINO HA CAPIENZA DISPONIBILE
						request.setAttribute("message", "Capienza non disponibile");
						String url="/servletgestioneadmin";
						RequestDispatcher view=super.getServletContext().getRequestDispatcher(response.encodeURL(url));
						view.forward(request, response);
						return;
		
					}
					else {//SE HA INVIATO UN CODICE DEL PRODOTTO ERRATO
						
						request.setAttribute("message", "Il prodotto che vuoi rifornire non esiste");
						String url="/servletgestioneadmin";
						RequestDispatcher view=super.getServletContext().getRequestDispatcher(response.encodeURL(url));
						view.forward(request, response);
						return;
					}
					
				}
				else if(logAdminB.equals("ordine")){
					String url="ServletGestioneOrdiniAdmin";
					url= response.encodeURL(url);
					response.sendRedirect(url);
					return;
				}
				else{
					//l'admin non e' loggato
					String url="servletaccessoadmin";
					url= response.encodeURL(url);
					response.sendRedirect(url);
					return;
				}
			}
			else {
				//l'admin non e' loggato
				String url="servletaccessoadmin";
				url= response.encodeURL(url);
				response.sendRedirect(url);
				return;
			}
		}
	}

}
