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


@WebServlet("/servletricerca")
public class ServletRicerca extends HttpServlet {
	private static final long serialVersionUID = 1L;
       

    public ServletRicerca() {
        super();
        // TODO Auto-generated constructor stub
    }


	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		//setto navbar
		response.setContentType("text/html");
		HttpSession session = request.getSession(true);
		synchronized(session)
		{
			Object logB= session.getAttribute("log");
			boolean log;
			if(logB!=null) {
				log = (Boolean) logB;
			}
			else
			{
				log = false;
				session.setAttribute("log", log);
			}
			
			if(log == true)
			{
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
			}
			else
			{
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
			}
			ArrayList<String> carr = (ArrayList<String>) session.getAttribute("carrello");
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
		}
		String str = request.getParameter("ricerca");
		DataSource ds = (DataSource)super.getServletContext().getAttribute("DataSource");
		 ProdottoDAO dao = new  ProdottoDAO(ds);
		 ArrayList<ProdottoBean> bean = new ArrayList<ProdottoBean>();
		 try {
			bean = dao.ricercaPerSottostringa(str);
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		 System.out.println(str);
		 request.setAttribute("prodotti",bean);
	//reindirizzo la pagina
		request.setAttribute("visitato","");
		RequestDispatcher view = super.getServletContext().getRequestDispatcher(response.encodeURL("/WEB-INF/classes/prodotto/paginaRicerca.jsp"));
		view.forward(request, response);
	//
	
	}

	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		
		this.doGet(request, response);
	}

}
