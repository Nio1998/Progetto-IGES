package prodotto;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.PrintWriter;
import java.net.URLDecoder;
import java.sql.SQLException;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import javax.sql.DataSource;

import org.json.JSONObject;

@WebServlet("/servletrecensione")
public class ServletRecensione extends HttpServlet {
	private static final long serialVersionUID = 1L;
       

    public ServletRecensione() {
        super();

    }


	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		this.doPost(request, response);
	}


	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		response.setContentType("application/json");
		HttpSession session = request.getSession(true);
		synchronized(session)
		{
			Object logB= session.getAttribute("log");
			if(logB==null) {
				JSONObject mess = new JSONObject();
				mess.put("recensione","Effettua l'accesso per recensire!");
				PrintWriter out = response.getWriter();
				out.print(mess.toString());
				return;
			}
			else
			{
				boolean log = (Boolean) logB;
				if(log==true)
				{
					String email = (String) session.getAttribute("emailSession");
					BufferedReader body= request.getReader();
					StringBuffer jb = new StringBuffer();
					String campo="";
					if((campo=body.readLine())!=null){
						jb.append(campo);
					}
					String app = jb.toString();
					String obj= URLDecoder.decode(app, "utf-8");
					JSONObject json = new JSONObject(obj);
					
					int voto = json.getInt("voto");
					int id = json.getInt("id");
					String commento=json.getString("commento");
					DataSource ds = (DataSource)super.getServletContext().getAttribute("DataSource");
					RecensisceDAO dao = new RecensisceDAO(ds);
					
					try {
						if(dao.ricercaPerChiave(email,id)==null)
						{
							RecensisceBean bean = new RecensisceBean();
							bean.setCliente(email);
							bean.setProdotto(id);
							bean.setVoto(voto);
							bean.setCommento(commento);
							
							try {
								dao.newInsert(bean);
								JSONObject mess = new JSONObject();
								mess.put("recensione","Recensione effettuata con voto "+voto);
								PrintWriter out = response.getWriter();
								out.print(mess.toString());
								return;
							} catch (SQLException e) {
								// TODO Auto-generated catch block
								e.printStackTrace();
							}
						}
						else
						{
							JSONObject mess = new JSONObject();
							mess.put("recensione","Hai gia effettuato una recensione per questo prodotto");
							PrintWriter out = response.getWriter();
							out.print(mess.toString());
							return;
						}
					} catch (SQLException e1) {
						// TODO Auto-generated catch block
						e1.printStackTrace();
					}
					
				}
				else
				{
					JSONObject mess = new JSONObject();
					mess.put("recensione","Effettua l'accesso per recensire!");
					PrintWriter out = response.getWriter();
					out.print(mess.toString());
					return;
				}
			}
		}
		
		
	}

}
