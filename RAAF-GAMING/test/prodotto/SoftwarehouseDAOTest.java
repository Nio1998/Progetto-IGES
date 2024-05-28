package prodotto;

import static org.junit.Assert.*;

import java.sql.SQLException;
import java.util.ArrayList;

import javax.sql.DataSource;

import org.dbunit.DataSourceBasedDBTestCase;
import org.dbunit.dataset.IDataSet;
import org.dbunit.dataset.xml.FlatXmlDataSetBuilder;
import org.dbunit.operation.DatabaseOperation;
import org.h2.jdbcx.JdbcDataSource;
import org.junit.After;
import org.junit.AfterClass;
import org.junit.Before;
import org.junit.BeforeClass;
import org.junit.Test;

import acquisto.CorriereEspressoBean;
import acquisto.CorriereEspressoDAO;



public class SoftwarehouseDAOTest extends DataSourceBasedDBTestCase{
	private DataSource ds;
    private SoftwarehouseDAO sh;

	@Before
	public void setUp() throws Exception {
		super.setUp();
        ds = this.getDataSource();
        sh = new SoftwarehouseDAO(ds);
	}

	@After
	public void tearDown() throws Exception {
		super.tearDown();
	}

	

	@Override
	protected DataSource getDataSource() {
		JdbcDataSource dataSource = new JdbcDataSource();
        dataSource.setURL("jdbc:h2:mem:test;DB_CLOSE_DELAY=-1;init=runscript from 'classpath:resources/db/init/softwarehouse.sql'");
        dataSource.setUser("root");
        dataSource.setPassword("veloce123");
        return dataSource;
	}

	@Override
	protected IDataSet getDataSet() throws Exception {
		return new FlatXmlDataSetBuilder().build(this.getClass().getClassLoader().getResourceAsStream("resources/db/init/softwarehouse.xml"));
	}
	 @Override
	    protected DatabaseOperation getSetUpOperation() {
	        return DatabaseOperation.REFRESH;
	    }

	 @Override
	    protected DatabaseOperation getTearDownOperation() {
	        return DatabaseOperation.DELETE_ALL;
	    }
	
	@Test 
	public void testAllElementsASC() throws SQLException{
		ArrayList<SoftwarehouseBean> s = sh.allElements("nomesfh asc");
		ArrayList<SoftwarehouseBean> soft = new ArrayList<SoftwarehouseBean>();
		
		SoftwarehouseBean softbean = new SoftwarehouseBean();
		softbean.setNomesfh("Activision");
		softbean.setLogo(null);
		soft.add(softbean);
		
		SoftwarehouseBean softbean1 = new SoftwarehouseBean();
		softbean1.setNomesfh("CD Project Red");
		softbean1.setLogo(null);
		soft.add(softbean1);
		
		SoftwarehouseBean softbean2 = new SoftwarehouseBean();
		softbean2.setNomesfh("Electronic Arts");
		softbean2.setLogo(null);
		soft.add(softbean2);
		
		for(int i=0; i<s.size();i++)
		{			
			assertEquals(s.get(i).getNomesfh(),soft.get(i).getNomesfh());
		}
	}
	@Test 
	public void testAllElementsDESC() throws SQLException{
		ArrayList<SoftwarehouseBean> s = sh.allElements("nomesfh desc");
		ArrayList<SoftwarehouseBean> soft = new ArrayList<SoftwarehouseBean>();
		
		SoftwarehouseBean softbean2 = new SoftwarehouseBean();
		softbean2.setNomesfh("Electronic Arts");
		softbean2.setLogo(null);
		soft.add(softbean2);
		
		SoftwarehouseBean softbean1 = new SoftwarehouseBean();
		softbean1.setNomesfh("CD Project Red");
		softbean1.setLogo(null);
		soft.add(softbean1);
		
		
		SoftwarehouseBean softbean = new SoftwarehouseBean();
		softbean.setNomesfh("Activision");
		softbean.setLogo(null);
		soft.add(softbean);

		
		for(int i=0; i<s.size();i++)
		{			
			assertEquals(s.get(i).getNomesfh(),soft.get(i).getNomesfh());
		}
	}
	@Test 
	public void testAllElementsNULL() throws SQLException
	{
		ArrayList<SoftwarehouseBean> a = null;
		try {
		a = sh.allElements(null);
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
	}
	@Test 
	public void testAllElementsNotValid() throws NullPointerException
	{
		ArrayList<SoftwarehouseBean> a = null;
		try {
		a = sh.allElements("nome sc");
		}catch(SQLException e)
		{
			assertNull(a);
		}
	}
	@Test 
	public void testAllElementsVoid() throws SQLException
	{
		ArrayList<SoftwarehouseBean> a = null;
		try {
		a = sh.allElements("");
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
	}

}
