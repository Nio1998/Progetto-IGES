package prodotto;

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
import org.junit.BeforeClass;
import org.junit.Test;

import acquisto.OrdineDAOTest;

public class ConsoleDAOTest extends DataSourceBasedDBTestCase{
	private DataSource ds;
	private ConsoleDAO cns;
	
	@Before
	public void setUp() throws Exception {
		super.setUp();
        ds = this.getDataSource();
        cns = new ConsoleDAO(ds);
	}

	@After
	public void tearDown() throws Exception {
		super.tearDown();
	}

	@Override
	protected DataSource getDataSource() {
		JdbcDataSource dataSource = new JdbcDataSource();
        dataSource.setURL("jdbc:h2:mem:test;DB_CLOSE_DELAY=-1;init=runscript from 'classpath:resources/db/init/console.sql'");
        dataSource.setUser("root");
        dataSource.setPassword("veloce123");
        return dataSource;
	}

	@Override
	protected IDataSet getDataSet() throws Exception {
		return new FlatXmlDataSetBuilder().build(this.getClass().getClassLoader().getResourceAsStream("resources/db/init/console.xml"));
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
	public void testricercaPerChiave() throws SQLException {
		ConsoleBean a = cns.ricercaPerChiave("3");
		ConsoleBean b = new ConsoleBean();
		
		b.setProdotto(3);
		b.setSpecifica("1000 nuovi giochi");
		b.setColore("rosso");
		
		assertEquals(a.getProdotto(),b.getProdotto());
		assertEquals(a.getSpecifica(),b.getSpecifica());
		assertEquals(a.getColore(),b.getColore());
	}
	@Test
	public void testRicercaPerChiaveNULL() throws SQLException{
		ConsoleBean a = null;
		try {
			a = cns.ricercaPerChiave(null);
			}catch(NullPointerException e)
			{
				assertNull(a);
			}
	}
	@Test
	public void testRicercaPerChiaveVoid() throws SQLException{
		ConsoleBean a = null;
		try {
		a = cns.ricercaPerChiave("");
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
	}
	@Test
	public void testRicercaPerChiaveNotValid() throws NullPointerException
	{
		ConsoleBean a = null;
		try {
		a = cns.ricercaPerChiave("78");
		}catch(SQLException e)
		{
			assertNull(a);
		}
	}
	@Test
	public void testAllElementsASC() throws SQLException
	{
		ArrayList<ConsoleBean> s = cns.allElements("prodotto asc");
		ArrayList<ConsoleBean> bean = new ArrayList<ConsoleBean>();
		
		ConsoleBean bean1 = new ConsoleBean();
		bean1.setProdotto(3);
		bean1.setSpecifica("1000 nuovi giochi");
		bean1.setColore("rosso");
		bean.add(bean1);
		
		ConsoleBean bean2 = new ConsoleBean();
		bean2.setProdotto(8);
		bean2.setSpecifica("nuova playstation");
		bean2.setColore("bianco");
		bean.add(bean2);
		
		
		
		assertEquals(s.size(), bean.size());
		
		for(int i=0; i<s.size();i++)
		{			
			assertEquals(s.get(i).getProdotto(),bean.get(i).getProdotto());
			assertEquals(s.get(i).getSpecifica(),bean.get(i).getSpecifica());
			assertEquals(s.get(i).getColore(),bean.get(i).getColore());
		}
		
	}
	@Test
	public void testAllElementsDESC() throws SQLException
	{
		ArrayList<ConsoleBean> s = cns.allElements("prodotto desc");
		ArrayList<ConsoleBean> bean = new ArrayList<ConsoleBean>();
		
		ConsoleBean bean2 = new ConsoleBean();
		bean2.setProdotto(8);
		bean2.setSpecifica("nuova playstation");
		bean2.setColore("bianco");
		bean.add(bean2);
		
		ConsoleBean bean1 = new ConsoleBean();
		bean1.setProdotto(3);
		bean1.setSpecifica("1000 nuovi giochi");
		bean1.setColore("rosso");
		bean.add(bean1);
		

		assertEquals(s.size(), bean.size());
		
		for(int i=0; i<s.size();i++)
		{			
			assertEquals(s.get(i).getProdotto(),bean.get(i).getProdotto());
			assertEquals(s.get(i).getSpecifica(),bean.get(i).getSpecifica());
			assertEquals(s.get(i).getColore(),bean.get(i).getColore());
		}
		
	}
	@Test
	public void testAllElementsNULL() throws SQLException{
		ArrayList<ConsoleBean> a = null;
		try {
			a = cns.allElements(null);
			}catch(NullPointerException e)
			{
				assertNull(a);
			}
	}
	@Test
	public void testAllElementsVoid() throws SQLException{
		ArrayList<ConsoleBean> a = null;
		try {
			a = cns.allElements("");
			}catch(NullPointerException e)
			{
				assertNull(a);
			}
	}
	@Test
	public void testAllElementsNotValid() throws NullPointerException{
		ArrayList<ConsoleBean> a = null;
		try {
			a = cns.allElements("prodoo as");
			}catch(SQLException e)
			{
				assertNull(a);
			}
	}
	@Test
	public void testNewInsertNew() throws Exception {      
	      
        ITable expectedTable = new FlatXmlDataSetBuilder()
                   .build(OrdineDAOTest.class.getClassLoader().getResourceAsStream("resources/db/expected/console.xml"))
                   .getTable("console");
     
        ConsoleBean bean2 = new ConsoleBean();
		bean2.setProdotto(10);
		bean2.setSpecifica("nuova xbox");
		bean2.setColore("nera");
		
     
       cns.newInsert(bean2);
       
       IDatabaseTester tester = this.getDatabaseTester();
       
       ITable actualTable =  tester.getConnection().createDataSet().getTable("console");       
      
      Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
		
	}
	@Test
	public void testNewInsertOld() {

		 ConsoleBean bean2 = new ConsoleBean();
		
		bean2.setProdotto(8);
		bean2.setSpecifica("nuova playstation");
		bean2.setColore("bianco");
	      
		
		int i=0;
		try {
			cns.newInsert(bean2);
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
			cns.newInsert(null);
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
