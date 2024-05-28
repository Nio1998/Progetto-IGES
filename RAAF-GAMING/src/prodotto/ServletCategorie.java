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

@WebServlet("/servletcategorie")
public class ServletCategorie extends HttpServlet {
	private static final long serialVersionUID = 1L;


	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		response.setContentType("text/html");
		
		String per = request.getParameter("per");
		
		if(per==null)
		{
			String url="servletindex";
			url= response.encodeURL(url);
			response.sendRedirect(url);
			return;
		}
		
		else
		{
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
					String impostazione4 ="servletlogout";
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
			DataSource ds = (DataSource)super.getServletContext().getAttribute("DataSource");
			if(per.equals("catalogo"))
			{
				ProdottoDAO dao = new  ProdottoDAO(ds);
				ArrayList<ProdottoBean> bean = new ArrayList<ProdottoBean>();
				try {
						bean = dao.allElements("codice_prodotto asc");
				} catch (SQLException e) {
					e.printStackTrace();
				}
					request.setAttribute("prodotti",bean);
					request.setAttribute("visitato","");
					RequestDispatcher view = super.getServletContext().getRequestDispatcher(response.encodeURL("/WEB-INF/classes/prodotto/paginaRicerca.jsp"));
					view.forward(request, response);
					return;
			}
			else if(per.equals("azione"))
			{
				ProdottoDAO dap = new  ProdottoDAO(ds);
				ArrayList<ProdottoBean> prod = new ArrayList<ProdottoBean>();
				ParteDiDAO dao = new ParteDiDAO(ds);
				try {
					ArrayList<ParteDiBean> bean = dao.ricercaPerCategoria("Azione");
					for(ParteDiBean b : bean)
					{
						prod.add(dap.ricercaPerChiave(b.getVideogioco()+""));
					}
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
				request.setAttribute("prodotti",prod);
				request.setAttribute("visitato","");
				RequestDispatcher view = super.getServletContext().getRequestDispatcher(response.encodeURL("/WEB-INF/classes/prodotto/paginaRicerca.jsp"));
				view.forward(request, response);
				return;
			}
			else if(per.equals("avventura"))
			{
				ProdottoDAO dap = new  ProdottoDAO(ds);
				ArrayList<ProdottoBean> prod = new ArrayList<ProdottoBean>();
				ParteDiDAO dao = new ParteDiDAO(ds);
				try {
					ArrayList<ParteDiBean> bean = dao.ricercaPerCategoria("Avventura");
					for(ParteDiBean b : bean)
					{
						prod.add(dap.ricercaPerChiave(b.getVideogioco()+""));
					}
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
				request.setAttribute("prodotti",prod);
				request.setAttribute("visitato","");
				RequestDispatcher view = super.getServletContext().getRequestDispatcher(response.encodeURL("/WEB-INF/classes/prodotto/paginaRicerca.jsp"));
				view.forward(request, response);
				return;
			}
			else if(per.equals("battleroyale"))
			{
				ProdottoDAO dap = new  ProdottoDAO(ds);
				ArrayList<ProdottoBean> prod = new ArrayList<ProdottoBean>();
				ParteDiDAO dao = new ParteDiDAO(ds);
				try {
					ArrayList<ParteDiBean> bean = dao.ricercaPerCategoria("Battle Royale");
					for(ParteDiBean b : bean)
					{
						prod.add(dap.ricercaPerChiave(b.getVideogioco()+""));
					}
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
				request.setAttribute("prodotti",prod);
				request.setAttribute("visitato","");
				RequestDispatcher view = super.getServletContext().getRequestDispatcher(response.encodeURL("/WEB-INF/classes/prodotto/paginaRicerca.jsp"));
				view.forward(request, response);
				return;
			}
			else if(per.equals("sport"))
			{
				ProdottoDAO dap = new  ProdottoDAO(ds);
				ArrayList<ProdottoBean> prod = new ArrayList<ProdottoBean>();
				ParteDiDAO dao = new ParteDiDAO(ds);
				try {
					ArrayList<ParteDiBean> bean = dao.ricercaPerCategoria("Sport");
					for(ParteDiBean b : bean)
					{
						prod.add(dap.ricercaPerChiave(b.getVideogioco()+""));
					}
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
				request.setAttribute("prodotti",prod);
				request.setAttribute("visitato","");
				RequestDispatcher view = super.getServletContext().getRequestDispatcher(response.encodeURL("/WEB-INF/classes/prodotto/paginaRicerca.jsp"));
				view.forward(request, response);
				return;
			}
			else if(per.equals("horror"))
			{
				ProdottoDAO dap = new  ProdottoDAO(ds);
				ArrayList<ProdottoBean> prod = new ArrayList<ProdottoBean>();
				ParteDiDAO dao = new ParteDiDAO(ds);
				try {
					ArrayList<ParteDiBean> bean = dao.ricercaPerCategoria("Survival horror");
					for(ParteDiBean b : bean)
					{
						prod.add(dap.ricercaPerChiave(b.getVideogioco()+""));
					}
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
				request.setAttribute("prodotti",prod);
				request.setAttribute("visitato","");
				RequestDispatcher view = super.getServletContext().getRequestDispatcher(response.encodeURL("/WEB-INF/classes/prodotto/paginaRicerca.jsp"));
				view.forward(request, response);
				return;
			}
			else if(per.equals("console"))
			{
				ProdottoDAO dap = new  ProdottoDAO(ds);
				ArrayList<ProdottoBean> prod = new ArrayList<ProdottoBean>();
				ConsoleDAO dao = new ConsoleDAO(ds);
				try {
					ArrayList<ConsoleBean> bean = dao.allElements("prodotto asc");
					for(ConsoleBean b : bean)
					{
						prod.add(dap.ricercaPerChiave(b.getProdotto()+""));
					}
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
				request.setAttribute("prodotti",prod);
				request.setAttribute("visitato","");
				RequestDispatcher view = super.getServletContext().getRequestDispatcher(response.encodeURL("/WEB-INF/classes/prodotto/paginaRicerca.jsp"));
				view.forward(request, response);
				return;
			}
			else if(per.equals("videogiochi"))
			{
				ProdottoDAO dap = new  ProdottoDAO(ds);
				ArrayList<ProdottoBean> prod = new ArrayList<ProdottoBean>();
				VideogiocoDAO dao = new VideogiocoDAO(ds);
				try {
					ArrayList<VideogiocoBean> bean = dao.allElements("prodotto asc");
					for(VideogiocoBean b : bean)
					{
						prod.add(dap.ricercaPerChiave(b.getProdotto()+""));
					}
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
				request.setAttribute("prodotti",prod);
				request.setAttribute("visitato","");
				RequestDispatcher view = super.getServletContext().getRequestDispatcher(response.encodeURL("/WEB-INF/classes/prodotto/paginaRicerca.jsp"));
				view.forward(request, response);
				return;
			}
			else if(per.equals("abbonamenti"))
			{
				ProdottoDAO dap = new  ProdottoDAO(ds);
				ArrayList<ProdottoBean> prod = new ArrayList<ProdottoBean>();
				AbbonamentoDAO dao = new AbbonamentoDAO(ds);
				try {
					ArrayList<AbbonamentoBean> bean = dao.allElements("prodotto asc");
					for(AbbonamentoBean b : bean)
					{
						prod.add(dap.ricercaPerChiave(b.getProdotto()+""));
					}
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
				request.setAttribute("prodotti",prod);
				request.setAttribute("visitato","");
				RequestDispatcher view = super.getServletContext().getRequestDispatcher(response.encodeURL("/WEB-INF/classes/prodotto/paginaRicerca.jsp"));
				view.forward(request, response);
				return;
			}
			else if(per.equals("dlc"))
			{
				ProdottoDAO dap = new  ProdottoDAO(ds);
				ArrayList<ProdottoBean> prod = new ArrayList<ProdottoBean>();
				DlcDAO dao = new DlcDAO(ds);
				try {
					ArrayList<DlcBean> bean = dao.allElements("prodotto asc");
					for(DlcBean b : bean)
					{
						prod.add(dap.ricercaPerChiave(b.getProdotto()+""));
					}
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
				request.setAttribute("prodotti",prod);
				request.setAttribute("visitato","");
				RequestDispatcher view = super.getServletContext().getRequestDispatcher(response.encodeURL("/WEB-INF/classes/prodotto/paginaRicerca.jsp"));
				view.forward(request, response);
				return;
			}
			else
			{
				String url="servletindex";
				url= response.encodeURL(url);
				response.sendRedirect(url);
				return;
			}
		}
		
	}

	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		this.doPost(request, response);
	}

}
