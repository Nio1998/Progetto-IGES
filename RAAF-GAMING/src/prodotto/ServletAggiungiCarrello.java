package prodotto;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.PrintWriter;
import java.net.URLDecoder;
import java.util.ArrayList;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

import org.json.JSONObject;


@WebServlet("/servletaggiungicarrello")
public class ServletAggiungiCarrello extends HttpServlet {
	private static final long serialVersionUID = 1L;
       

    public ServletAggiungiCarrello() {
        super();
        // TODO Auto-generated constructor stub
    }


	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		this.doPost(request, response);
	}


	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		response.setContentType("application/json");
		
		BufferedReader body= request.getReader();
		StringBuffer jb = new StringBuffer();
		String campo="";
		
		if((campo=body.readLine())!=null){
			jb.append(campo);
		}
			String app = jb.toString();
			String obj= URLDecoder.decode(app, "utf-8");
			JSONObject json = new JSONObject(obj);
			HttpSession session = request.getSession(true);
			synchronized(session)
			{
				ArrayList<String> carrello = (ArrayList<String>)session.getAttribute("carrello");
				
				if(carrello==null)
					{
						carrello = new ArrayList<String>();
						carrello.add(json.getInt("id")+"");
						session.setAttribute("carrello", carrello);
						JSONObject mess = new JSONObject();
						mess.put("message","Aggiunta nel carrello fatta con successo!");
						PrintWriter out = response.getWriter();
						out.print(mess.toString());
						return;
					}
				else
				{
					for(String str: carrello)
					{
						if(str.equals(json.getInt("id")+""))
						{
							JSONObject mess = new JSONObject();
							mess.put("message","Hai gia questo prodotto nell carrello");
							PrintWriter out = response.getWriter();
							out.print(mess.toString());
							return;
						}
					}
					
					carrello.add(json.getInt("id")+"");
					session.setAttribute("carrello", carrello);
					JSONObject mess = new JSONObject();
					mess.put("message","Aggiunta nel carrello fatta con successo!");
					PrintWriter out = response.getWriter();
					out.print(mess.toString());
					return;
				}

			}
		}
	}
