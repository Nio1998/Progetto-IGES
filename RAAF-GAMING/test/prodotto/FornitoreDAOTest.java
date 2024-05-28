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
import org.junit.Before;
import org.junit.Test;

public class FornitoreDAOTest extends DataSourceBasedDBTestCase{
	private DataSource ds;
    private FornitoreDAO fo;
    
	@Before
	public void setUp() throws Exception {
		super.setUp();
        ds = this.getDataSource();
        fo = new FornitoreDAO(ds);
	}

	@After
	public void tearDown() throws Exception {
		super.tearDown();
	}

	

	@Override
	protected DataSource getDataSource() {
		JdbcDataSource dataSource = new JdbcDataSource();
        dataSource.setURL("jdbc:h2:mem:test;DB_CLOSE_DELAY=-1;init=runscript from 'classpath:resources/db/init/fornitore.sql'");
        dataSource.setUser("root");
        dataSource.setPassword("veloce123");
        return dataSource;
	}

	@Override
	protected IDataSet getDataSet() throws Exception {
		return new FlatXmlDataSetBuilder().build(this.getClass().getClassLoader().getResourceAsStream("resources/db/init/fornitore.xml"));
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
	public void testAllElementsASC() throws SQLException
	{
		ArrayList<FornitoreBean> s = fo.allElements("nome asc");
		ArrayList<FornitoreBean> bean = new ArrayList<FornitoreBean>();
		
		FornitoreBean bean1 = new FornitoreBean();
		bean1.setNome("AMD");
		bean1.setIndirizzo("Giappone");
		bean1.setTelefono("089343743");
		bean.add(bean1);
		
		FornitoreBean bean2 = new FornitoreBean();
		bean2.setNome("Nvidia");
		bean2.setIndirizzo("America");
		bean2.setTelefono("089343701");
		bean.add(bean2);
		
		FornitoreBean bean3 = new FornitoreBean();
		bean3.setNome("Sony");
		bean3.setIndirizzo("Giappone");
		bean3.setTelefono("081931798");
		bean.add(bean3);
		
		
		assertEquals(s.size(), bean.size());
		
		for(int i=0; i<s.size();i++)
		{			
			assertEquals(s.get(i).getNome(),bean.get(i).getNome());
			assertEquals(s.get(i).getIndirizzo(),bean.get(i).getIndirizzo());
			assertEquals(s.get(i).getTelefono(),bean.get(i).getTelefono());
		}
		
	}
	@Test
	public void testAllElementsDESC() throws SQLException
	{
		ArrayList<FornitoreBean> s = fo.allElements("nome desc");
		ArrayList<FornitoreBean> bean = new ArrayList<FornitoreBean>();
		
		FornitoreBean bean3 = new FornitoreBean();
		bean3.setNome("Sony");
		bean3.setIndirizzo("Giappone");
		bean3.setTelefono("081931798");
		bean.add(bean3);
		
		FornitoreBean bean2 = new FornitoreBean();
		bean2.setNome("Nvidia");
		bean2.setIndirizzo("America");
		bean2.setTelefono("089343701");
		bean.add(bean2);
		
		FornitoreBean bean1 = new FornitoreBean();
		bean1.setNome("AMD");
		bean1.setIndirizzo("Giappone");
		bean1.setTelefono("089343743");
		bean.add(bean1);
		
		
		
		
		
		
		assertEquals(s.size(), bean.size());
		
		for(int i=0; i<s.size();i++)
		{			
			assertEquals(s.get(i).getNome(),bean.get(i).getNome());
			assertEquals(s.get(i).getIndirizzo(),bean.get(i).getIndirizzo());
			assertEquals(s.get(i).getTelefono(),bean.get(i).getTelefono());
		}	
	}
	@Test
	public void testAllElementsNULL() throws SQLException
	{
		ArrayList<FornitoreBean> a = null;
		try {
		a = fo.allElements(null);
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
	}
	@Test
	public void testAllElementsNotValid() throws NullPointerException
	{
		ArrayList<FornitoreBean> a = null;
		try {
		a = fo.allElements("Napoli ssc");
		}catch(SQLException e)
		{
			assertNull(a);
		}
	}
	@Test
	public void testAllElementsVoid() throws SQLException
	{
		ArrayList<FornitoreBean> a = null;
		try {
		a = fo.allElements("");
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
	}

}
