package acquisto;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;

import javax.sql.DataSource;



public class CorriereEspressoDAO{
	
	DataSource ds =null;//la connessione al DB la ottengo dal Controller che se la va a prendere dal ServletContext

	
	public CorriereEspressoDAO(DataSource d)
	{
		ds=d;
	}
	
	public ArrayList<CorriereEspressoBean> allElements(String ordinamento) throws SQLException, NullPointerException {
		if(ordinamento==null || ordinamento=="")throw new NullPointerException("ordinamento vuoto o null");
		
		Connection connessione = ds.getConnection();
		String Query=null;
		
		if(ordinamento.equals("nome asc"))
			Query="SELECT * FROM corriereespresso ORDER BY nome asc ";
		else if(ordinamento.equals("nome desc"))
			Query="SELECT * FROM corriereespresso ORDER BY nome desc ";
		else if(ordinamento.equals("sito asc"))
			Query="SELECT * FROM corriereespresso ORDER BY sito asc ";
		else if(ordinamento.equals("sito desc"))
			Query="SELECT * FROM corriereespresso ORDER BY sito desc ";
		
		else
			throw new SQLException("ordinamento scritto in modo errato");
		
		
		
		PreparedStatement ps= connessione.prepareStatement(Query);

		
		ResultSet rs= ps.executeQuery();
		ArrayList<CorriereEspressoBean> a= new ArrayList<CorriereEspressoBean>();
		
		while(rs.next())
		{
		CorriereEspressoBean app= new CorriereEspressoBean();
		app.setNome(rs.getString("nome"));
		app.setSito(rs.getString("sito"));
		a.add(app);
		}
		rs.close();
		ps.close();
		connessione.close();
		return a;
	}

}
