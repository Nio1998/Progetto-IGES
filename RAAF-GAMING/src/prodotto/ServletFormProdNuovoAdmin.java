package prodotto;

import java.io.IOException;
import java.io.InputStream;
import java.sql.Date;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.Calendar;

import javax.servlet.annotation.*;
import javax.servlet.RequestDispatcher;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.servlet.http.Part;
import javax.sql.DataSource;

import magazzino.MagazzinoBean;
import magazzino.MagazzinoDAO;
import magazzino.PresenteInBean;
import magazzino.PresenteInDAO;

/**
 * Servlet implementation class ServletFormProdNuovoAdmin
 */
@WebServlet("/servletformprodnuovoadmin")
@MultipartConfig(fileSizeThreshold=1024*1024*10,maxFileSize=1024*1024*10,maxRequestSize=1024*1024*10)

public class ServletFormProdNuovoAdmin extends HttpServlet {
	private static final long serialVersionUID = 1L;
       
    public ServletFormProdNuovoAdmin() {
        super();
        // TODO Auto-generated constructor stub
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
					//l'admin e' loggato e può eseguire il form
					String nomeProd =request.getParameter("nomeP");//prendo il nome del prodotto
					double prezzoProd= Double.valueOf(request.getParameter("prezzoP"));
					System.out.println("prezzo= "+prezzoProd);
					int scontoProd=Integer.parseInt(request.getParameter("scontoP"));
					String data = request.getParameter("dataP");
					Date dataProd= Date.valueOf(data);
					String fornitoreProd = request.getParameter("fornitoreP");
					int quantitaProd=Integer.parseInt(request.getParameter("quantitaP"));

					
					DataSource ds = (DataSource)super.getServletContext().getAttribute("DataSource");
					System.out.println("dopo datasource");
					ProdottoDAO ProdDAO = new ProdottoDAO(ds);
					
					try {
						ArrayList<ProdottoBean> prodEsistenti = ProdDAO.ricercaPerNome(nomeProd);
						System.out.println("dopo Arraylist");
						if(prodEsistenti.size()!=0)//se il nuovo prodotto e gia  nel database 
						{
							System.out.println("dopo prodEsistenti.size()!=0");
							String message = "prodotto gia' esistente";
							request.setAttribute("message", message);
							RequestDispatcher dispatcher = request.getRequestDispatcher(response.encodeURL("/servletgestioneadmin"));
							dispatcher.forward(request, response);
						}
						else //il nuovo prodotto non e presente nel database
						{
							//vedo se nel magazino c'e spazio
							MagazzinoDAO mmd=new MagazzinoDAO(ds);
							ArrayList<MagazzinoBean> mb=null;
							try {
								mb=mmd.allElements("indirizzo asc");
							} 
							catch (SQLException e)
							{
								e.printStackTrace();
							}
							PresenteInDAO pid=new PresenteInDAO(ds);
							for(int i=0;i<mb.size();i++)
								{
									ArrayList<PresenteInBean> pb=null;
									try 
									{
										pb=pid.ricercaPerMagazzino(mb.get(i).getIndirizzo());
									}
									catch (SQLException e)
									{
										e.printStackTrace();
									}
									int cont=0;
									for(PresenteInBean pib:pb)
									{
										cont+=pib.getQuantita_disponibile();//calcolo la quantità occupata
									}
									if((cont+quantitaProd)<=mb.get(i).getCapienza()) {//disponibilita in magazzino
										
										ProdottoBean prodBean = new ProdottoBean();
										ProdottoBean max =  ProdDAO.getMax();
										prodBean.setCodice_prodotto(max.getCodice_prodotto()+1);
										prodBean.setData_uscita(dataProd);
										prodBean.setFornitore(fornitoreProd);
										//prendere il file dalla request
										System.out.println("prima di request.getpart");
										Part parte = request.getPart("copertina");
										System.out.println("dopo request.getpart");
										InputStream flusso = null;
										
										System.out.println("prima di if");
										if(parte!=null)
										{
											flusso=parte.getInputStream();
											prodBean.setCopertina(flusso.readAllBytes());
										}
										System.out.println("dopo if");
										
										prodBean.setNome(nomeProd);
										prodBean.setPrezzo(prezzoProd);
										prodBean.setQuantita_fornitura(quantitaProd);
										prodBean.setSconto(scontoProd);
										Date dateatt = new Date(Calendar.getInstance().getTime().getTime());//calcolo la data attuale
										prodBean.setUltima_fornitura(dateatt);
										
										PresenteInBean pbean = new PresenteInBean();
										pbean.setProdotto(prodBean.getCodice_prodotto());
										pbean.setQuantita_disponibile(prodBean.getQuantita_fornitura());
										pbean.setMagazzino(mb.get(i).getIndirizzo());
										
										String scelta = request.getParameter("sceltaP");
										
										if(scelta.equals("videogioco fisico")==true)
										{
											String nomecategoria = request.getParameter("categoria");//prendo la categoria del videogioco 
											//setto il bean per la tabella PARTE_DI del DB
											ParteDiBean parteBean= new ParteDiBean();
											parteBean.setCategoria(nomecategoria);
											parteBean.setVideogioco(prodBean.getCodice_prodotto());
											ParteDiDAO pdao= new ParteDiDAO((DataSource)super.getServletContext().getAttribute("DataSource"));
											
											double dimensione  =Double.parseDouble(request.getParameter("dimensioni"));
											int pegi = Integer.parseInt(request.getParameter("pegi"));
											//String chiave = request.getParameter("chiave");
											int ncd = Integer.parseInt(request.getParameter("ncd"));
											
											String nomesfh = request.getParameter("nomesfh");
											boolean edlim = Boolean.parseBoolean(request.getParameter("limitata"));
											
											VideogiocoBean videogicocoBean = new VideogiocoBean();
											videogicocoBean.setDimensione(dimensione);
											videogicocoBean.setEdizione_limitata(edlim);
											videogicocoBean.setPegi(pegi);
											videogicocoBean.setSoftware_house(nomesfh);
											videogicocoBean.setProdotto(prodBean.getCodice_prodotto());
											
											
											//inserisci un gioco fisico
											videogicocoBean.setNcd(ncd);
											videogicocoBean.setVkey(null);
												//inserisci gioco digitale
												/*videogicocoBean.setVkey(chiave);
												videogicocoBean.setNcd(0);*/
											
											prodBean.setGestore((String)sessione.getAttribute("emailAdmin"));//settiamo il gestore che ha fornito questo nuovo gestore
											ProdDAO.newInsert(prodBean);
											pid.newInsert(pbean);
											VideogiocoDAO vDAO = new VideogiocoDAO(ds);
											vDAO.newInsert(videogicocoBean);
											pdao.newInsert(parteBean);
											
											String messageok = "Prodotto inserito con successo!";
											request.setAttribute("messageok", messageok);
											RequestDispatcher dispatcher = request.getRequestDispatcher(response.encodeURL("/servletgestioneadmin"));
											dispatcher.forward(request, response);
											return;
											
										}
										else if(scelta.equals("videogioco digitale")==true) {
											
											String nomecategoria = request.getParameter("categoria");//prendo la categoria del videogioco 
											//setto il bean per la tabella PARTE_DI del DB
											ParteDiBean parteBean= new ParteDiBean();
											parteBean.setCategoria(nomecategoria);
											parteBean.setVideogioco(prodBean.getCodice_prodotto());
											ParteDiDAO pdao= new ParteDiDAO((DataSource)super.getServletContext().getAttribute("DataSource"));
											
											double dimensione  =Double.parseDouble(request.getParameter("dimensioni"));
											int pegi = Integer.parseInt(request.getParameter("pegi"));
											String chiave = request.getParameter("chiave");
											
											String nomesfh = request.getParameter("nomesfh");
											boolean edlim = Boolean.parseBoolean(request.getParameter("limitata"));
											
											VideogiocoBean videogicocoBean = new VideogiocoBean();
											videogicocoBean.setDimensione(dimensione);
											videogicocoBean.setEdizione_limitata(edlim);
											videogicocoBean.setPegi(pegi);
											videogicocoBean.setSoftware_house(nomesfh);
											videogicocoBean.setProdotto(prodBean.getCodice_prodotto());
											
											//inserisci gioco digitale
											videogicocoBean.setVkey(chiave);
											videogicocoBean.setNcd(0);
											
											prodBean.setGestore((String)sessione.getAttribute("emailAdmin"));//settiamo il gestore che ha fornito questo nuovo gestore
											ProdDAO.newInsert(prodBean);
											pid.newInsert(pbean);
											VideogiocoDAO vDAO = new VideogiocoDAO(ds);
											vDAO.newInsert(videogicocoBean);
											pdao.newInsert(parteBean);
											
											String messageok = "Prodotto inserito con successo!";
											request.setAttribute("messageok", messageok);
											RequestDispatcher dispatcher = request.getRequestDispatcher(response.encodeURL("/servletgestioneadmin"));
											dispatcher.forward(request, response);
											return;
										}
										else if(scelta.equals("console")==true)
										{
											String specifica = request.getParameter("specifiche");
											String colore = request.getParameter("colore");
											
											ConsoleBean console = new ConsoleBean();
											console.setColore(colore);
											console.setProdotto(prodBean.getCodice_prodotto());
											console.setSpecifica(specifica);
											
											ConsoleDAO cDAO = new ConsoleDAO(ds);
											
											prodBean.setGestore((String)sessione.getAttribute("emailAdmin"));//settiamo il gestore che ha fornito questo nuovo gestore
											ProdDAO.newInsert(prodBean);
											pid.newInsert(pbean);
											cDAO.newInsert(console);
											
											String messageok = "Prodotto inserito con successo!";
											request.setAttribute("messageok", messageok);
											RequestDispatcher dispatcher = request.getRequestDispatcher(response.encodeURL("/servletgestioneadmin"));
											dispatcher.forward(request, response);
											return;
										}
										else if(scelta.equals("dlc")==true)
										{
											String descrizione = request.getParameter("descrizione");
											int dimensioneDlc = Integer.parseInt(request.getParameter("dimensioneDlc"));
											
											DlcBean dlcbean = new DlcBean();
											DlcDAO dDAO = new DlcDAO(ds);
											dlcbean.setProdotto(prodBean.getCodice_prodotto());
											dlcbean.setDescrizione(descrizione);
											dlcbean.setDimensione(dimensioneDlc);
										
											prodBean.setGestore((String)sessione.getAttribute("emailAdmin"));//settiamo il gestore che ha fornito questo nuovo gestore
											ProdDAO.newInsert(prodBean);
											pid.newInsert(pbean);
											dDAO.newInsert(dlcbean);
											
											String messageok = "Prodotto inserito con successo!";
											request.setAttribute("messageok", messageok);
											RequestDispatcher dispatcher = request.getRequestDispatcher(response.encodeURL("/servletgestioneadmin"));
											dispatcher.forward(request, response);
											return;
										}
										else
										{
											String codice = request.getParameter("codice");
											int durata = Integer.parseInt(request.getParameter("durata"));
											
											AbbonamentoBean abb = new AbbonamentoBean();
											AbbonamentoDAO abbDAO = new AbbonamentoDAO(ds);
											
											abb.setCodice(codice);
											abb.setDurata_abbonamento(durata);
											abb.setProdotto(prodBean.getCodice_prodotto());
											
											prodBean.setGestore((String)sessione.getAttribute("emailAdmin"));//settiamo il gestore che ha fornito questo nuovo gestore
											ProdDAO.newInsert(prodBean);
											pid.newInsert(pbean);
											abbDAO.newInsert(abb);
											
											String messageok = "Prodotto inserito con successo!";
											request.setAttribute("messageok", messageok);
											RequestDispatcher dispatcher = request.getRequestDispatcher(response.encodeURL("/servletgestioneadmin"));
											dispatcher.forward(request, response);
											return;
										}
										
										}
								}	
							
							request.setAttribute("message", "Capienza non disponibile");
							String url="/servletgestioneadmin";
							RequestDispatcher view=super.getServletContext().getRequestDispatcher(response.encodeURL(url));
							view.forward(request, response);
							return;
							
							//ho visto
							
						}//fine else prodotto non e presente nel database
						
					} catch (SQLException e) {
						// TODO Auto-generated catch block
						e.printStackTrace();
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
