package profilo;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import javax.sql.DataSource;

public class CartaFedeltaDAO {

	DataSource ds=null;
	
	public CartaFedeltaDAO(DataSource d) {
	ds=d;
	}



	public CartaFedeltaBean ricercaPerChiave(String id) throws SQLException,NullPointerException {
		
		if(id==null || id=="")
			throw new NullPointerException("Inserito un id null o vuoto");
		
		Connection connessione= ds.getConnection();
		String query="SELECT * FROM cartafedelta WHERE codice= ?;";
		PreparedStatement ps= connessione.prepareStatement(query);
		ps.setString(1,id);
		ResultSet risultato= ps.executeQuery();
		while(risultato.next()) {
			CartaFedeltaBean app= new CartaFedeltaBean();
			app.setCodice(risultato.getString("codice"));
			app.setPunti(risultato.getInt("punti"));
			risultato.close();
			ps.close();
			connessione.close();
			return app;
		}
		risultato.close();
		ps.close();
		connessione.close();
		return null;
	}


	public ArrayList<CartaFedeltaBean> allElements(String ordinamento) throws SQLException,NullPointerException {
		
		if(ordinamento==null || ordinamento=="")
			throw new NullPointerException("Inserito un ordinamento null o vuoto");
		
		Connection connessione= ds.getConnection();
		String query=null;
		if(ordinamento.equals("codice asc"))
			query="SELECT * FROM cartafedelta ORDER BY codice asc ";
		else if(ordinamento.equals("codice desc"))
			query="SELECT * FROM cartafedelta ORDER BY codice desc ";
		else if(ordinamento.equals("punti asc"))
			query="SELECT * FROM cartafedelta ORDER BY punti asc ";
		else if(ordinamento.equals("punti desc"))
			query="SELECT * FROM cartafedelta ORDER BY punti desc ";
		PreparedStatement ps= connessione.prepareStatement(query);
		ArrayList<CartaFedeltaBean> a= new ArrayList<CartaFedeltaBean>();
		ResultSet risultato= ps.executeQuery();
		while(risultato.next()) {
			CartaFedeltaBean app= new CartaFedeltaBean();
			app.setCodice(risultato.getString("codice"));
			app.setPunti(risultato.getInt("punti"));
			a.add(app);
		}
		risultato.close();
		ps.close();
		connessione.close();
		return a;
	}
	

	public void newInsert(CartaFedeltaBean item) throws SQLException,NullPointerException {
		
		if(item==null)
			throw new NullPointerException("Inserito un item null");
		
		Connection connessione = ds.getConnection();//ottengo la connessione
		String query="INSERT INTO cartafedelta VALUES(?,?);";
		PreparedStatement ps= connessione.prepareStatement(query);
		ps.setString(1, item.getCodice());
		ps.setInt(2, item.getPunti());
		ps.executeUpdate();
		ps.close();
		connessione.close();
	}
	

	public void doUpdate(CartaFedeltaBean item) throws SQLException,NullPointerException{
		
		if(item.getCodice()==null)
			throw new NullPointerException("Inserito un id null o vuoto");
		
		Connection con = ds.getConnection();
		String str = "UPDATE cartafedelta SET punti=punti+1 WHERE codice=?";
		PreparedStatement ps = con.prepareStatement(str);
		ps.setString(1,item.getCodice());
		ps.executeUpdate();
		ps.close();
		con.close();
	}
	

}