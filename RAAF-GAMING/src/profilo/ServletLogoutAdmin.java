package profilo;

import java.io.IOException;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

/**
 * Servlet implementation class ServletLogoutAdmin
 */
@WebServlet("/servletlogoutadmin")
public class ServletLogoutAdmin extends HttpServlet {
	private static final long serialVersionUID = 1L;
       
    public ServletLogoutAdmin() {
        super();
        // TODO Auto-generated constructor stub
    }

	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		HttpSession sessione= request.getSession(true);
		Object logAdmin= sessione.getAttribute("logAdmin");
		if(logAdmin==null) {
			String url="servletaccessoadmin";
			url= response.encodeURL(url);
			response.sendRedirect(url);
			return;
		}
		else {
			sessione.removeAttribute("logAdmin");
	    	sessione.removeAttribute("emailAdmin");
	    	sessione.removeAttribute("passwordAdmin");
			String url="servletaccessoadmin";
			url= response.encodeURL(url);
			response.sendRedirect(url);
			return;
		}
	}

	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		// TODO Auto-generated method stub
		doGet(request, response);
	}

}
