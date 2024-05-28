package prodotto;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;

import javax.sql.DataSource;



public class ParteDiDAO{
	DataSource ds = null;
	
	public ParteDiDAO(DataSource ds)
	{
		this.ds = ds;
	}


	public ParteDiBean ricercaPerChiave(int id1,String id2) throws SQLException,NullPointerException{
		if(id1<0 || id2==null)throw new NullPointerException("id1 non valido o id2 null");
		Connection con = ds.getConnection();
		String str = "SELECT * FROM parte_di WHERE videogioco=? AND categoria=? ;";
		PreparedStatement ps = con.prepareStatement(str);
		ps.setInt(1,id1);
		ps.setString(2, id2);
		ResultSet st = ps.executeQuery();
		ParteDiBean bean = new ParteDiBean();
		while(st.next())
		{
			bean.setCategoria(st.getString("categoria"));
			bean.setVideogioco(st.getInt("videogioco"));
		}
		
		st.close();
		ps.close();
		con.close();
		return bean;
	}


	public ArrayList<ParteDiBean> allElements(String ordinamento) throws SQLException {
		if(ordinamento==null || ordinamento=="")throw new NullPointerException("ordinamento vuoto o null");
		Connection con = ds.getConnection();
		
		String query=null;
		
		if(ordinamento.equals("videogioco asc"))
			query="SELECT * FROM parte_di ORDER BY  videogioco asc";
		else if(ordinamento.equals("videogioco desc"))
			query="SELECT * FROM parte_di ORDER BY videogioco desc";
		else if(ordinamento.equals("categoria asc"))
			query="SELECT * FROM parte_di ORDER BY  categoria asc";
		else if(ordinamento.equals("categoria desc"))
			query="SELECT * FROM parte_di ORDER BY categoria desc";

		else
			throw new SQLException("ordinamento scritto in modo errato");
	
		PreparedStatement ps = con.prepareStatement(query);	
			
		ResultSet st = ps.executeQuery();
		ArrayList<ParteDiBean> array = new ArrayList<ParteDiBean>();
		while(st.next())
		{
			ParteDiBean bean = new ParteDiBean();
			bean.setCategoria(st.getString("categoria"));
			bean.setVideogioco(st.getInt("videogioco"));
			array.add(bean);
		}
		st.close();
		ps.close();
		con.close();
		return array;
	}


	public void newInsert(ParteDiBean item) throws SQLException {
		if(item==null)throw new NullPointerException("item null");
		Connection connessione= ds.getConnection();
		String query="INSERT INTO parte_di VALUES(?,?);";
		PreparedStatement ps= connessione.prepareStatement(query);
		ps.setInt(1, item.getVideogioco());
		ps.setString(2, item.getCategoria());
		ps.executeUpdate();
		ps.close();
		connessione.close();
	}


	
	public ArrayList<ParteDiBean> ricercaPerCategoria(String id) throws SQLException,NullPointerException {
		if(id==null || id=="")throw new NullPointerException("id null o vuoto");
		Connection con = ds.getConnection();
		String str = "SELECT * FROM parte_di WHERE categoria=?;";
		PreparedStatement ps = con.prepareStatement(str);
		ps.setString(1,id);
		ResultSet st = ps.executeQuery();
		ArrayList<ParteDiBean> array = new ArrayList<ParteDiBean>();
		while(st.next())
		{
			ParteDiBean bean = new ParteDiBean();
			bean.setCategoria(st.getString("categoria"));
			bean.setVideogioco(st.getInt("videogioco"));
			array.add(bean);
		}
		
		st.close();
		ps.close();
		con.close();
		return array;
	}

}
