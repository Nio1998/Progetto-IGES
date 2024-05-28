/**
 * 
 */
package acquisto;

import static org.junit.Assert.*;

import java.sql.SQLException;
import java.util.ArrayList;

import javax.sql.DataSource;

import org.dbunit.Assertion;
import org.dbunit.DataSourceBasedDBTestCase;
import org.dbunit.IDatabaseTester;
import org.dbunit.dataset.IDataSet;
import org.dbunit.dataset.ITable;
import org.dbunit.dataset.SortedTable;
import org.dbunit.dataset.xml.FlatXmlDataSetBuilder;
import org.dbunit.operation.DatabaseOperation;
import org.h2.jdbcx.JdbcDataSource;
import org.junit.After;
import org.junit.Before;
import org.junit.Test;

/**
 * @author Utente
 *
 */
public class RiguardaDAOTest extends DataSourceBasedDBTestCase{
	private DataSource ds;
    private RiguardaDAO rig;
	
	
	@Override
	protected DataSource getDataSource() {
		JdbcDataSource dataSource = new JdbcDataSource();
        dataSource.setURL("jdbc:h2:mem:test;DB_CLOSE_DELAY=-1;init=runscript from 'classpath:resources/db/init/riguarda.sql'");
        dataSource.setUser("root");
        dataSource.setPassword("veloce123");
        return dataSource;
	}

	@Override
	protected IDataSet getDataSet() throws Exception {
		return new FlatXmlDataSetBuilder().build(this.getClass().getClassLoader().getResourceAsStream("resources/db/init/riguarda.xml"));
	}
	
	@Override
    protected DatabaseOperation getSetUpOperation() {
        return DatabaseOperation.REFRESH;
    }

	 @Override
	 protected DatabaseOperation getTearDownOperation() {
	    return DatabaseOperation.DELETE_ALL;
	 }

	/**
	 * @throws java.lang.Exception
	 */
	@Before
	public void setUp() throws Exception {
		super.setUp();
        ds = this.getDataSource();
        rig = new RiguardaDAO(ds);
	}

	/**
	 * @throws java.lang.Exception
	 */
	@After
	public void tearDown() throws Exception {
		super.tearDown();
	}
	

	/**
	 * Test method for {@link acquisto.RiguardaDAO#allElements(java.lang.String)}.
	 */
	@Test
	public void testAllElementsASC() throws SQLException {
		
		ArrayList<RiguardaBean> a = rig.allElements("prodotto asc");
		ArrayList<RiguardaBean> b = new ArrayList<RiguardaBean>();
		RiguardaBean bean = new RiguardaBean();
		
		bean.setOrdine("10");
		bean.setProdotto(1);
		bean.setQuantita_acquistata(1);
		
		b.add(bean);
		
		
		RiguardaBean bean2 = new RiguardaBean();
		
		bean2.setOrdine("7");
		bean2.setProdotto(2);
		bean2.setQuantita_acquistata(1);
		
		b.add(bean2);
		
		RiguardaBean bean3 = new RiguardaBean();
		
		bean3.setOrdine("10");
		bean3.setProdotto(5);
		bean3.setQuantita_acquistata(1);
		
		b.add(bean3);
		
		assertEquals(b.size(),a.size());
		
		
		for(int i=0; i<a.size();i++)
		{
			assertEquals(a.get(i).getOrdine(),b.get(i).getOrdine());
			assertEquals(a.get(i).getProdotto(),b.get(i).getProdotto());
			assertEquals(a.get(i).getQuantita_acquistata(),b.get(i).getQuantita_acquistata());
		}
		
		
	}
	
	
	/**
	 * Test method for {@link acquisto.RiguardaDAO#allElements(java.lang.String)}.
	 */
	@Test
	public void testAllElementsDesc() throws SQLException {
		
		ArrayList<RiguardaBean> a = rig.allElements("prodotto desc");
		ArrayList<RiguardaBean> b = new ArrayList<RiguardaBean>();
		
		RiguardaBean bean3 = new RiguardaBean();
		
		bean3.setOrdine("10");
		bean3.setProdotto(5);
		bean3.setQuantita_acquistata(1);
		
		b.add(bean3);
		
		RiguardaBean bean2 = new RiguardaBean();
		
		bean2.setOrdine("7");
		bean2.setProdotto(2);
		bean2.setQuantita_acquistata(1);
		
		b.add(bean2);
		
		RiguardaBean bean = new RiguardaBean();
		
		bean.setOrdine("10");
		bean.setProdotto(1);
		bean.setQuantita_acquistata(1);
		
		
		
		b.add(bean);
		
		assertEquals(b.size(),a.size());
		
		
		for(int i=0; i<a.size();i++)
		{
			assertEquals(a.get(i).getOrdine(),b.get(i).getOrdine());
			assertEquals(a.get(i).getProdotto(),b.get(i).getProdotto());
			assertEquals(a.get(i).getQuantita_acquistata(),b.get(i).getQuantita_acquistata());
		}
		
		
	}
	
	
	
	/**
	 * Test method for {@link acquisto.RiguardaDAO#allElements(java.lang.String)}.
	 */
	@Test
	public void testAllElementsNULL() throws SQLException {
		
		ArrayList<RiguardaBean> a = null;
		try {
		a = rig.allElements(null);
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
		catch(SQLException e)
		{
			fail("Not throws NullPointer");
		}
	}
	
	
	/**
	 * Test method for {@link acquisto.RiguardaDAO#allElements(java.lang.String)}.
	 */
	@Test
	public void testAllElementsNotValid() throws SQLException {
		
		ArrayList<RiguardaBean> a = null;
		try {
		a = rig.allElements("prodotto ascc");
		}catch(SQLException e)
		{
			assertNull(a);
		}
		catch(NullPointerException e)
		{
			fail("Not throws SqlExceptionr");
		}
		
	}
	
	/**
	 * Test method for {@link acquisto.RiguardaDAO#allElements(java.lang.String)}.
	 */
	@Test
	public void testAllElementsNotVoid() throws SQLException {
		
		ArrayList<RiguardaBean> a = null;
		try {
		a = rig.allElements("");
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
		catch(SQLException e)
		{
			fail("Not throws NullPointer");
		}
		
	}

	/**
	 * Test method for {@link acquisto.RiguardaDAO#newInsert(acquisto.RiguardaBean)}.
	 */
	@Test
	public void testNewInsertNew() throws Exception {      
	      
        ITable expectedTable = new FlatXmlDataSetBuilder()
                   .build(OrdineDAOTest.class.getClassLoader().getResourceAsStream("resources/db/expected/riguarda.xml"))
                   .getTable("riguarda");
     
       
        RiguardaBean bean = new RiguardaBean();
		bean.setOrdine("3");
		bean.setProdotto(1);
		bean.setQuantita_acquistata(1);
		
		
		rig.newInsert(bean);
		
		
		RiguardaBean bean2 = new RiguardaBean();
		
		bean2.setOrdine("3");
		bean2.setProdotto(2);
		bean2.setQuantita_acquistata(1);
		
		rig.newInsert(bean2);
       
       IDatabaseTester tester = this.getDatabaseTester();
       
       ITable actualTable =  tester.getConnection().createDataSet().getTable("riguarda");       
      
      Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
		
	}
	
	
	/**
	 * Test method for {@link acquisto.RiguardaDAO#newInsert(acquisto.RiguardaBean)}.
	 */
	@Test
	public void testNewInsertOld() throws Exception {

		
		RiguardaBean bean = new RiguardaBean();
		bean.setOrdine("10");
		bean.setProdotto(1);
		bean.setQuantita_acquistata(1);
		
		int i=0;
		try {
		rig.newInsert(bean);
		}
		catch(SQLException e)
		{
			i++;
		}
		
		assertEquals(1,i);

	}
	
	
	/**
	 * Test method for {@link acquisto.RiguardaDAO#newInsert(acquisto.RiguardaBean)}.
	 */
	@Test
	public void testNewInsertNull() {
		
		int i=0;
		try {
			rig.newInsert(null);
		}
		catch(NullPointerException e)
		{
			i++;
		} catch (SQLException e) {
			i=0;
		}
		
		assertEquals(1,i);
		
	}



}
