package prodotto;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;

import javax.sql.DataSource;



public class ProdottoDAO{
	DataSource ds = null;
	
	public ProdottoDAO(DataSource ds)
	{
		this.ds = ds;
	}
	
	public ProdottoBean ricercaPerChiave(String code) throws SQLException {
		if(code==null || code.equals(""))
			throw new NullPointerException("code e' null o stringa vuota");
		else {
			Connection con = ds.getConnection();
			String str = "SELECT * FROM prodotto WHERE codice_prodotto=? ;";
			PreparedStatement ps = con.prepareStatement(str);
			int codice= Integer.parseInt(code);
			ps.setInt(1,codice);
			ResultSet st = ps.executeQuery();
			ProdottoBean bean = null;
			if(st.next())
			{
				bean = new ProdottoBean();
				bean.setCodice_prodotto(st.getInt("codice_prodotto"));
				bean.setCopertina(st.getBytes("copertina"));
				bean.setData_uscita(st.getDate("data_uscita"));
				bean.setFornitore(st.getString("fornitore"));
				bean.setNome(st.getString("nome"));
				bean.setPrezzo(st.getDouble("prezzo"));
				bean.setQuantita_fornitura(st.getInt("quantita_fornitura"));
				bean.setSconto(st.getInt("sconto"));
				bean.setUltima_fornitura(st.getDate("data_fornitura"));
				bean.setGestore(st.getString("gestore"));
				
				st.close();
				ps.close();
				con.close();
				return bean;
			}
			st.close();
			ps.close();
			con.close();
			return bean;
		}
	}
	
	public ArrayList<ProdottoBean> ricercaPerSottostringa(String nome) throws SQLException {
		if(nome==null)
			throw new NullPointerException("nome e' null");
		else {
			Connection con = ds.getConnection();
			String str = "SELECT * FROM prodotto WHERE nome LIKE ? ;";
			PreparedStatement ps = con.prepareStatement(str);
			ps.setString(1,"%"+nome+"%");
			ResultSet st = ps.executeQuery();
			ArrayList<ProdottoBean> b = new ArrayList<ProdottoBean>();
			while(st.next())
			{
				ProdottoBean bean = new ProdottoBean();
				bean.setCodice_prodotto(st.getInt("codice_prodotto"));
				bean.setCopertina(st.getBytes("copertina"));
				bean.setData_uscita(st.getDate("data_uscita"));
				bean.setFornitore(st.getString("fornitore"));
				bean.setNome(st.getString("nome"));
				bean.setPrezzo(st.getDouble("prezzo"));
				bean.setQuantita_fornitura(st.getInt("quantita_fornitura"));
				bean.setSconto(st.getInt("sconto"));
				bean.setUltima_fornitura(st.getDate("data_fornitura"));
				bean.setGestore(st.getString("gestore"));
				b.add(bean);
			}
			st.close();
			ps.close();
			con.close();
			return b;
		}
	}
	
	public ArrayList<ProdottoBean> ricercaPerNome(String nome) throws SQLException,NullPointerException {
		if(nome==null)
			throw new NullPointerException("nome e' null");
		else {
			Connection con = ds.getConnection();
			String str = "SELECT * FROM prodotto WHERE nome=?";
			PreparedStatement ps = con.prepareStatement(str);
			ps.setString(1,nome);
			ResultSet st = ps.executeQuery();
			ArrayList<ProdottoBean> b = new ArrayList<ProdottoBean>();
			while(st.next())
			{
				ProdottoBean bean = new ProdottoBean();
				bean.setCodice_prodotto(st.getInt("codice_prodotto"));
				bean.setCopertina(st.getBytes("copertina"));
				bean.setData_uscita(st.getDate("data_uscita"));
				bean.setFornitore(st.getString("fornitore"));
				bean.setNome(st.getString("nome"));
				bean.setPrezzo(st.getDouble("prezzo"));
				bean.setQuantita_fornitura(st.getInt("quantita_fornitura"));
				bean.setSconto(st.getInt("sconto"));
				bean.setUltima_fornitura(st.getDate("data_fornitura"));
				bean.setGestore(st.getString("gestore"));
				b.add(bean);
			}
			st.close();
			ps.close();
			con.close();
			return b;
		}
	}
	
	public ProdottoBean getMax() throws SQLException
	{
		Connection con = ds.getConnection();
		String str = "select * from prodotto where codice_prodotto = (SELECT MAX(codice_prodotto) AS massimo FROM prodotto);";
		PreparedStatement ps = con.prepareStatement(str);
		ResultSet st = ps.executeQuery();
		ProdottoBean bean = new ProdottoBean();
		if(st.next())
		{
			bean.setCodice_prodotto(st.getInt("codice_prodotto"));
			bean.setCopertina(st.getBytes("copertina"));
			bean.setData_uscita(st.getDate("data_uscita"));
			bean.setFornitore(st.getString("fornitore"));
			bean.setNome(st.getString("nome"));
			bean.setPrezzo(st.getDouble("prezzo"));
			bean.setQuantita_fornitura(st.getInt("quantita_fornitura"));
			bean.setSconto(st.getInt("sconto"));
			bean.setUltima_fornitura(st.getDate("data_fornitura"));
			bean.setGestore(st.getString("gestore"));
		}
		st.close();
		ps.close();
		con.close();
		return bean;
	}

	public ArrayList<ProdottoBean> allElements(String ordinamento) throws SQLException {
		
		if(ordinamento==null || ordinamento.equals(""))
			throw new NullPointerException("ordinamento e' null o stringa vuota");
		
		Connection con = ds.getConnection();
		
		String query=null;
		
		if(ordinamento.equals("codice_prodotto asc"))
			query="SELECT * FROM prodotto ORDER BY codice_prodotto asc ";
		else if(ordinamento.equals("codice_prodotto desc"))
			query="SELECT * FROM prodotto ORDER BY codice_prodotto desc ";
		
		else if(ordinamento.equals("prezzo asc"))
			query="SELECT * FROM prodotto ORDER BY prezzo asc ";
		else if(ordinamento.equals("prezzo desc"))
			query="SELECT * FROM prodotto ORDER BY prezzo desc ";
		
		else if(ordinamento.equals("sconto asc"))
			query="SELECT * FROM prodotto ORDER BY sconto asc ";
		else if(ordinamento.equals("sconto desc"))
			query="SELECT * FROM prodotto ORDER BY sconto desc ";
		
		else if(ordinamento.equals("data_uscita asc"))
			query="SELECT * FROM prodotto ORDER BY data_uscita asc ";
		else if(ordinamento.equals("data_uscita desc"))
			query="SELECT * FROM prodotto ORDER BY data_uscita desc ";
		
		else if(ordinamento.equals("nome asc"))
			query="SELECT * FROM prodotto ORDER BY nome asc ";
		else if(ordinamento.equals("nome desc"))
			query="SELECT * FROM nome ORDER BY nome desc ";
		
		else if(ordinamento.equals("quantita_fornitura asc"))
			query="SELECT * FROM prodotto ORDER BY quantita_fornitura asc ";
		else if(ordinamento.equals("quantita_fornitura desc"))
			query="SELECT * FROM prodotto ORDER BY quantita_fornitura desc ";
		
		else if(ordinamento.equals("data_fornitura asc"))
			query="SELECT * FROM prodotto ORDER BY data_fornitura asc ";
		else if(ordinamento.equals("data_fornitura desc"))
			query="SELECT * FROM prodotto ORDER BY data_fornitura desc ";
			
			else if(ordinamento.equals("fornitore asc"))
				query="SELECT * FROM prodotto ORDER BY fornitore asc ";
			else if(ordinamento.equals("fornitore desc"))
				query="SELECT * FROM prodotto ORDER BY fornitore desc ";
		
			else if(ordinamento.equals("gestore asc"))
				query="SELECT * FROM prodotto ORDER BY gestore asc ";
			else if(ordinamento.equals("gestore desc"))
				query="SELECT * FROM prodotto ORDER BY gestore desc ";
		else
			throw new SQLException("ordinamento scritto in modo errato");
		

		PreparedStatement ps = con.prepareStatement(query);

		ResultSet st = ps.executeQuery();
		ArrayList<ProdottoBean> array = new ArrayList<ProdottoBean>();
		while(st.next())
		{
			ProdottoBean bean = new ProdottoBean();
			bean.setCodice_prodotto(st.getInt("codice_prodotto"));
			bean.setCopertina(st.getBytes("copertina"));
			bean.setData_uscita(st.getDate("data_uscita"));
			bean.setFornitore(st.getString("fornitore"));
			bean.setNome(st.getString("nome"));
			bean.setPrezzo(st.getDouble("prezzo"));
			bean.setQuantita_fornitura(st.getInt("quantita_fornitura"));
			bean.setSconto(st.getInt("sconto"));
			bean.setUltima_fornitura(st.getDate("data_fornitura"));
			bean.setGestore(st.getString("gestore"));
			array.add(bean);
		}
		st.close();
		ps.close();
		con.close();
		return array;
	}


	public void newInsert(ProdottoBean item) throws SQLException {
		if(item==null)
			throw new NullPointerException("l'item e' null");
		else {
			Connection con = ds.getConnection();
			String str = "INSERT INTO prodotto VALUES(?,?,?,?,?,?,?,?,?,?);";
			PreparedStatement ps = con.prepareStatement(str);
			
			ps.setInt(1,item.getCodice_prodotto());
			ps.setDouble(2,item.getPrezzo());
			ps.setBytes(3,item.getCopertina());
			ps.setInt(4,item.getSconto());
			ps.setDate(5, item.getData_uscita());
			ps.setString(6, item.getNome());
			ps.setInt(7,item.getQuantita_fornitura());
			ps.setDate(8, item.getUltima_fornitura());
			ps.setString(9,item.getFornitore());
			ps.setString(10, item.getGestore());
			
			ps.executeUpdate();
			ps.close();
			con.close();
		}
	}

	public void doUpdate(ProdottoBean item) throws SQLException {
		if(item==null)
			throw new NullPointerException("l'item e' nulll");
		else {
			Connection con = ds.getConnection();
			String str = "UPDATE prodotto SET quantita_fornitura=? , data_fornitura=? WHERE codice_prodotto=?;";
			PreparedStatement ps = con.prepareStatement(str);
			
			ps.setInt(1,item.getQuantita_fornitura());
			ps.setDate(2,item.getUltima_fornitura());
			ps.setInt(3,item.getCodice_prodotto());
			
			ps.executeUpdate();
			ps.close();
			con.close();
		}
	}
	

}
