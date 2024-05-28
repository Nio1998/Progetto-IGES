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
import org.junit.AfterClass;
import org.junit.Before;
import org.junit.Test;

/**
 * @author Francesco Peluso
 *
 */
public class SpeditoDAOTest extends DataSourceBasedDBTestCase {
	private DataSource ds;
    private SpeditoDAO sp;
    
    @Override
	protected DataSource getDataSource() {
		JdbcDataSource dataSource = new JdbcDataSource();
        dataSource.setURL("jdbc:h2:mem:test;DB_CLOSE_DELAY=-1;init=runscript from 'classpath:resources/db/init/spedito.sql'");
        dataSource.setUser("root");
        dataSource.setPassword("veloce123");
        return dataSource;
	}

	@Override
	protected IDataSet getDataSet() throws Exception {
		return new FlatXmlDataSetBuilder().build(this.getClass().getClassLoader().getResourceAsStream("resources/db/init/spedito.xml"));
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
	@AfterClass
	public static void tearDownAfterClass() throws Exception {
	}

	/**
	 * @throws java.lang.Exception
	 */
	@Before
	public void setUp() throws Exception {
		super.setUp();
        ds = this.getDataSource();
        sp = new SpeditoDAO(ds);
	}

	/**
	 * @throws java.lang.Exception
	 */
	@After
	public void tearDown() throws Exception {
		 super.tearDown();
	}
	
	/**
	 * Test method for {@link acquisto.SpeditoDAO#ricercaPerChiave(java.lang.String)}.
	 */
	@Test
	public void testRicercaPerChiaveIdPresente() throws SQLException {
		SpeditoBean b = sp.ricercaPerChiave("1");
		
		assertEquals(b.getOrdine(), "1");
		assertEquals(b.getCorriere_espresso(), "SDA");
		assertEquals(b.getData_consegna(),java.sql.Date.valueOf("2022-02-02"));
	}
	
	
	/**
	 * Test method for {@link acquisto.SpeditoDAO#ricercaPerChiave(java.lang.String)}.
	 */
	@Test
	public void testRicercaPerChiaveIdNonPresente() throws SQLException {
		
		SpeditoBean b = sp.ricercaPerChiave("10");	
		assertNull(b);
		
	}
	
	
	/**
	 * Test method for {@link acquisto.SpeditoDAO#ricercaPerChiave(java.lang.String)}.
	 */
	@Test
	public void testRicercaPerChiaveIdVuota()  {
		
		SpeditoBean b = null;
		try {
		b=sp.ricercaPerChiave("");	
		}catch(NullPointerException e)
		{
			assertNull(b);
		}
		catch(SQLException e)
		{
			fail("Not throws NullPointer");
		}
		
		
		
	}
	
	/**
	 * Test method for {@link acquisto.SpeditoDAO#ricercaPerChiave(java.lang.String)}.
	 */
	@Test
	public void testRicercaPerChiaveIdNull()  {
		
		SpeditoBean b = null;
		try {
		b=sp.ricercaPerChiave(null);	
		}catch(NullPointerException e)
		{
			assertNull(b);
		}
		catch(SQLException e)
		{
			fail("Not throws NullPointer");
		}
		
		
		
	}

	/**
	 * Test method for {@link acquisto.SpeditoDAO#allElements(java.lang.String)}.
	 * @throws SQLException 
	 */
	@Test
	public void testAllElementsASC() throws SQLException {
		ArrayList<SpeditoBean> a = sp.allElements("ordine asc");
		ArrayList<SpeditoBean> b = new ArrayList<SpeditoBean>();
		SpeditoBean bean = new SpeditoBean();
		
		bean.setOrdine("1");
		bean.setCorriere_esprersso("SDA");
		bean.setData_consegna(java.sql.Date.valueOf("2022-02-02"));
		
		b.add(bean);
		
		
		SpeditoBean bean2 = new SpeditoBean();
		
		bean2.setOrdine("2");
		bean2.setCorriere_esprersso("DHL");
		bean2.setData_consegna(java.sql.Date.valueOf("2022-03-03"));
		
		b.add(bean2);
		
		assertEquals(b.size(),a.size());
		
		
		for(int i=0; i<a.size();i++)
		{
			assertEquals(a.get(i).getCorriere_espresso(),b.get(i).getCorriere_espresso());
			assertEquals(a.get(i).getOrdine(),b.get(i).getOrdine());
			assertEquals(a.get(i).getData_consegna(),b.get(i).getData_consegna());
		}
		
	}
	
	
	/**
	 * Test method for {@link acquisto.SpeditoDAO#allElements(java.lang.String)}.
	 */
	@Test
	public void testAllElementsDesc() throws SQLException {
		ArrayList<SpeditoBean> a = sp.allElements("ordine desc");
		ArrayList<SpeditoBean> b = new ArrayList<SpeditoBean>();
				
		SpeditoBean bean2 = new SpeditoBean();
		
		bean2.setOrdine("2");
		bean2.setCorriere_esprersso("DHL");
		bean2.setData_consegna(java.sql.Date.valueOf("2022-03-03"));
		
		b.add(bean2);
		
		
		SpeditoBean bean = new SpeditoBean();
		
		bean.setOrdine("1");
		bean.setCorriere_esprersso("SDA");
		bean.setData_consegna(java.sql.Date.valueOf("2022-02-02"));
		
		b.add(bean);
		
		assertEquals(b.size(),a.size());
		
		
		for(int i=0; i<a.size();i++)
		{
			assertEquals(a.get(i).getCorriere_espresso(),b.get(i).getCorriere_espresso());
			assertEquals(a.get(i).getOrdine(),b.get(i).getOrdine());
			assertEquals(a.get(i).getData_consegna(),b.get(i).getData_consegna());
		}
		
		
	}
	
	
	/**
	 * Test method for {@link acquisto.SpeditoDAO#allElements(java.lang.String)}.
	 */
	@Test
	public void testAllElementsNULL() throws SQLException {
		
		ArrayList<SpeditoBean> a = null;
		try {
		a = sp.allElements(null);
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
	 * Test method for {@link acquisto.SpeditoDAO#allElements(java.lang.String)}.
	 */
	@Test
	public void testAllElementsVoid() throws SQLException {
		
		ArrayList<SpeditoBean> a = null;
		try {
		a = sp.allElements("");
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
	 * Test method for {@link acquisto.SpeditoDAO#allElements(java.lang.String)}.
	 */
	@Test
	public void testAllElementsNotValid() throws SQLException {
		
		ArrayList<SpeditoBean> a = null;
		try {
		a = sp.allElements("ordine ascc");
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
	 * Test method for {@link acquisto.SpeditoDAO#newInsert(acquisto.SpeditoBean)}.
	 */
	@Test
	public void testNewInsertNew() throws Exception {      
	      
        ITable expectedTable = new FlatXmlDataSetBuilder()
                   .build(OrdineDAOTest.class.getClassLoader().getResourceAsStream("resources/db/expected/spedito.xml"))
                   .getTable("spedito");
     
       
        SpeditoBean bean = new SpeditoBean();
		
		bean.setOrdine("3");
		bean.setCorriere_esprersso("Bartolini");
		bean.setData_consegna(java.sql.Date.valueOf("2022-04-04"));

		sp.newInsert(bean);

       
       IDatabaseTester tester = this.getDatabaseTester();
       
       ITable actualTable =  tester.getConnection().createDataSet().getTable("spedito");       
      
      Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
		
	}
	
	/**
	 * Test method for {@link acquisto.SpeditoDAO#newInsert(acquisto.SpeditoBean)}.
	 */
	@Test
	public void testNewInsertOld() throws Exception {      
	      
		SpeditoBean bean = new SpeditoBean();
		bean.setOrdine("1");
		bean.setCorriere_esprersso("SDA");
		bean.setData_consegna(java.sql.Date.valueOf("2022-04-04"));
		int i=0;
		try {
		sp.newInsert(bean);
		}
		catch(SQLException e)
		{
			i++;
		}
		
		assertEquals(1,i);
		
	}
	
	
	/**
	 * Test method for {@link acquisto.SpeditoDAO#newInsert(acquisto.SpeditoBean)}.
	 */
	@Test
	public void testNewInsertNULL() throws Exception {      
	     
		int i=0;
		try {
			sp.newInsert(null);
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
