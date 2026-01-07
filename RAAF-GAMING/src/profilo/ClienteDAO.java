package profilo;

import java.sql.Connection;
import java.sql.Date;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;

import javax.sql.DataSource;


public class ClienteDAO {

	DataSource ds= null;//la connessione al DB la ottengo dal Controller che se la va a prendere dal ServletContext
	
	public ClienteDAO(DataSource d) {
		ds=d;
	}
	

	public ClienteBean ricercaPerChiave(String id) throws SQLException,NullPointerException {
		
		if(id==null || id=="")
			throw new NullPointerException("Inserito un id null o vuoto");
		
		Connection connessione= ds.getConnection();
		String query= "SELECT * FROM cliente WHERE email=?;";
		
		PreparedStatement ps= connessione.prepareStatement(query);
		ps.setString(1, id);
		ResultSet risultato= ps.executeQuery();
		if(risultato.next()) {
			ClienteBean app= new ClienteBean();
			app.setEmail(risultato.getString("email"));
			app.setNome(risultato.getString("nome"));
			app.setCognome(risultato.getString("cognome"));
			app.setPassword(risultato.getString("password"));
			app.setData_di_nascita(risultato.getDate("data_di_nascita"));
			app.setCartadicredito(risultato.getString("cartadicredito"));
			app.setCarta_fedelta(risultato.getString("carta_fedelta"));
			
			risultato.close();
			ps.close();
			connessione.close();
			return app;
		}
		else {
			risultato.close();
			ps.close();
			connessione.close();
			return null;
		}
	}


	public ArrayList<ClienteBean> allElements(String ordinamento) throws SQLException,NullPointerException{
		
		if(ordinamento==null || ordinamento=="")
			throw new NullPointerException("Inserito un ordinamento null o vuoto");
		
		Connection connessione= ds.getConnection();//ottengo la connessione al DB
		
		String query=null;
		
		if(ordinamento.equals("email asc"))
			query="SELECT * FROM cliente ORDER BY email asc ";
		else if(ordinamento.equals("email desc"))
			query="SELECT * FROM cliente ORDER BY email desc ";
		else if(ordinamento.equals("nome asc"))
			query="SELECT * FROM cliente ORDER BY nome asc ";
		else if(ordinamento.equals("nome desc"))
			query="SELECT * FROM cliente ORDER BY nome desc ";
		else if(ordinamento.equals("cognome asc"))
			query="SELECT * FROM cliente ORDER BY cognome asc ";
		else if(ordinamento.equals("cognome desc"))
			query="SELECT * FROM cliente ORDER BY cognome desc ";
		else if(ordinamento.equals("password asc"))
			query="SELECT * FROM cliente ORDER BY password asc ";
		else if(ordinamento.equals("password desc"))
			query="SELECT * FROM cliente ORDER BY password desc ";
		else if(ordinamento.equals("carta_fedelta asc"))
			query="SELECT * FROM cliente ORDER BY carta_fedelta asc ";
		else if(ordinamento.equals("carta_fedelta desc"))
			query="SELECT * FROM cliente ORDER BY carta_fedelta desc";
		else if(ordinamento.equals("cartadicredito asc"))
			query="SELECT * FROM cliente ORDER BY cartadicredito asc ";
		else if(ordinamento.equals("cartadicredito desc"))
			query="SELECT * FROM cliente ORDER BY cartadicredito desc ";
		
		else
			throw new SQLException("ordinamento scritto in modo errato");
		PreparedStatement ps= connessione.prepareStatement(query);
		
		ArrayList<ClienteBean> a= new ArrayList<ClienteBean>();//mi salvo tutte le righe dei clienti che ho ottenuto dalla query
		ResultSet risultato= ps.executeQuery();
		while(risultato.next()) {
		
			ClienteBean app= new ClienteBean();//creo un ClienteBean d'appoggio per salvarmi i campi della riga man mano
			app.setEmail(risultato.getString("email"));
			app.setNome(risultato.getString("nome"));
			app.setCognome(risultato.getString("cognome"));
			app.setPassword(risultato.getString("password"));
			app.setData_di_nascita(risultato.getDate("data_di_nascita"));
			app.setCartadicredito(risultato.getString("cartadicredito"));
			app.setCarta_fedelta(risultato.getString("carta_fedelta"));
			
			a.add(app);//aggiungo il bean all'arraylist
		}
		risultato.close();
		ps.close();
		connessione.close();
		return a;
	}


	public void newInsert(ClienteBean item) throws SQLException,NullPointerException{
		
		if(item==null)
			throw new NullPointerException("Inserito un item null");
		
		Connection connessione = ds.getConnection();//ottengo la connessione
		
		String query="INSERT INTO cliente VALUES(?,?,?,?,MD5(?),?,?);";
		PreparedStatement ps= connessione.prepareStatement(query);
		
		ps.setString(1, item.getEmail());
		ps.setString(2, item.getNome());
		ps.setString(3, item.getCognome());
		ps.setDate(4, (Date) item.getData_di_nascita());
		ps.setString(5, item.getPassword());
		ps.setString(6, item.getCarta_fedelta());
		ps.setString(7, item.getCartadicredito());
		
		ps.executeUpdate();//eseguo la insert del cliente
		ps.close();
		connessione.close();
	}

	/**
	 * @param item L'oggetto {@link ClienteBean} contenente i nuovi dati del cliente. 
	 * Non deve essere {@code null}
	 * @throws NullPointerException Se l'oggetto {@code item} passato come parametro è {@code null}.
	 */
	public void doUpdate(ClienteBean item) throws SQLException,NullPointerException {
		
		if(item==null)
			throw new NullPointerException("Inserito un item null");
		
		Connection connessione= ds.getConnection();//ottengo la connessione al DB
		
		String query= "UPDATE cliente SET password=MD5(?) WHERE email=? ;";
		
		PreparedStatement ps= connessione.prepareStatement(query);
		
		ps.setString(1, item.getPassword());
		ps.setString(2, item.getEmail());
		
		ps.executeUpdate();
		ps.close();
		connessione.close();
	}
	

	
}

