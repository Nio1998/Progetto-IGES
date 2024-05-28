package prodotto;


import java.sql.Connection;

import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;

import javax.sql.DataSource;





public class RecensisceDAO{

	DataSource ds;
	
	public RecensisceDAO(DataSource d) {
		ds=d;
	}
	
	public RecensisceBean ricercaPerChiave(String cliente,int prodotto) throws SQLException,NullPointerException{
		if((cliente==null || cliente=="" || prodotto<0))throw new NullPointerException("prodotto o cliente è vuoto o null");
		Connection connessione = ds.getConnection();
		String Query="SELECT * FROM recensisce WHERE prodotto=? and cliente=?;";
		
		PreparedStatement ps= connessione.prepareStatement(Query);
		ps.setInt(1,prodotto);
		ps.setString(2,cliente);
		ResultSet rs= ps.executeQuery();		
		while(rs.next())
		{
		RecensisceBean app= new RecensisceBean();
		app.setCliente(rs.getString("cliente"));
		app.setProdotto(rs.getInt("prodotto"));
		app.setVoto(rs.getInt("voto"));
		app.setCommento(rs.getString("commento"));
		rs.close();
		ps.close();
		connessione.close();
		return app;
		}
		rs.close();
		ps.close();
		connessione.close();
		return null;

	}
	
	public ArrayList<RecensisceBean> ricercaPerProdotto(int id) throws SQLException,NullPointerException{
		if(id<0) throw new NullPointerException("id non valido");
		Connection connessione = ds.getConnection();
		String Query="SELECT * FROM recensisce WHERE prodotto=?;";
		
		PreparedStatement ps= connessione.prepareStatement(Query);
		ps.setInt(1,id);	
		ArrayList<RecensisceBean >a=new ArrayList<RecensisceBean>();
		ResultSet risultato=ps.executeQuery();
		while(risultato.next()) {
			RecensisceBean app=new RecensisceBean();
			
			app.setCliente(risultato.getString("cliente"));
			app.setProdotto(risultato.getInt("prodotto"));
			app.setVoto(risultato.getInt("voto"));
			app.setCommento(risultato.getString("commento"));
			a.add(app);
		}
		risultato.close();
		ps.close();
		connessione.close();
		
		return a;

	}


	public ArrayList<RecensisceBean> allElements(String ordinamento) throws SQLException {
	if(ordinamento==null || ordinamento=="")throw new NullPointerException("ordinamento vuoto o null");
		Connection connessione=ds.getConnection();
		
		String query=null;
		if(ordinamento.equals("cliente asc"))
			query="SELECT * FROM recensisce ORDER BY cliente asc ";
		else if(ordinamento.equals("cliente desc"))
			query="SELECT * FROM cliente ORDER BY cliente desc ";
		
		else if(ordinamento.equals("prodotto asc"))
			query="SELECT * FROM recensisce ORDER BY prodotto asc ";
		else if(ordinamento.equals("prodotto desc"))
			query="SELECT * FROM recensisce ORDER BY prodotto desc ";
		
		else if(ordinamento.equals("voto asc"))
			query="SELECT * FROM recensisce ORDER BY voto asc ";
		else if(ordinamento.equals("voto desc"))
			query="SELECT * FROM recensisce ORDER BY voto desc ";
		
		else if(ordinamento.equals("commento asc"))
			query="SELECT * FROM recensisce ORDER BY commento asc ";
		else if(ordinamento.equals("commento desc"))
			query="SELECT * FROM recensisce ORDER BY commento desc ";
		
		else
			throw new SQLException("ordinamento scritto in modo errato");		
		
		PreparedStatement ps=connessione.prepareStatement(query);
			
		ArrayList<RecensisceBean >a=new ArrayList<RecensisceBean>();
		ResultSet risultato=ps.executeQuery();
		while(risultato.next()) {
			RecensisceBean app=new RecensisceBean();
			
			app.setCliente(risultato.getString("cliente"));
			app.setProdotto(risultato.getInt("prodotto"));
			app.setVoto(risultato.getInt("voto"));
			app.setCommento(risultato.getString("commento"));
			a.add(app);
		}
		risultato.close();
		ps.close();
		connessione.close();
		
		return a;
		
	}

	
	public void newInsert(RecensisceBean item) throws SQLException {
		if(item==null) throw new NullPointerException("item è null");
		Connection connessione = ds.getConnection();
		
		String query="INSERT INTO recensisce VALUES(?,?,?,?);";
		PreparedStatement ps= connessione.prepareStatement(query);
		
		ps.setString(1,item.getCliente());
		ps.setInt(2,item.getProdotto());
		ps.setInt(3,item.getVoto());
		ps.setString(4,item.getCommento());
		
		ps.executeUpdate();
		ps.close();
		connessione.close();
		
	}
	


	


}
