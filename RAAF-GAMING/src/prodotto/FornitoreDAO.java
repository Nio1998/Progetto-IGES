package prodotto;

import java.sql.Connection;

import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;

import javax.sql.DataSource;

public class FornitoreDAO{

	DataSource ds =null;
	
	public  FornitoreDAO(DataSource d)
	{
		ds=d;
	}

	public ArrayList<FornitoreBean> allElements(String ordinamento) throws SQLException {
		if(ordinamento==null || ordinamento=="")throw new NullPointerException("ordinamento vuoto o null");
		Connection connessione = ds.getConnection();
		
		String query=null;
		
		if(ordinamento.equals("nome asc"))
			query="SELECT * FROM fornitore ORDER BY nome asc ";
		else if(ordinamento.equals("nome desc"))
			query="SELECT * FROM fornitore ORDER BY nome desc ";
		
		else if(ordinamento.equals("indirizzo asc"))
			query="SELECT * FROM fornitore ORDER BY indirizzo asc ";
		else if(ordinamento.equals("indirizzo desc"))
			query="SELECT * FROM fornitore ORDER BY indirizzo desc ";
		
		else if(ordinamento.equals("telefono asc"))
			query="SELECT * FROM fornitore ORDER BY telefono asc ";
		else if(ordinamento.equals("telefono desc"))
			query="SELECT * FROM fornitore ORDER BY telefono desc ";
		
		else
			throw new SQLException("ordinamento scritto in modo errato");
			
		PreparedStatement ps= connessione.prepareStatement(query);	
		
		ResultSet rs= ps.executeQuery();
		ArrayList<FornitoreBean> a= new ArrayList<FornitoreBean>();
		
		while(rs.next())
		{
		FornitoreBean app= new FornitoreBean();
		app.setNome(rs.getString("nome"));
		app.setIndirizzo(rs.getString("indirizzo"));
		app.setTelefono(rs.getString("telefono"));
		a.add(app);
		}
		rs.close();
		ps.close();
		connessione.close();
		return a;
	}
}
