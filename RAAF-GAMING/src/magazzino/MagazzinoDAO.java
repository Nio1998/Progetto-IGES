package magazzino;

import java.sql.Connection;

import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;

import javax.sql.DataSource;


public class MagazzinoDAO{
	
	DataSource ds = null;
	
	public MagazzinoDAO(DataSource ds)
	{
		this.ds = ds;
	}

	public MagazzinoBean  ricercaPerChiave(String id) throws SQLException,NullPointerException {
		if(id==null || id=="")throw new NullPointerException("id null o stringa vuota");
		Connection con = ds.getConnection();
		String str = "SELECT * FROM magazzino WHERE indirizzo=? ;";
		PreparedStatement ps = con.prepareStatement(str);
		ps.setString(1,id);
		ResultSet st = ps.executeQuery();
		MagazzinoBean bean = null;
		if(st.next())
		{
			bean= new MagazzinoBean();
			bean.setIndirizzo(st.getString("indirizzo"));
			bean.setCapienza(st.getInt("capienza"));
		}
		
		st.close();
		ps.close();
		con.close();
		
		return bean;
		
	}

	public ArrayList<MagazzinoBean> allElements(String ordinamento) throws SQLException {
		if(ordinamento==null || ordinamento=="")throw new NullPointerException("ordinamento vuoto o null");
		Connection con = ds.getConnection();
		String str;
		
		if(ordinamento.equals("indirizzo asc"))
			str = "SELECT * FROM magazzino ORDER BY indirizzo asc;";
		else if(ordinamento.equals("indirizzo desc"))
			str = "SELECT * FROM magazzino ORDER BY indirizzo desc;";
		else if(ordinamento.equals("capienza asc"))
			str = "SELECT * FROM magazzino ORDER BY capienza asc;";
		else if(ordinamento.equals("capienza desc"))
			str = "SELECT * FROM magazzino ORDER BY capienza desc;";
		else
			throw new SQLException("ordinamento scritto in modo errato");
		
		PreparedStatement ps = con.prepareStatement(str);
	

		ResultSet st = ps.executeQuery();
		ArrayList<MagazzinoBean> array = new ArrayList<MagazzinoBean>();
		while(st.next())
		{
			MagazzinoBean bean = new MagazzinoBean();
			bean.setIndirizzo(st.getString("indirizzo"));
			bean.setCapienza(st.getInt("capienza"));
			array.add(bean);
		}
		
		st.close();
		ps.close();
		con.close();
		return array;
	}
}
