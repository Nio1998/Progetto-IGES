package acquisto;

import java.sql.Connection;
import java.sql.Date;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;

import javax.sql.DataSource;



public class SpeditoDAO{

	DataSource ds;
	public SpeditoDAO(DataSource d) {
		ds=d;
	}
	public SpeditoBean ricercaPerChiave(String id) throws SQLException {
		if(id==null || id.equals(""))
			throw new NullPointerException("l'id e' nulla o e' stringa vuota");
		else {
			Connection connessione = ds.getConnection();
			String query="SELECT * FROM spedito WHERE ordine=?;";
			PreparedStatement ps= connessione.prepareStatement(query);
			ps.setString(1, id);
			
			ResultSet risultato= ps.executeQuery();
			SpeditoBean app= null;
			while(risultato.next()) {
				app= new SpeditoBean();
				app.setOrdine(risultato.getString("ordine"));
				app.setData_consegna(risultato.getDate("data_consegna"));
				app.setCorriere_esprersso(risultato.getString("corriere_espresso"));
			}
			risultato.close();
			ps.close();
			connessione.close();
			return app;
		}
	}


	public ArrayList<SpeditoBean> allElements(String ordinamento) throws SQLException {
		if(ordinamento==null || ordinamento=="")throw new NullPointerException("ordinamento vuoto o null");
		Connection connesione=ds.getConnection();
		String query=null;
		
		if(ordinamento.equals("ordine asc"))
			query="SELECT * FROM spedito ORDER BY ordine asc;";
	else if(ordinamento.equals("ordine desc"))
		query="SELECT * FROM spedito ORDER BY ordine desc;";
	else if(ordinamento.equals("corriere_espresso asc"))
		query="SELECT * FROM spedito ORDER BY corriere_espresso asc;";
	else if(ordinamento.equals("corriere_espresso desc"))
		query="SELECT * FROM spedito ORDER BY corriere_espresso desc;";
	else if(ordinamento.equals("data_consegna asc"))
		query="SELECT * FROM spedito ORDER BY data_consegna asc;";
	else if(ordinamento.equals("data_consegna desc"))
		query="SELECT * FROM spedito ORDER BY data_consegna desc;";
	else
		throw new SQLException("Invalid ordinamento");
		
		PreparedStatement ps=connesione.prepareStatement(query);
		
		ArrayList<SpeditoBean> a=new ArrayList<SpeditoBean>();
		ResultSet risultato=ps.executeQuery();
		while(risultato.next()) {
			SpeditoBean app=new SpeditoBean();
			app.setOrdine(risultato.getString("spedito.ordine"));
			app.setCorriere_esprersso(risultato.getString("spedito.corriere_espresso"));
			app.setData_consegna(risultato.getDate("spedito.data_consegna"));
			a.add(app);
		}
		risultato.close();
		ps.close();
		connesione.close();
		
		return a;
	}


	public void newInsert(SpeditoBean item) throws SQLException {
		if(item==null)
			throw new NullPointerException("l'item e' null");
		else {
			Connection connessione= ds.getConnection();
			String query="INSERT INTO spedito VALUES(?,?,?);";
			PreparedStatement ps= connessione.prepareStatement(query);
			ps.setString(1, item.getOrdine());
			ps.setString(2, item.getCorriere_espresso());
			System.out.println(item.getData_consegna());
			ps.setString(3,  item.getData_consegna().toString());
			
			ps.executeUpdate();
			ps.close();
			connessione.close();
			return;
		}
	}

}
