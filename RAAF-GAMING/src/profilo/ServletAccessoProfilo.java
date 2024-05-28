package profilo;

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

/**
 * Servlet implementation class ServletAccessoProfilo
 */
@WebServlet("/servletaccessoprofilo")
public class ServletAccessoProfilo extends HttpServlet {
	private static final long serialVersionUID = 1L;

    public ServletAccessoProfilo() {
        super();
        // TODO Auto-generated constructor stub
    }

	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		HttpSession sessione= request.getSession(true);
		synchronized(sessione){
			Object logB= sessione.getAttribute("log");
			if(logB!=null) {
				//l'utente potrebbe essere loggato
				
				boolean log= (Boolean) logB;
				if(log==true) {
					//l'utente è loggato
					
					//per far uscire i link: LOGOUT, PROFILO ecc sulla navabar
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
					
					//per l'immagine del carrello sulla navbar
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
					
					
					ClienteDAO cdao= new ClienteDAO((DataSource)super.getServletContext().getAttribute("DataSource"));
					try {
						//prendo i dati dell'utente loggato per far stampare i suoi dati sulla carta fedeltà
						ClienteBean utente= cdao.ricercaPerChiave((String)sessione.getAttribute("emailSession"));
						request.setAttribute("visitato", "");//per vedere nel profilo.jsp se sono passato prima in questa servlet
						request.setAttribute("datiUtenteCarta", utente);
						
						CartaFedeltaDAO cartadao= new CartaFedeltaDAO((DataSource)super.getServletContext().getAttribute("DataSource"));
						CartaFedeltaBean carta= cartadao.ricercaPerChiave(utente.getCarta_fedelta());
						request.setAttribute("puntiCarta", carta);
						
						String url="/WEB-INF/classes/profilo/profilo.jsp";
						url= response.encodeURL(url);
						RequestDispatcher dispatcher= request.getRequestDispatcher(url);
						dispatcher.forward(request, response);
						
					} catch (SQLException e) {
						// TODO Auto-generated catch block
						e.printStackTrace();
					}
					
				}
				else {
					//l'utente non è loggato
					
					//per far comparire sulla navbar LOGIN e registrati
					String impostazione1 = "Login";
					String impostazione2="Registrati";
					ArrayList<String> array = new ArrayList<String>();
					array.add(impostazione1);
					array.add(impostazione2);
					request.setAttribute("impostazione",array);
					String impostazione4 = "servletloginfirst";
					String impostazione5="ServletRegistrazione";
					ArrayList<String> array2 = new ArrayList<String>();
					array2.add(impostazione4);
					array2.add(impostazione5);
					request.setAttribute("impostazione2",array2);
					
					//per l'immagine del carrello sulla navbar
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
					
					String url="servletindex";
					url=response.encodeURL(url);
					response.sendRedirect(url);
				}
				
			}
			else {
				//se l'utente cerca di accedere al profilo ma non è loggato
				String url="servletindex";
				url=response.encodeURL(url);
				response.sendRedirect(url);//costringo l'utente a ridirezionarsi sulla home
			}
		}
	}

	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		// TODO Auto-generated method stub
		doGet(request, response);
	}

}
