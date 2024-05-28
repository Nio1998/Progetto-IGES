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

import magazzino.PresenteInBean;
import magazzino.PresenteInDAO;
import profilo.ClienteBean;
import profilo.ClienteDAO;


@WebServlet("/servletprodotto")
public class ServletProdotto extends HttpServlet {
	private static final long serialVersionUID = 1L;
       

    public ServletProdotto() {
        super();
    }

	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
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
				request.setAttribute("loggato",false);
			}
			
			if(log == true)
			{
				request.setAttribute("loggato",true);
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
				
				ClienteDAO cli = new ClienteDAO((DataSource)super.getServletContext().getAttribute("DataSource"));
				ClienteBean nomeCli=null;
				try {
					nomeCli = cli.ricercaPerChiave((String)session.getAttribute("emailSession"));
				} catch (SQLException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
				request.setAttribute("nomeCliente",nomeCli.getNome()+" "+nomeCli.getCognome());
			}
			else
			{
				request.setAttribute("loggato",false);
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
		
		String id = request.getParameter("id");
		if(id==null || id.equals(""))
		{
			String message="PRODOTTO NON ESISTENTE!";
			request.setAttribute("message", message);
			request.setAttribute("visitato", "");
			String url="/WEB-INF/classes/prodotto/paginaGioco.jsp";
			url= response.encodeURL(url);
			RequestDispatcher dispatcher= request.getRequestDispatcher(url);
			dispatcher.forward(request, response);
			return;
		}
		else {
			DataSource ds = (DataSource)super.getServletContext().getAttribute("DataSource");
			RecensisceDAO rec = new RecensisceDAO(ds);
			try {
			ArrayList<RecensisceBean> arrayrec = rec.ricercaPerProdotto(Integer.parseInt(id));
			ClienteDAO cli = new ClienteDAO(ds);
			
			
			ArrayList<String> cliente = new ArrayList<String>();
			for(RecensisceBean b : arrayrec)
			{
				ClienteBean bean = cli.ricercaPerChiave(b.getCliente());
				cliente.add(bean.getNome()+" "+bean.getCognome()+" voto:"+b.getVoto()+" :"+b.getCommento());
			}
			request.setAttribute("clienti", cliente);
			
			} catch (SQLException e1) {
				// TODO Auto-generated catch block
				e1.printStackTrace();
			}
			
			
			//-------------------------------------------//
			ProdottoDAO dao = new ProdottoDAO(ds);
			try {
	
				ProdottoBean prod = dao.ricercaPerChiave(id);
				
				if(prod==null)//prodotto non esiste nel db
				{
					String message="PRODOTTO NON ESISTENTE!";
					request.setAttribute("message", message);
					request.setAttribute("visitato", "");
					String url="/WEB-INF/classes/prodotto/paginaGioco.jsp";
					url= response.encodeURL(url);
					RequestDispatcher dispatcher= request.getRequestDispatcher(url);
					dispatcher.forward(request, response);
					return;
				}
				else {//esiste il prodotto nel db e vedo se c'è in magazzino
					
					PresenteInDAO present = new PresenteInDAO(ds);
					ArrayList<PresenteInBean> presente = present.ricercaPerProdotto(id);
		
					request.setAttribute("presente",null);
					for(PresenteInBean l : presente)
					{
						if(l.getQuantita_disponibile()>0)
						{
							request.setAttribute("presente","");
							break;
						}
					}
					
					VideogiocoDAO vdao = new VideogiocoDAO(ds);
					VideogiocoBean video = vdao.ricercaPerChiave(""+prod.getCodice_prodotto());
					if(video!=null)
					{
						request.setAttribute("tipo","videogioco");
						request.setAttribute("videogioco",video);
						request.setAttribute("Prodotto",prod);
						request.setAttribute("visitato","");
						String url="/WEB-INF/classes/prodotto/paginaGioco.jsp";
						url= response.encodeURL(url);
						request.getRequestDispatcher(url).forward(request, response);
						return;
					}
					else
					{
						ConsoleDAO cons = new ConsoleDAO(ds);
						ConsoleBean console = cons.ricercaPerChiave(""+prod.getCodice_prodotto());
						if(console!=null)
						{
							request.setAttribute("tipo","console");
							request.setAttribute("console",console);
							request.setAttribute("Prodotto",prod);
							request.setAttribute("visitato","");
							String url="/WEB-INF/classes/prodotto/paginaGioco.jsp";
							url= response.encodeURL(url);
							request.getRequestDispatcher(url).forward(request, response);
							return;
						}
						else
						{
							AbbonamentoDAO abb = new AbbonamentoDAO(ds);
							AbbonamentoBean abbo = abb.ricercaPerChiave(""+prod.getCodice_prodotto());
							if(abbo!=null)
							{
								request.setAttribute("tipo","abbonamento");
								request.setAttribute("abbonamento",abbo);
								request.setAttribute("Prodotto",prod);
								request.setAttribute("visitato","");
								String url="/WEB-INF/classes/prodotto/paginaGioco.jsp";
								url= response.encodeURL(url);
								request.getRequestDispatcher(url).forward(request, response);
								return;
							}
							else
							{
								DlcDAO dl = new DlcDAO(ds);
								DlcBean dlc = dl.ricercaPerChiave(""+prod.getCodice_prodotto());
								if(dlc!=null)
								{
									request.setAttribute("tipo","dlc");
									request.setAttribute("dlc",dlc);
									request.setAttribute("Prodotto",prod);
									request.setAttribute("visitato","");
									String url="/WEB-INF/classes/prodotto/paginaGioco.jsp";
									url= response.encodeURL(url);
									request.getRequestDispatcher(url).forward(request, response);
									return;
								}
								else
								{
									String message="PRODOTTO NON ESISTENTE!";
									request.setAttribute("message", message);
									request.setAttribute("visitato", "");
									String url="/WEB-INF/classes/prodotto/paginaGioco.jsp";
									url= response.encodeURL(url);
									RequestDispatcher dispatcher= request.getRequestDispatcher(url);
									dispatcher.forward(request, response);
									return;
								}
							}
						}
					}
				}
				
				
			} catch (SQLException e) {
				e.printStackTrace();
			}
		}
		
		}
	}


	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		this.doGet(request, response);
	}

}
