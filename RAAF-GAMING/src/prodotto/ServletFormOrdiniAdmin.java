package prodotto;

import java.io.IOException;
import java.sql.Date;
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
import acquisto.SpeditoBean;
import acquisto.SpeditoDAO;

/**
 * Servlet per SPEDIRE UN ORDINE
 */
@WebServlet("/servletformordiniadmin")
public class ServletFormOrdiniAdmin extends HttpServlet {
	private static final long serialVersionUID = 1L;
       

    public ServletFormOrdiniAdmin() {
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
					//l'admin e' loggato e può eseguire il form perchè è un GESTORE ORDINI
					
					SpeditoDAO sdao= new SpeditoDAO((DataSource)super.getServletContext().getAttribute("DataSource"));
					SpeditoBean spedizione= new SpeditoBean();
					String ordine= request.getParameter("numeroOrdine");
					String corriere= request.getParameter("corriere");
					String data= request.getParameter("consegnaO");					
					
					java.sql.Date dataConsegna= java.sql.Date.valueOf(data);
					
					if(ordine==null || ordine=="" || corriere==null || corriere=="" || data==null || data=="")
					{
						String url="/ServletGestioneOrdiniAdmin";
						url= response.encodeURL(url);
						request.setAttribute("messageok", "Errore!");
						RequestDispatcher dispatcher= request.getRequestDispatcher(url);
						dispatcher.forward(request, response);	
					}
					
					spedizione.setOrdine(ordine);	
					spedizione.setCorriere_esprersso(corriere);
					spedizione.setData_consegna(dataConsegna);
					
					OrdineDAO odao= new OrdineDAO((DataSource)super.getServletContext().getAttribute("DataSource"));
					
					try {
						OrdineBean ordineGestito= odao.ricercaPerChiave(ordine); //ottengo l'ordine che viene spedito
						
						//setto il nuovo stato dell'ordine e chi l'ha gestito(l'email del gestore)
						ordineGestito.setStato("spedito");
						ordineGestito.setGestore((String)sessione.getAttribute("emailAdmin"));
						
						odao.doUpdate(ordineGestito); //aggiorno l'ordine
						sdao.newInsert(spedizione);	 //inserisco la tupla
						
						String url="/ServletGestioneOrdiniAdmin";
						url= response.encodeURL(url);
						request.setAttribute("messageok","Ordine spedito con successo!");
						RequestDispatcher dispatcher= request.getRequestDispatcher(url);
						dispatcher.forward(request, response);	
					} catch (SQLException e) {
						// TODO Auto-generated catch block
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
