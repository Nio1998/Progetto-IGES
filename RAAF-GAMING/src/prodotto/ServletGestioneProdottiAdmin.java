package prodotto;

import java.io.IOException;
import java.sql.SQLException;
import java.util.ArrayList;

import javax.servlet.RequestDispatcher;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.sql.DataSource;

import acquisto.OrdineBean;
import acquisto.OrdineDAO;
import magazzino.PresenteInBean;
import magazzino.PresenteInDAO;

/**
 * Servlet per ANDARE SULLA PAGINA DI FORNITURA PRODOTTI DELL'ADMIN
 */
@WebServlet("/servletgestioneadmin")
public class ServletGestioneProdottiAdmin extends HttpServlet {
	private static final long serialVersionUID = 1L;
       

    public ServletGestioneProdottiAdmin() {
        super();
        // TODO Auto-generated constructor stub
    }

	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		HttpSession sessione= request.getSession(true);
		synchronized(sessione) {
			Object logAdminB= sessione.getAttribute("logAdmin");
			if(logAdminB!=null) {
				//l'admin potrebbe essere loggato
				
				String logAdmin= (String) logAdminB;
				
				if(logAdmin.equals("prodotto")) {
					//l'admin e' loggato e può andare alla pagine di gestione(paginaAmministratore)
					
					OrdineDAO odao= new OrdineDAO((DataSource)super.getServletContext().getAttribute("DataSource"));
					try {
						request.setAttribute("visitato", "");
						
						//ottengo tutti i prodotti esistenti
						ProdottoDAO pdao= new ProdottoDAO((DataSource)super.getServletContext().getAttribute("DataSource"));
						ArrayList<ProdottoBean> prodottiEsistenti= pdao.allElements("codice_prodotto asc");
						request.setAttribute("prodottiEsistenti", prodottiEsistenti);
						
						//ottengo le quantità disponibili di ogni prodotto
						PresenteInDAO presentedao= new PresenteInDAO((DataSource)super.getServletContext().getAttribute("DataSource"));
					
						ArrayList<String> disponibilita = new ArrayList<String>();//per memorizzare la quantità disponibile del corrispettivo prodotto
						
						for(ProdottoBean prodotto : prodottiEsistenti) {
							ArrayList<PresenteInBean> magazzini= presentedao.ricercaPerProdotto(""+prodotto.getCodice_prodotto());//ottengo tutti i magazzini che hanno quel prodotto
							int quantitaAttuale=0;
							
							//calcolo la quantità totale disponibili di questo prodotto
							for(PresenteInBean p : magazzini) {
								quantitaAttuale+=p.getQuantita_disponibile();
							}
							disponibilita.add(""+quantitaAttuale);
						}
						request.setAttribute("disponibilita", disponibilita);
						
						//ottengo tutti i fornitori
						FornitoreDAO fdao= new FornitoreDAO((DataSource)super.getServletContext().getAttribute("DataSource"));
						ArrayList<FornitoreBean> fornitori= fdao.allElements("nome asc");
						request.setAttribute("fornitori", fornitori);
						
						//ottengo tutte le softwarehouse
						SoftwarehouseDAO sdao= new SoftwarehouseDAO((DataSource)super.getServletContext().getAttribute("DataSource"));
						ArrayList<SoftwarehouseBean> sfh= sdao.allElements("nomesfh asc");
						request.setAttribute("softwarehouse", sfh);
						
						//ottengo tutte le categorie
						CategoriaDAO cdao= new CategoriaDAO((DataSource)super.getServletContext().getAttribute("DataSource"));
						ArrayList<CategoriaBean> categorie= cdao.allElements("nome asc");
						request.setAttribute("categorie", categorie);
						
						String url="/WEB-INF/classes/prodotto/paginaAmministratore.jsp";
						url= response.encodeURL(url);
						RequestDispatcher dispatcher= request.getRequestDispatcher(url);
						dispatcher.forward(request, response);
						return;
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
				
				
				else {
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

	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		// TODO Auto-generated method stub
		doGet(request, response);
	}

}
