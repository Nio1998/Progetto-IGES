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
import acquisto.SpeditoBean;
import acquisto.SpeditoDAO;

/**
 * Servlet implementation class servletOrdini
 */
@WebServlet("/servletordini")
public class ServletOrdini extends HttpServlet {
	private static final long serialVersionUID = 1L;
       

    public ServletOrdini() {
        super();
        // TODO Auto-generated constructor stub
    }

	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		HttpSession sessione= request.getSession();
		synchronized(sessione) {
			Object logB= sessione.getAttribute("log");
			boolean log;
			//controllo se l'utente è loggato
			if(logB!=null) {
				log=(Boolean) logB;
			}
			else {
				log=false;
				sessione.setAttribute("log", log);
			}
			
			if(log==true) {
				//se è loggato sulla navbar deve comparire logOut, i miei ordini e profilo
				String impostazione1 = "LogOut";
				String impostazione2="Profilo";
				String impostazione3="I miei ordini";
				ArrayList<String> array = new ArrayList<String>();
				array.add(impostazione1);
				array.add(impostazione2);
				array.add(impostazione3);
				request.setAttribute("impostazione",array);
				String impostazione4 = "servletlogout";
				String impostazione5="servletaccessoprofilo";
				String impostazione6="servletordini";
				ArrayList<String> array2 = new ArrayList<String>();
				array2.add(impostazione4);
				array2.add(impostazione5);
				array2.add(impostazione6);
				request.setAttribute("impostazione2",array2);
				ArrayList<String> carr = (ArrayList<String>) sessione.getAttribute("carrello");
				if(carr == null)
				{
					request.setAttribute("carrello",null);
				}
				else if(carr.size()==0)
				{
					request.setAttribute("carrello",null);
				}
				else
				{
					request.setAttribute("carrello",carr);
				}
				
				//ottengo dal DB tutti gli ordini effettuati dall'utente loggato
				OrdineDAO odao= new OrdineDAO((DataSource) super.getServletContext().getAttribute("DataSource"));
				String emailUtente= (String)sessione.getAttribute("emailSession");
				try {
					ArrayList<OrdineBean> ordiniUtente= odao.ricercaPerCliente(emailUtente);
					request.setAttribute("listaOrdini", ordiniUtente);//metto l'array degli ordini nella request che passero a ordini.jsp
					
					//ottengo il corriere espresso e le date di consegna di ogni ordine dell'utente
					SpeditoDAO sdao= new SpeditoDAO((DataSource) super.getServletContext().getAttribute("DataSource"));
					ArrayList<SpeditoBean> spedizione= new ArrayList<SpeditoBean>();
					SpeditoBean app= new SpeditoBean();
					
					for(OrdineBean ordineApp : ordiniUtente) {
						app= sdao.ricercaPerChiave(ordineApp.getCodice());
						spedizione.add(app);
					}
					request.setAttribute("spedizioni", spedizione);//metto l'array delle spedizioni nella request che passero a ordini.jsp
							
					request.setAttribute("visitato", "");//per controllare se son passato prima per questa servlet prima di andare a ordini.jsp
					String url="/WEB-INF/classes/prodotto/ordini.jsp";
					url= response.encodeURL(url);
					RequestDispatcher dispatcher= request.getRequestDispatcher(url);
					dispatcher.forward(request, response);
					
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
				
			}
			else {
				//se non è loggato lo riporto sulla home
				String url="servletindex";
				url= response.encodeURL(url);
				response.sendRedirect(url);
			}
		}
	}

	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		// TODO Auto-generated method stub
		doGet(request, response);
	}

}
