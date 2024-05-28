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
 * Servlet che CI FA ANDARE NELLA PAGINA DI LOGIN
 */
@WebServlet("/servletloginfirst")
public class ServletLoginFirst extends HttpServlet {
	private static final long serialVersionUID = 1L;

    public ServletLoginFirst() {
        super();
        // TODO Auto-generated constructor stub
    }


	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		doPost(request, response);
	}


	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		HttpSession sessione= request.getSession(true);//controllo se esiste già una sessione se no ne creo una nuova
		Object log1= sessione.getAttribute("log");
		if(log1!=null)
		{
			boolean log = (Boolean) log1;
			if(log==true)
			{
				String url="servletindex";
				url=response.encodeURL(url);
				response.sendRedirect(url);
				return;
			}
			
			else
			{
				String message="";
				request.setAttribute("visita",message);
				String url="/WEB-INF/classes/profilo/login.jsp";
				url=response.encodeURL(url);
				RequestDispatcher view=super.getServletContext().getRequestDispatcher(url);
				view.forward(request, response);
			}
		}
		
		else
		{
				String message="";
				request.setAttribute("visita",message);
				String url="/WEB-INF/classes/profilo/login.jsp";
				url=response.encodeURL(url);
				RequestDispatcher view=super.getServletContext().getRequestDispatcher(url);
				view.forward(request, response);
		}

	}

}
