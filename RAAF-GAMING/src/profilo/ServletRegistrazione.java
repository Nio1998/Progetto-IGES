package profilo;

import java.io.IOException;


import java.sql.SQLException;
import java.util.ArrayList;
import java.util.Random;

import javax.servlet.RequestDispatcher;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.sql.DataSource;

@WebServlet("/ServletRegistrazione")
public class ServletRegistrazione extends HttpServlet {
	private static final long serialVersionUID = 1L;
       
   
     
    public ServletRegistrazione() {
        super();
       
    }

	
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		doPost(request,response);
	}

	
	 
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		HttpSession sessione= request.getSession(true);//controllo se esiste gia una sessione se no la creo
		synchronized(sessione)
		{
		Object logB= sessione.getAttribute("log");
		if(logB!=null) {//se il campo log esiste
			
			Boolean log= (Boolean) logB;
			if(log==true) {// se l'utente è loggato non può registrarsi
				{
					String url="servletindex";
					url=response.encodeURL(url);
					response.sendRedirect(url);
					return;
				}

			}
			else {
				//ottengo i dati dal form
				
				String nome=request.getParameter("nome");
				String cognome=request.getParameter("cognome");
				String email=request.getParameter("email");
				String codicecarta=request.getParameter("codicecarta");
				String data_scadenza=request.getParameter("data_scadenza");
				String codice_cvv=request.getParameter("codice_cvv");
				String data=request.getParameter("data");
				String password=request.getParameter("password");
				if(password==null || password.length()==0 || nome==null || nome.length()==0 || cognome==null || cognome.length()==0 || email==null || email.length()==0 || data==null || data.length()==0 || codicecarta==null || codicecarta.length()!=16 || codice_cvv==null || codice_cvv.length()==0 || data_scadenza==null || data_scadenza.length()==0)
				{
					
					request.setAttribute("visitato", "");
					String url="/WEB-INF/classes/profilo/registrazione.jsp";
					url=response.encodeURL(url);
					RequestDispatcher di = request.getRequestDispatcher(url);
					di.forward(request, response);
					return;
					
				}
				
				
				java.sql.Date nuovaData = java.sql.Date.valueOf(data);
				java.sql.Date nuovaDatacarta = java.sql.Date.valueOf(data_scadenza);
				
				DataSource ds = (DataSource)super.getServletContext().getAttribute("DataSource");
				ClienteDAO cmd = new ClienteDAO(ds);
				
				try {
					ClienteBean cb=cmd.ricercaPerChiave(email);
					if(cb!=null) { //il cliente e' gia registrato
						String message="Sei gia' iscritto al nostro sito!";
						request.setAttribute("message", message);
						
						request.setAttribute("visitato", "");
						
						String url = "/WEB-INF/classes/profilo/registrazione.jsp";
						url = response.encodeURL(url);
						RequestDispatcher dispatcher=super.getServletContext().getRequestDispatcher(url);//ritorno alla registrazione
						dispatcher.forward(request, response);
					}else {
						//Inserisco la carta di credito e vedo se si puo regostrare con quella carta
						CartaDiCreditoDAO cc = new CartaDiCreditoDAO(ds);
						
						CartaDiCreditoBean cartacredito = new CartaDiCreditoBean();
						
						cartacredito.setCodice_cvv(Integer.parseInt(codice_cvv));
						cartacredito.setData_scadenza(nuovaDatacarta);
						cartacredito.setCodicecarta(codicecarta);
						
						try {
						cc.newInsert(cartacredito);
						}
						catch (SQLException e)
						{
							String message="Non puoi registrarti con questa carta";
							request.setAttribute("message", message);
							request.setAttribute("visitato", "");
							String url = "/WEB-INF/classes/profilo/registrazione.jsp";
							url = response.encodeURL(url);
							RequestDispatcher dispatcher=super.getServletContext().getRequestDispatcher(url);//ritorno alla registrazione
							dispatcher.forward(request, response);
							return;
						}
						
						
						CartaFedeltaDAO cf = new CartaFedeltaDAO(ds);
						ArrayList<CartaFedeltaBean>carte=cf.allElements("codice desc");//otteniamo tutte le carte fedelta in ordine decrescente
						CartaFedeltaBean carta = new CartaFedeltaBean();
						Random ran= new Random();
						int i=0;
						String nuovaCarta = null;
						String codice = null;
						while(i<carte.size())
						{
							if(i==0)
							{
								codice = ""+ran.nextInt(999999999);
								nuovaCarta=codice;
							}
							if(codice.equals(carte.get(i).getCodice()))
								i=0;
							else
								i++;
						}
						
						carta.setCodice(nuovaCarta);
						carta.setPunti(0);
						cf.newInsert(carta);
						ClienteBean nuovoCliente=new ClienteBean(); //creiamo il nuovo utente
						nuovoCliente.setNome(nome);
						nuovoCliente.setCognome(cognome);
						nuovoCliente.setEmail(email);
						nuovoCliente.setCartadicredito(codicecarta);
						nuovoCliente.setData_di_nascita(nuovaData);//da correggere
						nuovoCliente.setPassword(password);
						nuovoCliente.setCarta_fedelta(nuovaCarta);
						cmd.newInsert(nuovoCliente);
						String url="servletloginfirst";
						url=response.encodeURL(url);
						response.sendRedirect(url);
						return;
					}
				} catch (SQLException e) {
					e.printStackTrace();
				}
			}
		}
		else {//se non è loggato
			//ottengo i dati dal form

			String nome=request.getParameter("nome");
			String cognome=request.getParameter("cognome");
			String email=request.getParameter("email");
			String codicecarta=request.getParameter("codicecarta");
			String data_scadenza=request.getParameter("data_scadenza");
			String codice_cvv=request.getParameter("codice_cvv");
			String data=request.getParameter("data");
			String password=request.getParameter("password");
			if(password==null || password.length()==0 || nome==null || nome.length()==0 || cognome==null || cognome.length()==0 || email==null || email.length()==0 || data==null || data.length()==0 || codicecarta==null || codicecarta.length()!=16 || codice_cvv==null || codice_cvv.length()==0 || data_scadenza==null || data_scadenza.length()==0)
			{
				
				request.setAttribute("visitato", "");
				String url="/WEB-INF/classes/profilo/registrazione.jsp";
				url=response.encodeURL(url);
				RequestDispatcher di = request.getRequestDispatcher(url);
				di.forward(request, response);
				return;
				
			}
			
			
			java.sql.Date nuovaData = java.sql.Date.valueOf(data);
			java.sql.Date nuovaDatacarta = java.sql.Date.valueOf(data_scadenza);
			
			DataSource ds = (DataSource)super.getServletContext().getAttribute("DataSource");
			ClienteDAO cmd = new ClienteDAO(ds);
			
			try {
				ClienteBean cb=cmd.ricercaPerChiave(email);
				if(cb!=null) { //il cliente e' gia registrato
					String message="Sei gia' iscritto al nostro sito!";
					request.setAttribute("message", message);
					
					request.setAttribute("visitato", "");
					
					String url = "/WEB-INF/classes/profilo/registrazione.jsp";
					url = response.encodeURL(url);
					RequestDispatcher dispatcher=super.getServletContext().getRequestDispatcher(url);//ritorno alla registrazione
					dispatcher.forward(request, response);
				}else {
					//Inserisco la carta di credito e vedo se si puo regostrare con quella carta
					CartaDiCreditoDAO cc = new CartaDiCreditoDAO(ds);
					
					CartaDiCreditoBean cartacredito = new CartaDiCreditoBean();
					
					cartacredito.setCodice_cvv(Integer.parseInt(codice_cvv));
					cartacredito.setData_scadenza(nuovaDatacarta);
					cartacredito.setCodicecarta(codicecarta);
					
					try {
					cc.newInsert(cartacredito);
					}
					catch (SQLException e)
					{
						String message="Non puoi registrarti con questa carta";
						request.setAttribute("message", message);
						request.setAttribute("visitato", "");
						String url = "/WEB-INF/classes/profilo/registrazione.jsp";
						url = response.encodeURL(url);
						RequestDispatcher dispatcher=super.getServletContext().getRequestDispatcher(url);//ritorno alla registrazione
						dispatcher.forward(request, response);
						return;
					}
					
					
					CartaFedeltaDAO cf = new CartaFedeltaDAO(ds);
					ArrayList<CartaFedeltaBean>carte=cf.allElements("codice desc");//otteniamo tutte le carte fedelta in ordine decrescente
					CartaFedeltaBean carta = new CartaFedeltaBean();
					Random ran= new Random();
					int i=0;
					String nuovaCarta = null;
					String codice = null;
					while(i<carte.size())
					{
						if(i==0)
						{
							codice = ""+ran.nextInt(999999999);
							nuovaCarta=codice;
						}
						if(codice.equals(carte.get(i).getCodice()))
							i=0;
						else
							i++;
					}
					
					carta.setCodice(nuovaCarta);
					carta.setPunti(0);
					cf.newInsert(carta);
					ClienteBean nuovoCliente=new ClienteBean(); //creiamo il nuovo utente
					nuovoCliente.setNome(nome);
					nuovoCliente.setCognome(cognome);
					nuovoCliente.setEmail(email);
					nuovoCliente.setCartadicredito(codicecarta);
					nuovoCliente.setData_di_nascita(nuovaData);//da correggere
					nuovoCliente.setPassword(password);
					nuovoCliente.setCarta_fedelta(nuovaCarta);
					cmd.newInsert(nuovoCliente);
					String url="servletloginfirst";
					url=response.encodeURL(url);
					response.sendRedirect(url);
					return;
				}
			} catch (SQLException e) {
				e.printStackTrace();
			}
		}
				
		}
	}
}
