package acquisto;

import java.sql.Connection;
import java.sql.Date;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Types;
import java.util.ArrayList;


import javax.sql.DataSource;

public class OrdineDAO {
	
	DataSource ds = null;
	
	public OrdineDAO(DataSource ds)
	{
		this.ds = ds;
	}

	public OrdineBean ricercaPerChiave(String id) throws SQLException,NullPointerException {
		if(id==null || id.equals(""))
			throw new NullPointerException("l'id e' nulla o e' stringa vuota");
		else {
			Connection con = ds.getConnection();
			String str = "SELECT * FROM ordine WHERE codice=? ;";
			PreparedStatement ps = con.prepareStatement(str);
			ps.setString(1,id);
			ResultSet st = ps.executeQuery();
			OrdineBean bean = null;
			while(st.next())
			{
				bean = new OrdineBean();
				bean.setCodice(st.getString("codice"));
				bean.setMetodo_di_pagamento(st.getString("metodo_di_pagamento"));
				bean.setData_acquisto(st.getDate("data_acquisto"));
				bean.setIndirizzo_di_consegna(st.getString("indirizzo_di_consegna"));
				bean.setCliente(st.getString("cliente"));
				bean.setPrezzo_totale(st.getDouble("prezzo_totale"));
				bean.setGestore(st.getString("gestore"));
				bean.setStato(st.getString("stato"));
			}
			
			st.close();
			ps.close();
			con.close();
			
			return bean;
		}
	}

	public ArrayList<OrdineBean> allElements(String ordinamento) throws SQLException {
		if(ordinamento==null || ordinamento=="")throw new NullPointerException("ordinamento vuoto o null");
		Connection con = ds.getConnection();
		String str = null;
		
		if(ordinamento.equals("codice asc"))
				str="SELECT * FROM ordine ORDER BY codice asc;";
		else if(ordinamento.equals("codice desc"))
			str="SELECT * FROM ordine ORDER BY codice desc;";
		else if(ordinamento.equals("data_acquisto asc"))
			str="SELECT * FROM ordine ORDER BY data_acquisto asc;";
		else if(ordinamento.equals("data_acquisto desc"))
			str="SELECT * FROM ordine ORDER BY data_acquisto desc;";
		else if(ordinamento.equals("indirizzo_di_consegna asc"))
			str="SELECT * FROM ordine ORDER BY indirizzo_di_consegna asc;";
		else if(ordinamento.equals("indirizzo_di_consegna desc"))
			str="SELECT * FROM ordine ORDER BY indirizzo_di_consegna desc;";
		else if(ordinamento.equals("cliente asc"))
			str="SELECT * FROM ordine ORDER BY cliente asc;";
		else if(ordinamento.equals("cliente desc"))
			str="SELECT * FROM ordine ORDER BY cliente desc;";
		else if(ordinamento.equals("prezzo_totale asc"))
			str="SELECT * FROM ordine ORDER BY prezzo_totale asc;";
		else if(ordinamento.equals("prezzo_totale desc"))
			str="SELECT * FROM ordine ORDER BY prezzo_totale desc;";
		else if(ordinamento.equals("gestore asc"))
			str="SELECT * FROM ordine ORDER BY gestore asc;";
		else if(ordinamento.equals("gestore desc"))
			str="SELECT * FROM ordine ORDER BY gestore desc;";
		else if(ordinamento.equals("stato asc"))
			str="SELECT * FROM ordine ORDER BY stato asc;";
		else if(ordinamento.equals("stato desc"))
			str="SELECT * FROM ordine ORDER BY stato desc;";
		else if(ordinamento.equals("metodo_di_pagamento asc"))
			str="SELECT * FROM ordine ORDER BY metodo_di_pagamento asc;";
		else if(ordinamento.equals("metodo_di_pagamento desc"))
			str="SELECT * FROM ordine ORDER BY metodo_di_pagamento desc;";
		else
			throw new SQLException("Invalid ordinamento");
		

		PreparedStatement ps = con.prepareStatement(str);
		ResultSet st = ps.executeQuery();
		ArrayList<OrdineBean> array = new ArrayList<OrdineBean>();
		while(st.next())
		{
			OrdineBean bean = new OrdineBean();
			bean.setCodice(st.getString("codice"));
			bean.setMetodo_di_pagamento(st.getString("metodo_di_pagamento"));
			bean.setData_acquisto(st.getDate("data_acquisto"));
			bean.setIndirizzo_di_consegna(st.getString("indirizzo_di_consegna"));
			bean.setCliente(st.getString("cliente"));
			bean.setPrezzo_totale(st.getDouble("prezzo_totale"));
			bean.setGestore(st.getString("gestore"));
			bean.setStato(st.getString("stato"));
			array.add(bean);
		}
		
		st.close();
		ps.close();
		con.close();
		return array;
	}

	public void newInsert(OrdineBean item) throws SQLException {
		if(item==null)
			throw new NullPointerException("l'item e' null");
		else {
			Connection con = ds.getConnection();
			String str = "insert into ordine values(?,?,?,?,?,?,?,?);";
			PreparedStatement ps = con.prepareStatement(str);
			ps.setString(1,item.getCodice());
			ps.setDate(2, item.getData_acquisto());
			ps.setString(3,item.getIndirizzo_di_consegna());
			ps.setString(4,item.getCliente());
			ps.setDouble(5,item.getPrezzo_totale());
			ps.setNull(6, Types.NULL);//per settare la chiave del gestore a null
			ps.setString(7, item.getStato());
			ps.setString(8, item.getMetodo_di_pagamento());
			ps.executeUpdate();
			ps.close();
			con.close();
		}
	}


	public void doUpdate(OrdineBean item) throws SQLException {
		if(item==null)
			throw new NullPointerException("l'item e' null");
		else {
			Connection connessione= ds.getConnection();
			String query="UPDATE ordine SET stato=?,gestore=? WHERE codice=?;";
			PreparedStatement ps= connessione.prepareStatement(query);
			ps.setString(1, item.getStato());
			ps.setString(2, item.getGestore());
			ps.setString(3, item.getCodice());
			ps.executeUpdate();
			ps.close();
			connessione.close();
		}
	}
	
	public ArrayList<OrdineBean> ricercaPerCliente(String cliente) throws SQLException{
		if(cliente==null)
			throw new NullPointerException("cliente e' null");
		else {
			Connection connessione= ds.getConnection();
			String query="SELECT * FROM ordine WHERE cliente=? ORDER BY data_acquisto desc;";
			PreparedStatement ps= connessione.prepareStatement(query);
			ps.setString(1, cliente);
			
			ResultSet risultato= ps.executeQuery();
			ArrayList<OrdineBean> a= new ArrayList<OrdineBean>();
			while(risultato.next()) {
				OrdineBean app= new OrdineBean();
				app.setCodice(risultato.getString("codice"));
				app.setIndirizzo_di_consegna(risultato.getString("indirizzo_di_consegna"));
				app.setMetodo_di_pagamento(risultato.getString("metodo_di_pagamento"));
				app.setCliente(risultato.getString("cliente"));
				app.setPrezzo_totale(risultato.getDouble("prezzo_totale"));
				app.setData_acquisto(risultato.getDate("data_acquisto"));
				app.setGestore(risultato.getString("gestore"));
				app.setStato(risultato.getString("stato"));
				a.add(app);
			}
			
			risultato.close();
			ps.close();
			connessione.close();
			return a;
		}
	}
	
	public ArrayList<OrdineBean> getOrdiniNonConsegnati() throws SQLException{
		Connection connessione= ds.getConnection();
		String query="SELECT * FROM ordine o WHERE gestore IS NULL;";
		PreparedStatement ps= connessione.prepareStatement(query);
		
		ResultSet risultato= ps.executeQuery();
		ArrayList<OrdineBean> nonConsegnati= new ArrayList<OrdineBean>();
		while(risultato.next()) {
			OrdineBean app= new OrdineBean();
			app.setCodice(risultato.getString("codice"));
			app.setIndirizzo_di_consegna(risultato.getString("indirizzo_di_consegna"));
			app.setMetodo_di_pagamento(risultato.getString("metodo_di_pagamento"));
			app.setCliente(risultato.getString("cliente"));
			app.setData_acquisto(risultato.getDate("data_acquisto"));
			app.setPrezzo_totale(risultato.getDouble("prezzo_totale"));
			app.setGestore(risultato.getString("gestore"));
			app.setStato(risultato.getString("stato"));
			
			nonConsegnati.add(app);
		}
		risultato.close();
		ps.close();
		connessione.close();
		return nonConsegnati;
	}

}
