package prodotto;

import java.sql.Connection;

import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;

import javax.sql.DataSource;

public class CategoriaDAO {

	DataSource ds;
	
	public CategoriaDAO(DataSource d) {
		ds=d;
	}


	public ArrayList<CategoriaBean> allElements(String ordinamento) throws SQLException {
		if(ordinamento==null || ordinamento=="")throw new NullPointerException("ordinamento vuoto o null");
		Connection connessione= ds.getConnection();
		
		String query=null;
		
		if(ordinamento.equals("nome asc"))
			query="SELECT * FROM categoria ORDER BY nome asc ";
		else if(ordinamento.equals("nome desc"))
			query="SELECT * FROM categoria ORDER BY nome desc ";
		
		else
			throw new SQLException("ordinamento scritto in modo errato");
		
		PreparedStatement ps= connessione.prepareStatement(query);
		
		ArrayList<CategoriaBean> a= new ArrayList<CategoriaBean>();
		ResultSet risultato= ps.executeQuery();
		while(risultato.next()) {
			CategoriaBean app= new CategoriaBean();
			app.setNome(risultato.getString("nome"));
			a.add(app);
		}
		
		risultato.close();
		ps.close();
		connessione.close();
		return a;
	}
}
