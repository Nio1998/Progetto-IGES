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
import org.junit.Before;
import org.junit.Test;

import acquisto.OrdineDAOTest;

public class AbbonamentoDAOTest extends DataSourceBasedDBTestCase{
	private DataSource ds;
	private AbbonamentoDAO ab;
	
	@Before
	public void setUp() throws Exception {
		super.setUp();
        ds = this.getDataSource();
        ab = new AbbonamentoDAO(ds);
	}

	@After
	public void tearDown() throws Exception {
		super.tearDown();
	}



	@Override
	protected DataSource getDataSource() {
		JdbcDataSource dataSource = new JdbcDataSource();
        dataSource.setURL("jdbc:h2:mem:test;DB_CLOSE_DELAY=-1;init=runscript from 'classpath:resources/db/init/abbonamento.sql'");
        dataSource.setUser("root");
        dataSource.setPassword("veloce123");
        return dataSource;
	}

	@Override
	protected IDataSet getDataSet() throws Exception {
		return new FlatXmlDataSetBuilder().build(this.getClass().getClassLoader().getResourceAsStream("resources/db/init/abbonamento.xml"));
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
		AbbonamentoBean a = ab.ricercaPerChiave("5");
		AbbonamentoBean b = new AbbonamentoBean();
		
		b.setProdotto(5);
		b.setCodice("asd34gf56d1");
		b.setDurata_abbonamento(2);
		
		assertEquals(a.getProdotto(),b.getProdotto());
		assertEquals(a.getCodice(),b.getCodice());
		assertEquals(a.getDurata_abbonamento(),b.getDurata_abbonamento());
	}
	@Test
	public void testRicercaPerChiaveNULL() throws SQLException{
		AbbonamentoBean a = null;
		try {
			a = ab.ricercaPerChiave(null);
			}catch(NullPointerException e)
			{
				assertNull(a);
			}
	}
	@Test
	public void testRicercaPerChiaveVoid() throws SQLException{
		AbbonamentoBean a = null;
		try {
		a = ab.ricercaPerChiave("");
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
	}
	@Test
	public void testRicercaPerChiaveNotValid() throws NullPointerException
	{
		AbbonamentoBean a = null;
		try {
		a = ab.ricercaPerChiave("8");
		}catch(SQLException e)
		{
			assertNull(a);
		}
	}
	@Test
	public void testAllElementsASC() throws SQLException
	{
		ArrayList<AbbonamentoBean> s = ab.allElements("prodotto asc");
		ArrayList<AbbonamentoBean> bean = new ArrayList<AbbonamentoBean>();
		
		AbbonamentoBean bean1 = new AbbonamentoBean();
		bean1.setProdotto(5);
		bean1.setCodice("asd34gf56d1");
		bean1.setDurata_abbonamento(2);
		bean.add(bean1);
		
		AbbonamentoBean bean2 = new AbbonamentoBean();
		bean2.setProdotto(7);
		bean2.setCodice("qwerty65jl1");
		bean2.setDurata_abbonamento(6);
		bean.add(bean2);
		
		
		assertEquals(s.size(), bean.size());
		
		for(int i=0; i<s.size();i++)
		{			
			assertEquals(s.get(i).getProdotto(),bean.get(i).getProdotto());
			assertEquals(s.get(i).getCodice(),bean.get(i).getCodice());
			assertEquals(s.get(i).getDurata_abbonamento(),bean.get(i).getDurata_abbonamento());
		}
		
	}
	@Test
	public void testAllElementsDESC() throws SQLException
	{
		ArrayList<AbbonamentoBean> s = ab.allElements("prodotto desc");
		ArrayList<AbbonamentoBean> bean = new ArrayList<AbbonamentoBean>();
		
		AbbonamentoBean bean2 = new AbbonamentoBean();
		bean2.setProdotto(7);
		bean2.setCodice("qwerty65jl1");
		bean2.setDurata_abbonamento(6);
		bean.add(bean2);
		
		AbbonamentoBean bean1 = new AbbonamentoBean();
		bean1.setProdotto(5);
		bean1.setCodice("asd34gf56d1");
		bean1.setDurata_abbonamento(2);
		bean.add(bean1);
		
		
		
		assertEquals(s.size(), bean.size());
		
		for(int i=0; i<s.size();i++)
		{			
			assertEquals(s.get(i).getProdotto(),bean.get(i).getProdotto());
			assertEquals(s.get(i).getCodice(),bean.get(i).getCodice());
			assertEquals(s.get(i).getDurata_abbonamento(),bean.get(i).getDurata_abbonamento());
		}
		
	}
	@Test
	public void testAllElementsNULL() throws SQLException{
		ArrayList<AbbonamentoBean> a = null;
		try {
			a = ab.allElements(null);
			}catch(NullPointerException e)
			{
				assertNull(a);
			}
	}
	@Test
	public void testAllElementsVoid() throws SQLException{
		ArrayList<AbbonamentoBean> a = null;
		try {
			a = ab.allElements("");
			}catch(NullPointerException e)
			{
				assertNull(a);
			}
	}
	@Test
	public void testAllElementsNotValid() throws NullPointerException{
		ArrayList<AbbonamentoBean> a = null;
		try {
			a = ab.allElements("prodoo as");
			}catch(SQLException e)
			{
				assertNull(a);
			}
	}
	@Test
	public void testNewInsertNew() throws Exception {      
	      
        ITable expectedTable = new FlatXmlDataSetBuilder()
                   .build(OrdineDAOTest.class.getClassLoader().getResourceAsStream("resources/db/expected/abbonamento.xml"))
                   .getTable("abbonamento");
     
        AbbonamentoBean bean = new  AbbonamentoBean();
     
       bean.setProdotto(8);
       bean.setCodice("abcdef122f5");
       bean.setDurata_abbonamento(9);
      
     
       ab.newInsert(bean);
       
       IDatabaseTester tester = this.getDatabaseTester();
       
       ITable actualTable =  tester.getConnection().createDataSet().getTable("abbonamento");       
      
      Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
		
	}
	@Test
	public void testNewInsertOld() {

		AbbonamentoBean bean = new  AbbonamentoBean();
		
		bean.setProdotto(5);
		bean.setCodice("asd34gf56d1");
		bean.setDurata_abbonamento(2);
	      
		
		int i=0;
		try {
			ab.newInsert(bean);
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
			ab.newInsert(null);
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
