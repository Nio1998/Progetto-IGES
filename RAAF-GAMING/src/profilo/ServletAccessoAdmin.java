package profilo;

import java.io.IOException;

import javax.servlet.RequestDispatcher;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

/**
 * Servlet implementation class ServletAccessoAdmin
 */
@WebServlet("/servletaccessoadmin")
public class ServletAccessoAdmin extends HttpServlet {
	private static final long serialVersionUID = 1L;
       
    public ServletAccessoAdmin() {
        super();
        // TODO Auto-generated constructor stub
    }

	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		HttpSession sessione= request.getSession(true);
		synchronized(sessione) {
			Object logAmB= sessione.getAttribute("logAdmin");//prendo questo campo per vedere se l'admin è loggato
			if(logAmB!=null) {
				//l'amministratore può essere loggato
				String logAmministratore= (String) logAmB;
				
				if(logAmministratore.equals("ordine")) {
					//l'amministratore e' gia loggato e quindi non può andare in admin.jsp
					String url="ServletGestioneOrdiniAdmin";//pagina ordini
					url= response.encodeURL(url);
					response.sendRedirect(url);
					return;
				}
				
				else if(logAmministratore.equals("prodotto"))
				{
					String url="servletgestioneadmin";//pagina fornitura
					url= response.encodeURL(url);
					response.sendRedirect(url);
					return;
				}
				
				else {
					//non è loggato e può andare in admin.jsp
					sessione.setAttribute("logAdmin", null);
					request.setAttribute("visitato", "");
					String url="/WEB-INF/classes/profilo/admin.jsp";
					url= response.encodeURL(url);
					RequestDispatcher dispatcher= request.getRequestDispatcher(url);
					dispatcher.forward(request, response);
					return;
				}
			}
			else {
				//l'amministratore non è loggato e quindi può andare in admin.jsp
				sessione.setAttribute("logAdmin", null);
				request.setAttribute("visitato", "");
				String url="/WEB-INF/classes/profilo/admin.jsp";
				url= response.encodeURL(url);
				RequestDispatcher dispatcher= request.getRequestDispatcher(url);
				dispatcher.forward(request, response);
				return;
				
			}
		}
	}

	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		// TODO Auto-generated method stub
		doGet(request, response);
	}

}
