package prodotto;

import static org.junit.Assert.*;

import java.sql.SQLException;
import java.util.ArrayList;

import javax.sql.DataSource;

import org.dbunit.DataSourceBasedDBTestCase;
import org.dbunit.IDatabaseTester;
import org.dbunit.dataset.IDataSet;
import org.dbunit.dataset.xml.FlatXmlDataSetBuilder;
import org.dbunit.operation.DatabaseOperation;
import org.h2.jdbcx.JdbcDataSource;
import org.junit.After;
import org.junit.Before;
import org.junit.Test;

public class CategoriaDAOTest extends DataSourceBasedDBTestCase{
	private DataSource ds;
    private CategoriaDAO ca;
    
	@Before
	public void setUp() throws Exception {
		super.setUp();
        ds = this.getDataSource();
        ca = new CategoriaDAO(ds);
	}

	@After
	public void tearDown() throws Exception {
		super.tearDown();
	}

	@Override
	protected DataSource getDataSource() {
		JdbcDataSource dataSource = new JdbcDataSource();
        dataSource.setURL("jdbc:h2:mem:test;DB_CLOSE_DELAY=-1;init=runscript from 'classpath:resources/db/init/categoria.sql'");
        dataSource.setUser("root");
        dataSource.setPassword("veloce123");
        return dataSource;
	}

	@Override
	protected IDataSet getDataSet() throws Exception {
		return new FlatXmlDataSetBuilder().build(this.getClass().getClassLoader().getResourceAsStream("resources/db/init/categoria.xml"));
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
		ArrayList<CategoriaBean> s = ca.allElements("nome asc");
		ArrayList<CategoriaBean> bean = new ArrayList<CategoriaBean>();
		
		CategoriaBean bean1 = new CategoriaBean();
		bean1.setNome("Arcade");
		bean.add(bean1);
		
		CategoriaBean bean2 = new CategoriaBean();
		bean2.setNome("Battle Royale");
		bean.add(bean2);
		
		assertEquals(s.size(), bean.size());
		
		for(int i=0; i<s.size();i++)
		{			
			assertEquals(s.get(i).getNome(),bean.get(i).getNome());
		}
		
	}
	@Test
	public void testAllElementsDESC() throws SQLException
	{
		ArrayList<CategoriaBean> s = ca.allElements("nome desc");
		ArrayList<CategoriaBean> bean = new ArrayList<CategoriaBean>();
		
		CategoriaBean bean2 = new CategoriaBean();
		bean2.setNome("Battle Royale");
		bean.add(bean2);
		
		
		CategoriaBean bean1 = new CategoriaBean();
		bean1.setNome("Arcade");
		bean.add(bean1);
		
		
		assertEquals(s.size(), bean.size());
		
		for(int i=0; i<s.size();i++)
		{			
			assertEquals(s.get(i).getNome(),bean.get(i).getNome());
		}
		
	}
	@Test
	public void testAllElementsNULL() throws SQLException
	{
		ArrayList<CategoriaBean> a = null;
		try {
		a = ca.allElements(null);
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
	}
	@Test
	public void testAllElementsNotValid() throws NullPointerException
	{
		ArrayList<CategoriaBean> a = null;
		try {
		a = ca.allElements("Napoli ssc");
		}catch(SQLException e)
		{
			assertNull(a);
		}
	}
	@Test
	public void testAllElementsVoid() throws SQLException
	{
		ArrayList<CategoriaBean> a = null;
		try {
		a = ca.allElements("");
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
	}

	

}
