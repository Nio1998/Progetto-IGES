package acquisto;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.PrintWriter;
import java.net.URLDecoder;
import java.util.ArrayList;

import javax.servlet.RequestDispatcher;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

import org.json.JSONObject;


@WebServlet("/servleteliminacarrello")
public class ServletEliminaCarrello extends HttpServlet {
	private static final long serialVersionUID = 1L;

	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		this.doPost(request, response);
	}


	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		response.setContentType("text/html");
		HttpSession session = request.getSession(true);
		synchronized(session)
		{
			ArrayList<String> carrello = (ArrayList<String>) session.getAttribute("carrello");
			
			if(carrello==null)
			{
				String url="servletcarrello";
				url=response.encodeURL(url);
				response.sendRedirect(url);
				return;
			}
			else
			{
				if(carrello.size()==0)
				{
					session.setAttribute("carrello",null);
					String url="servletcarrello";
					url=response.encodeURL(url);
					response.sendRedirect(url);
					return;
				}
					String id = request.getParameter("id");
					for(String str : carrello)
					{
						if(str.equals(id))
						{
							carrello.remove(str);
							if(carrello.size()==0)
							{
								session.setAttribute("carrello",null);
								String url="servletcarrello";
								url=response.encodeURL(url);
								response.sendRedirect(url);
								return;
							}
							else {
								session.setAttribute("carrello",carrello);
								String url="servletcarrello";
								url=response.encodeURL(url);
								response.sendRedirect(url);
								return;
							}
						}
					}
					String url="servletcarrello";
					url=response.encodeURL(url);
					response.sendRedirect(url);
					return;
			}
		}
	}

}
