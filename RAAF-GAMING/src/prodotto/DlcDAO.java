package prodotto;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;

import javax.sql.DataSource;



public class DlcDAO {

	DataSource ds =null;//la connessione al DB la ottengo dal Controller che se la va a prendere dal ServletContext

	
	public  DlcDAO(DataSource d)
	{
		ds=d;
	}
	
	public DlcBean ricercaPerChiave(String code) throws SQLException {
		if(code==null || code.equals(""))
			throw new NullPointerException("code e' null");
		else {
			Connection connessione = ds.getConnection();
			String Query="SELECT * FROM dlc WHERE prodotto=?;";
			
			PreparedStatement ps= connessione.prepareStatement(Query);
			ps.setString(1,code);
			
			ResultSet rs= ps.executeQuery();
			
			while(rs.next())
			{
				DlcBean app= new DlcBean();
				app.setProdotto(rs.getInt("prodotto"));
				app.setDimensione(rs.getDouble("dimensione"));
				app.setDescrizione(rs.getString("descrizione"));
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
	}

	public ArrayList<DlcBean> allElements(String ordinamento) throws SQLException {
		if(ordinamento==null || ordinamento=="")throw new NullPointerException("ordinamento vuoto o null");
		Connection connessione = ds.getConnection();
		

		String query=null;
		
		if(ordinamento.equals("prodotto asc"))
			query="SELECT * FROM dlc ORDER BY prodotto asc ";
		else if(ordinamento.equals("prodotto desc"))
			query="SELECT * FROM dlc ORDER BY prodotto desc ";
		else if(ordinamento.equals("dimensione asc"))
			query="SELECT * FROM dlc ORDER BY dimensione asc ";
		else if(ordinamento.equals("dimensione desc"))
			query="SELECT * FROM dlc ORDER BY dimensione desc ";
		else if(ordinamento.equals("descrizione asc"))
			query="SELECT * FROM dlc ORDER BY descrizione asc ";
		else if(ordinamento.equals("descrizione desc"))
			query="SELECT * FROM dlc ORDER BY descrizione desc ";
		
		else
			throw new SQLException("ordinamento scritto in modo errato");
		
		
		PreparedStatement ps= connessione.prepareStatement(query);
		
			
		
		ResultSet rs= ps.executeQuery();
		ArrayList<DlcBean> a= new ArrayList<DlcBean>();
		
		while(rs.next())
		{
		DlcBean app= new DlcBean();
		app.setProdotto(rs.getInt("prodotto"));
		app.setDimensione(rs.getDouble("dimensione"));
		app.setDescrizione(rs.getString("descrizione"));
		a.add(app);
		}
		rs.close();
		ps.close();
		connessione.close();
		return a;
	}

	public void newInsert(DlcBean item) throws SQLException {
		if(item==null)
			throw new NullPointerException("l'item e' null");
		else {
			Connection con = ds.getConnection();
			String str ="INSERT INTO dlc VALUES(?,?,?);";
			PreparedStatement ps = con.prepareStatement(str);
			
			ps.setInt(1,item.getProdotto());
			ps.setDouble(2,item.getDimensione());
			ps.setString(3,item.getDescrizione());
			
			ps.executeUpdate();
			ps.close();
			con.close();
		}
	}

	

	

}
