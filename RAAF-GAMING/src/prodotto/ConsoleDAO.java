package prodotto;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;

import javax.sql.DataSource;



public class ConsoleDAO{
	
	DataSource ds =null;

	
	public ConsoleDAO(DataSource d)
	{
		ds=d;
	}
	
	public ConsoleBean ricercaPerChiave(String code) throws SQLException {
		if(code==null || code=="")throw new NullPointerException("code è null oppure è una stringa vuota");
		Connection connessione = ds.getConnection();
		String Query="SELECT * FROM console WHERE prodotto=?;";
		
		PreparedStatement ps= connessione.prepareStatement(Query);
		ps.setString(1,code);		
		ResultSet rs= ps.executeQuery();		
		while(rs.next())
		{
		ConsoleBean app= new ConsoleBean();
		app.setProdotto(rs.getInt("prodotto"));
		app.setSpecifica(rs.getString("specifica"));
		app.setColore(rs.getString("colore"));
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

	
	public ArrayList<ConsoleBean> allElements(String ordinamento) throws SQLException {
	if(ordinamento==null || ordinamento=="")throw new NullPointerException("ordinamento vuoto o null");	
	Connection connessione = ds.getConnection();
	
	String query=null;
	
	if(ordinamento.equals("prodotto asc"))
		query="SELECT * FROM console ORDER BY prodotto asc ";
	else if(ordinamento.equals("prodotto desc"))
		query="SELECT * FROM console ORDER BY prodotto desc ";
	else if(ordinamento.equals("specifica asc"))
		query="SELECT * FROM console ORDER BY specifica asc ";
	else if(ordinamento.equals("specifica desc"))
		query="SELECT * FROM console ORDER BY specifica desc ";
	else if(ordinamento.equals("colore asc"))
		query="SELECT * FROM console ORDER BY colore asc ";
	else if(ordinamento.equals("colore desc"))
		query="SELECT * FROM console ORDER BY colore desc ";
	
	else
		throw new SQLException("ordinamento scritto in modo errato");

	
	PreparedStatement ps= connessione.prepareStatement(query);
	

	
	ResultSet rs= ps.executeQuery();
	ArrayList<ConsoleBean> a= new ArrayList<ConsoleBean>();
	
	while(rs.next())
	{
	ConsoleBean app= new ConsoleBean();
	app.setProdotto(rs.getInt("prodotto"));
	app.setSpecifica(rs.getString("specifica"));
	app.setColore(rs.getString("colore"));
	
	a.add(app);
	}
	rs.close();
	ps.close();
	connessione.close();
	return a;
	}

	
	public void newInsert(ConsoleBean item) throws SQLException {
		if(item==null)throw new NullPointerException("item è null");
		Connection con = ds.getConnection();
		String str ="INSERT INTO console VALUES(?,?,?);";
		PreparedStatement ps = con.prepareStatement(str);
		
		ps.setInt(1,item.getProdotto());
		ps.setString(2,item.getSpecifica());
		ps.setString(3,item.getColore());
		
		ps.executeUpdate();
		ps.close();
		con.close();
		
		

	}

	

}
