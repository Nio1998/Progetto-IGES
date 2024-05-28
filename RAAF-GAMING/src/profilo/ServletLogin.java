package profilo;

import java.io.IOException;
import java.math.BigInteger;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.sql.SQLException;

import javax.servlet.RequestDispatcher;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.sql.DataSource;


@WebServlet("/servletlogin")
public class ServletLogin extends HttpServlet {
	private static final long serialVersionUID = 1L;
       
   
    public ServletLogin() {
        super();
        
    }

	
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		doPost(request,response);
	}

	
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		
		HttpSession sessione= request.getSession(true);//controllo se esiste già una sessione se no ne creo una nuova
		synchronized(sessione)
		{
		Object log1= sessione.getAttribute("log");
		if(log1!=null)
		{
			boolean log = (Boolean) log1;
			
			if(log==true) {
				String url="servletindex";
				url=response.encodeURL(url);
				response.sendRedirect(url);
				return;
			}		
			else {
				String email=request.getParameter("email");
				String password=request.getParameter("password");
				if(email==null && password==null)
				{
					String url="servletloginfirst";
					url=response.encodeURL(url);
					response.sendRedirect(url);
					return;
				}
					
				ClienteDAO cmd=new ClienteDAO((DataSource)super.getServletContext().getAttribute("DataSource"));
				try {
					ClienteBean cb=cmd.ricercaPerChiave(email);	//ottengo i dati dell'utente che si vuole loggare
					
					if(cb!=null) {//se l'utente è nel DB
						
						//codifico la password per vedere se è uguale a quella del DB
						MessageDigest md = MessageDigest.getInstance("MD5"); 
						byte[] digest = md.digest(password.getBytes()); 
						BigInteger big=new BigInteger(1,digest);
						String str=big.toString(16);
						while(str.length()<32) {
							str="0"+str;
						}
						if(str.equals(cb.getPassword())) {   //se la password e l'email coincidono nel DB
							System.out.println("Sei loggato");
							sessione.setAttribute("emailSession", email);//aggiungo il campo dell'email alla sessione
							sessione.setAttribute("passwordSession", str);//aggiungo la password criptata alla sessione
							log=true;
							sessione.setAttribute("log",log);
							//URL rewriting
							String url="servletindex";
							url=response.encodeURL(url);
							response.sendRedirect(url);
							return;
						}
						else {
							//LA PASSWORD NON COINCIDE
							String message="";
							request.setAttribute("message",message);
							request.setAttribute("visita","");
							String url = "/WEB-INF/classes/profilo/login.jsp";
							url = response.encodeURL(url);
							RequestDispatcher view=super.getServletContext().getRequestDispatcher(url);
							view.forward(request, response);
						}
					}
					else {
						//SE L'EMAIL NON ESISTE NEL DB
						String message="";
						request.setAttribute("message",message);
						request.setAttribute("visita","");
						String url = "/WEB-INF/classes/profilo/login.jsp";
						url = response.encodeURL(url);
						RequestDispatcher view=super.getServletContext().getRequestDispatcher(url);
						view.forward(request, response);
					}
				} catch (SQLException e) {
					e.printStackTrace();
				} catch (NoSuchAlgorithmException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			}
			
		}
		else {
			//SE LA SESSIONE NON ESISTE SI PUO' LOGGARE
			boolean log;
			String email=request.getParameter("email");
			String password=request.getParameter("password");
			if(email.equals("") && password.equals(""))
			{
				String url="servletloginfirst";
				url=response.encodeURL(url);
				response.sendRedirect(url);
				return;
			}
				
			ClienteDAO cmd=new ClienteDAO((DataSource)super.getServletContext().getAttribute("DataSource"));
			try {
				ClienteBean cb=cmd.ricercaPerChiave(email); //ottengo i dati dell'utente che si vuole loggare
				
				if(cb!=null) {//se l'utente è nel DB
					
					//codifico la password per vedere se è uguale a quella del DB
					MessageDigest md = MessageDigest.getInstance("MD5"); 
					byte[] digest = md.digest(password.getBytes()); 
					BigInteger big=new BigInteger(1,digest);
					String str=big.toString(16);
					while(str.length()<32) {
						str="0"+str;
					}
					if(str.equals(cb.getPassword())) {   //se la password e l'email coincidono nel DB
						System.out.println("Sei loggato");
						sessione.setAttribute("emailSession", email);//aggiungio il campo dell'email alla sessione
						sessione.setAttribute("passwordSession", str);//aggiungo la password criptata alla sessione
						log=true;
						sessione.setAttribute("log",log);
						//URL rewriting
						String url="servletindex";
						url=response.encodeURL(url);
						response.sendRedirect(url);
						return;
					}
					else {
						//LA PASSWORD NON COINCIDE
						String message="";
						request.setAttribute("message",message);
						request.setAttribute("visita","");
						String url="/WEB-INF/classes/profilo/login.jsp";
						url=response.encodeURL(url);
						RequestDispatcher view=super.getServletContext().getRequestDispatcher(url);
						view.forward(request, response);
					}
				}
				else {
					//SE L'EMAIL NON ESISTE NEL DB
					String message="";
					request.setAttribute("message",message);
					request.setAttribute("visita","");
					String url="/WEB-INF/classes/profilo/login.jsp";
					url=response.encodeURL(url);
					RequestDispatcher view=super.getServletContext().getRequestDispatcher(url);
					view.forward(request, response);
				}
			} catch (SQLException e) {
				e.printStackTrace();
			} catch (NoSuchAlgorithmException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}
		}
		
	}
		

}
