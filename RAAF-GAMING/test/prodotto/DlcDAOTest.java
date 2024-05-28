package prodotto;

import static org.junit.Assert.*;

import java.sql.SQLException;
import java.util.ArrayList;

import javax.sql.DataSource;

import org.dbunit.Assertion;
import org.dbunit.DataSourceBasedDBTestCase;
import org.dbunit.IDatabaseTester;
import org.dbunit.JdbcDatabaseTester;
import org.dbunit.dataset.IDataSet;
import org.dbunit.dataset.ITable;
import org.dbunit.dataset.SortedTable;
import org.dbunit.dataset.xml.FlatXmlDataSetBuilder;
import org.dbunit.operation.DatabaseOperation;
import org.h2.jdbcx.JdbcDataSource;
import org.junit.After;
import org.junit.AfterClass;
import org.junit.Before;
import org.junit.BeforeClass;
import org.junit.Test;
import org.junit.jupiter.api.BeforeAll;

import acquisto.CorriereEspressoBean;
import acquisto.OrdineBean;
import acquisto.OrdineDAOTest;

public class DlcDAOTest extends DataSourceBasedDBTestCase{
	private DataSource ds;
    private DlcDAO dlc;

    
	@Before
	public void setUp() throws Exception {
		super.setUp();
        ds = this.getDataSource();
        dlc = new DlcDAO(ds);
	}

	@After
	public void tearDown() throws Exception {
		super.tearDown();
	}

	@Override
	protected DataSource getDataSource() {
		JdbcDataSource dataSource = new JdbcDataSource();
        dataSource.setURL("jdbc:h2:mem:test;DB_CLOSE_DELAY=-1;init=runscript from 'classpath:resources/db/init/dlc.sql'");
        dataSource.setUser("root");
        dataSource.setPassword("veloce123");
        return dataSource;
	}

	@Override
	protected IDataSet getDataSet() throws Exception {
		return new FlatXmlDataSetBuilder().build(this.getClass().getClassLoader().getResourceAsStream("resources/db/init/dlc.xml"));
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
	public void testRicercaPerChiave() throws SQLException{
		DlcBean a = dlc.ricercaPerChiave("1");
		DlcBean b = new DlcBean();
		
		b.setProdotto(1);
		b.setDimensione(1.0);
		b.setDescrizione("bello");
		
		assertEquals(a.getProdotto(), b.getProdotto());
		assertEquals(a.getDimensione(),b.getDimensione());
		assertEquals(a.getDescrizione(),b.getDescrizione());
	}
	@Test
	public void testRicercaPerChiaveNULL() throws SQLException{
		DlcBean a = null;
		try {
			a = dlc.ricercaPerChiave(null);
			}catch(NullPointerException e)
			{
				assertNull(a);
			}
	}
	@Test
	public void testRicercaPerChiaveVoid() throws SQLException{
		DlcBean a = null;
		try {
		a = dlc.ricercaPerChiave("");
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
	}
	@Test
	public void testRicercaPerChiaveNotValid() throws NullPointerException
	{
		DlcBean a = null;
		try {
		a = dlc.ricercaPerChiave("78");
		}catch(SQLException e)
		{
			assertNull(a);
		}
	}
	@Test
	public void testAllElementsASC() throws SQLException
	{
		ArrayList<DlcBean> s = dlc.allElements("prodotto asc");
		ArrayList<DlcBean> bean = new ArrayList<DlcBean>();
		
		DlcBean bean1 = new DlcBean();
		bean1.setProdotto(1);
		bean1.setDimensione(1.0);
		bean1.setDescrizione("bello");
		bean.add(bean1);
		
		DlcBean bean2 = new DlcBean();
		bean2.setProdotto(2);
		bean2.setDimensione(2.0);
		bean2.setDescrizione("onesto");
		bean.add(bean2);
		
		DlcBean bean3 = new DlcBean();
		bean3.setProdotto(3);
		bean3.setDimensione(3.0);
		bean3.setDescrizione("brutto");
		bean.add(bean3);
		
		assertEquals(s.size(), bean.size());
		
		for(int i=0; i<s.size();i++)
		{			
			assertEquals(s.get(i).getProdotto(),bean.get(i).getProdotto());
			assertEquals(s.get(i).getDimensione(),bean.get(i).getDimensione());
			assertEquals(s.get(i).getDescrizione(),bean.get(i).getDescrizione());
		}
		
	}
	@Test
	public void testAllElementsDESC() throws SQLException
	{
		ArrayList<DlcBean> s = dlc.allElements("prodotto desc");
		ArrayList<DlcBean> bean = new ArrayList<DlcBean>();
		
		DlcBean bean3 = new DlcBean();
		bean3.setProdotto(3);
		bean3.setDimensione(3.0);
		bean3.setDescrizione("brutto");
		bean.add(bean3);
		
		DlcBean bean2 = new DlcBean();
		bean2.setProdotto(2);
		bean2.setDimensione(2.0);
		bean2.setDescrizione("onesto");
		bean.add(bean2);
		
		
		DlcBean bean1 = new DlcBean();
		bean1.setProdotto(1);
		bean1.setDimensione(1.0);
		bean1.setDescrizione("bello");
		bean.add(bean1);
		
		assertEquals(s.size(), bean.size());

		for(int i=0; i<s.size();i++)
		{			
			assertEquals(s.get(i).getProdotto(),bean.get(i).getProdotto());
			assertEquals(s.get(i).getDimensione(),bean.get(i).getDimensione());
			assertEquals(s.get(i).getDescrizione(),bean.get(i).getDescrizione());
		}
		
	}
	@Test
	public void testAllElementsNULL() throws SQLException
	{
		ArrayList<DlcBean> a = null;
		try {
		a = dlc.allElements(null);
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
	}
	@Test
	public void testAllElementsNotValid() throws NullPointerException
	{
		ArrayList<DlcBean> a = null;
		try {
		a = dlc.allElements("prodotto sc");
		}catch(SQLException e)
		{
			assertNull(a);
		}
	}
	@Test
	public void testAllElementsVoid() throws SQLException
	{
		ArrayList<DlcBean> a = null;
		try {
		a = dlc.allElements("");
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
	}
	
	@Test
	public void testNewInsertNew() throws Exception {      
	      
        ITable expectedTable = new FlatXmlDataSetBuilder()
                   .build(OrdineDAOTest.class.getClassLoader().getResourceAsStream("resources/db/expected/dlc.xml"))
                   .getTable("dlc");
     
        DlcBean bean = new DlcBean();
     
       bean.setProdotto(4);
       bean.setDimensione(4.0);
       bean.setDescrizione("bellissimo");
      
     
       dlc.newInsert(bean);
       
       IDatabaseTester tester = this.getDatabaseTester();
       
       ITable actualTable =  tester.getConnection().createDataSet().getTable("dlc");       
      
      Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
		
	}
	@Test
	public void testNewInsertOld() {

		DlcBean bean = new DlcBean();
		
		 bean.setProdotto(3);
	     bean.setDimensione(3.0);
	     bean.setDescrizione("brutto");
	      
		
		int i=0;
		try {
			dlc.newInsert(bean);
		}
		catch(SQLException e)
		{
			i++;
		}
		
		assertEquals(1,i);
	}
	@Test
	public void testNewInsertNull() {
		
		int i=0;
		try {
			dlc.newInsert(null);
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
