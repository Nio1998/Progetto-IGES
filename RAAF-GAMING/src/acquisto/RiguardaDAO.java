package acquisto;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;

import javax.sql.DataSource;



public class RiguardaDAO{
	DataSource ds;
	public RiguardaDAO(DataSource d) {
		ds=d;
	}
	
	public ArrayList<RiguardaBean> allElements(String ordinamento) throws SQLException {
		if(ordinamento==null || ordinamento=="")throw new NullPointerException("ordinamento vuoto o null");
		
		Connection connessione= ds.getConnection();
		String query=null;
		
		if(ordinamento.equals("prodotto asc"))
			query="SELECT * FROM riguarda ORDER BY prodotto asc;";
	else if(ordinamento.equals("prodotto desc"))
		query="SELECT * FROM riguarda ORDER BY prodotto desc;";
	else if(ordinamento.equals("ordine asc"))
		query="SELECT * FROM riguarda ORDER BY ordine asc;";
	else if(ordinamento.equals("ordine desc"))
		query="SELECT * FROM riguarda ORDER BY ordine desc;";
	else if(ordinamento.equals("quantita_acquistata asc"))
		query="SELECT * FROM riguarda ORDER BY quantita_acquistata asc;";
	else if(ordinamento.equals("quantita_acquistata desc"))
		query="SELECT * FROM riguarda ORDER BY quantita_acquistata desc;";
	else
		throw new SQLException("Invalid ordinamento");
		
		PreparedStatement ps=connessione.prepareStatement(query);
		ArrayList<RiguardaBean>a=new ArrayList<RiguardaBean>();
		ResultSet risultato=ps.executeQuery();
		while(risultato.next()) {
			RiguardaBean app=new RiguardaBean();
			app.setOrdine(risultato.getString("riguarda.ordine"));
			app.setProdotto(risultato.getInt("riguarda.prodotto"));
			app.setQuantita_acquistata(risultato.getInt("riguarda.quantita_acquistata"));
			a.add(app);
		}
		
		risultato.close();
		ps.close();
		connessione.close();
		
		return a;
	}

	
	public void newInsert(RiguardaBean item) throws SQLException {
		if(item==null)
			throw new NullPointerException("l'item e' null");
		else {
			Connection con = ds.getConnection();
			String str = "insert into riguarda values(?,?,?);";
			PreparedStatement ps = con.prepareStatement(str);
			ps.setInt(1,item.getProdotto());
			ps.setString(2,item.getOrdine());
			ps.setInt(3, item.getQuantita_acquistata());
			ps.executeUpdate();
			ps.close();
			con.close();
		}
	}


}
