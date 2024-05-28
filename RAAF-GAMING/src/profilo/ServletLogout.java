package profilo;

import java.io.IOException;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

/**
 * 
 * Servlet per invalidare la sessione
 *
 */

@WebServlet("/servletlogout")
public class ServletLogout extends HttpServlet {
	private static final long serialVersionUID = 1L;
       
    public ServletLogout() {
        super();
        // TODO Auto-generated constructor stub
    }

    protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
    	HttpSession sessione= request.getSession(true);//ottengo la sessione
    	Object logB= sessione.getAttribute("log");
    	if(logB==null) {
    		String url="servletindex";
    		url= response.encodeURL(url);
    		response.sendRedirect(url);
    		return;
    	}
    	else{
	    	sessione.removeAttribute("log");
	    	sessione.removeAttribute("emailSession");
	    	sessione.removeAttribute("passwordSession");
	    	String url="servletindex";
			url=response.encodeURL(url);
			response.sendRedirect(url);
			return;
    	}
	}

	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		doGet(request, response);
	}

}
