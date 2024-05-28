package profilo;

import java.sql.Connection;
import java.sql.Date;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

import javax.sql.DataSource;

public class CartaDiCreditoDAO {
	
	DataSource ds= null;
	
	public CartaDiCreditoDAO(DataSource d) {
		ds=d;
	}
	
	public CartaDiCreditoBean ricercaPerChiave(String id) throws SQLException,NullPointerException
	{
		if(id==null || id=="")
			throw new NullPointerException("Inserito un id null o vuoto");
		
		Connection connessione= ds.getConnection();
		String query= "SELECT * FROM cartadicredito WHERE codicecarta=?;";
		
		PreparedStatement ps= connessione.prepareStatement(query);
		ps.setString(1,id);
		ResultSet risultato= ps.executeQuery();
		if(risultato.next()) {
			CartaDiCreditoBean app= new CartaDiCreditoBean();
			app.setCodicecarta(risultato.getString("codicecarta"));
			app.setCodice_cvv(risultato.getInt("codice_cvv"));
			app.setData_scadenza(risultato.getDate("data_scadenza"));
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
		
		
		public void newInsert(CartaDiCreditoBean item) throws SQLException,NullPointerException
		{
			
			if(item==null)
				throw new NullPointerException("Inserito un item null");
			
			Connection connessione = ds.getConnection();//ottengo la connessione
			
			String query="INSERT INTO cartadicredito VALUES(?,?,?);";
			PreparedStatement ps= connessione.prepareStatement(query);
			
			ps.setString(1, item.getCodicecarta());
			ps.setDate(2,  item.getData_scadenza());
			ps.setInt(3, item.getCodice_cvv());
			
			ps.executeUpdate();
			ps.close();
			connessione.close();
		}
		
		
		public void doUpdate(CartaDiCreditoBean item, String codice) throws SQLException,NullPointerException {
			
			
			if(item==null || codice==null)
				throw new NullPointerException("Inserito un item null o codice null");				
				
			Connection connessione= ds.getConnection();//ottengo la connessione al DB
			
			String query= "UPDATE cartadicredito SET codicecarta=?,data_scadenza=?,codice_cvv=? WHERE codicecarta=? ;";
			
			PreparedStatement ps= connessione.prepareStatement(query);
			
			ps.setString(1, item.getCodicecarta());
			ps.setDate(2,  item.getData_scadenza());
			ps.setInt(3, item.getCodice_cvv());
			ps.setString(4,codice);
			
			ps.executeUpdate();
			ps.close();
			connessione.close();
		}
		
		
		
		

}
