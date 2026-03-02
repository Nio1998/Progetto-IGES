package magazzino;

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

import acquisto.CorriereEspressoBean;
import acquisto.CorriereEspressoDAO;
import acquisto.OrdineBean;
import acquisto.OrdineDAO;

/**
 * Servlet per VEDERE GLI ORDINI NON SPEDITI
 */
@WebServlet("/ServletGestioneOrdiniAdmin")
public class ServletGestioneOrdiniAdmin extends HttpServlet {
	private static final long serialVersionUID = 1L;
       
   
    public ServletGestioneOrdiniAdmin() {
        super();
        // TODO Auto-generated constructor stub
    }

	
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		doPost(request, response);
	}

	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		HttpSession sessione = request.getSession(true);
		synchronized(sessione) {
			Object logAdminB= sessione.getAttribute("logAdmin");
			if(logAdminB!=null) {
				//l'admin potrebbe essere loggato
				
				String logAdmin= (String) logAdminB;
				if(logAdmin.equals("ordine")) {
					//l'admin e' loggato e vedere gli ordini non spediti perchè è un GESTORE ORDINI
					
					OrdineDAO odao= new OrdineDAO((DataSource)super.getServletContext().getAttribute("DataSource"));
					CorriereEspressoDAO sdao= new CorriereEspressoDAO((DataSource)super.getServletContext().getAttribute("DataSource"));
					
					try {
						ArrayList<OrdineBean> ordiniNonSpediti= odao.getOrdiniNonConsegnati();
						ArrayList<CorriereEspressoBean> corrieri= sdao.allElements("nome asc");
						
						request.setAttribute("visitato", "");
						request.setAttribute("ordiniNonSpediti", ordiniNonSpediti);
						request.setAttribute("corrieri", corrieri);
						
						String url="/WEB-INF/classes/prodotto/paginaGestioneOrdini.jsp";
						url= response.encodeURL(url);
						RequestDispatcher dispatcher= request.getRequestDispatcher(url);
						dispatcher.forward(request, response);
						return;
						
					} catch (SQLException e) {
						e.printStackTrace();
					}
					
					
				}
				else if(logAdmin.equals("prodotto")){ //se è un GESTORE PRODOTTI 
					String url="servletgestioneadmin";
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

}
