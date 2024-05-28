package profilo;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

import javax.sql.DataSource;

public class GestoreDAO {
	
	DataSource ds= null;
	
	public GestoreDAO(DataSource d) {
		ds=d;
	}
	
	public GestoreBean ricercaPerChiave(String id) throws SQLException,NullPointerException
	{
		if(id==null || id=="")
			throw new NullPointerException("Inserito un id null o vuoto");
		
		Connection connessione= ds.getConnection();
		String query= "SELECT * FROM gestore WHERE email=?;";
		
		PreparedStatement ps= connessione.prepareStatement(query);
		ps.setString(1,id);
		ResultSet risultato= ps.executeQuery();
		if(risultato.next()) {
			GestoreBean app= new GestoreBean();
			app.setEmail(risultato.getString("email"));
			app.setPassword(risultato.getString("password"));
			app.setRuolo(risultato.getString("ruolo"));
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

}
