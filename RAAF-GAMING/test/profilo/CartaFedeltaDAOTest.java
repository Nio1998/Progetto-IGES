package profilo;

import static org.junit.Assert.*;

import java.sql.SQLException;
import java.util.ArrayList;

import javax.sql.DataSource;

import org.dbunit.Assertion;
import org.dbunit.DataSourceBasedDBTestCase;
import org.dbunit.IDatabaseTester;
import org.dbunit.dataset.DataSetException;
import org.dbunit.dataset.IDataSet;
import org.dbunit.dataset.ITable;
import org.dbunit.dataset.SortedTable;
import org.dbunit.dataset.xml.FlatXmlDataSetBuilder;
import org.h2.jdbcx.JdbcDataSource;
import org.junit.After;
import org.junit.Before;
import org.junit.Test;

import acquisto.OrdineDAOTest;
import magazzino.PresenteInBean;
import magazzino.PresenteInDAOTest;

public class CartaFedeltaDAOTest extends DataSourceBasedDBTestCase{

	private DataSource ds;
	private CartaFedeltaDAO cf;
	@Before
	public void setUp() throws Exception {
		super.setUp();
        ds = this.getDataSource();
        cf = new CartaFedeltaDAO(ds);
	}

	@After
	public void tearDown() throws Exception {
		super.tearDown();
	}

	@Test
	public void testRicercaPerChiavePresente() throws SQLException {
		CartaFedeltaBean a=cf.ricercaPerChiave("1234567899");
		CartaFedeltaBean n=new CartaFedeltaBean();
		
		n.setCodice("1234567899");
		n.setPunti(15);
		
		assertEquals(a.getCodice(),n.getCodice());
		assertEquals(a.getPunti(),n.getPunti());
		
	}
	
	@Test
	public void testRicercaPerChiaveNonPresente() throws SQLException {
		CartaFedeltaBean a=cf.ricercaPerChiave("1234567897");
		assertNull(a);
	}
	
	@Test
	public void testRicercaPerChiaveVuota() throws SQLException {
		CartaFedeltaBean a=null;
		try {
			a=cf.ricercaPerChiave("");
		}catch(NullPointerException e){	
			assertNull(a);
		}
	}
		
		@Test
		public void testRicercaPerChiaveNull() throws SQLException {
			CartaFedeltaBean a=null;
			try {
				a=cf.ricercaPerChiave(null);
			}catch(NullPointerException e){	
				assertNull(a);
			}
	}
		@Test
		public void testAllElementsASC() throws SQLException
		{
			ArrayList<CartaFedeltaBean> s = cf.allElements("codice asc");
			ArrayList<CartaFedeltaBean> bean = new ArrayList<CartaFedeltaBean>();
			
			
			CartaFedeltaBean bean2= new CartaFedeltaBean();
			bean2.setCodice("1234567898");
			bean2.setPunti(20);
			bean.add(bean2);
			
			CartaFedeltaBean bean1=new CartaFedeltaBean();
			bean1.setCodice("1234567899");
			bean1.setPunti(15);
			bean.add(bean1);
			
			assertEquals(bean.size(), s.size());
			
			for(int i=0; i<s.size();i++) {
				assertEquals(s.get(i).getCodice(),bean.get(i).getCodice());
				assertEquals(s.get(i).getPunti(),bean.get(i).getPunti());
			}
		}
		
		@Test
		public void testAllElementsDESC() throws SQLException
		{
			ArrayList<CartaFedeltaBean> s = cf.allElements("codice desc");
			ArrayList<CartaFedeltaBean> bean = new ArrayList<CartaFedeltaBean>();
			
			CartaFedeltaBean bean1=new CartaFedeltaBean();
			bean1.setCodice("1234567899");
			bean1.setPunti(15);
			bean.add(bean1);
			
			
			CartaFedeltaBean bean2= new CartaFedeltaBean();
			bean2.setCodice("1234567898");
			bean2.setPunti(20);
			bean.add(bean2);
			
			
			assertEquals(bean.size(), s.size());
			
			for(int i=0; i<s.size();i++) {
				assertEquals(s.get(i).getCodice(),bean.get(i).getCodice());
				assertEquals(s.get(i).getPunti(),bean.get(i).getPunti());
			}
		}
		@Test
		public void testAllElementsNull() throws SQLException{
			ArrayList<CartaFedeltaBean> a = null;
			try {
				a = cf.allElements(null);
				}catch(NullPointerException e)
				{
					assertNull(a);
				}
		}
		@Test
		public void testAllElementsVuoto() throws SQLException{
			ArrayList<CartaFedeltaBean> a = null;
			try {
				a = cf.allElements("");
				}catch(NullPointerException e)
				{
					assertNull(a);
				}
		}
		
		@Test
		public void testAllElementsNonValido()  {
			ArrayList<CartaFedeltaBean> a=null;
			try {
			a=cf.allElements("peppe dasc");
			}catch(SQLException e) {
				assertNull(a);
			}
		}
		@Test
		public void testNewInsertNew() throws Exception {      
		      
	        ITable expectedTable = new FlatXmlDataSetBuilder()
	                   .build(OrdineDAOTest.class.getClassLoader().getResourceAsStream("resources/db/expected/cartafedelta.xml"))
	                   .getTable("cartafedelta");
	     
	        CartaFedeltaBean bean = new  CartaFedeltaBean();
	        
	        bean.setCodice("1234567895");
	        bean.setPunti(25);
	        cf.newInsert(bean);
	        
	        IDatabaseTester tester = this.getDatabaseTester();
	        
	        ITable actualTable =  tester.getConnection().createDataSet().getTable("cartafedelta");       
	       
	       Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
		}
		
		@Test
		public void testNewInsertNotNew() throws Exception {      
	        CartaFedeltaBean bean = new  CartaFedeltaBean();
	        
	        bean.setCodice("1234567899");
	        bean.setPunti(15);
	        
	        
	        int i=0;
			try {
				cf.newInsert(bean);
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
				cf.newInsert(null);
			}
			catch(NullPointerException e)
			{
				i++;
			} catch (SQLException e) {
				i=0;
			}
			
			assertEquals(1,i);
			
		}
		
		@Test
		public void testDoUpdatePresente() throws Exception {
			ITable expectedTable = new FlatXmlDataSetBuilder()
	                .build(PresenteInDAOTest.class.getClassLoader().getResourceAsStream("resources/db/expected/cartafedeltaDoUpdate.xml"))
	                .getTable("cartafedelta");
			
			CartaFedeltaBean aggiornato= new CartaFedeltaBean();
			aggiornato.setCodice("1234567899");
			aggiornato.setPunti(16);
			
			cf.doUpdate(aggiornato);
			
			IDatabaseTester tester= this.getDatabaseTester();
			
	        ITable actualTable = tester.getConnection().createDataSet().getTable("cartafedelta");
			
	        Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
		}
		
		@Test
		public void testDoUpdateNonPresente() throws Exception {
			ITable expectedTable = new FlatXmlDataSetBuilder()
	                .build(PresenteInDAOTest.class.getClassLoader().getResourceAsStream("resources/db/expected/cartafedeltaDoUpdateNo.xml"))
	                .getTable("cartafedelta");
			
			CartaFedeltaBean aggiornato= new CartaFedeltaBean();
			aggiornato.setCodice("1234567891");
			aggiornato.setPunti(16);
			
			cf.doUpdate(aggiornato);
			
			IDatabaseTester tester= this.getDatabaseTester();
			
	        ITable actualTable = tester.getConnection().createDataSet().getTable("cartafedelta");
			
	        Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
		}
		
		@Test
		public void testDoUpdateNull() throws SQLException {
			
			boolean flag=false;
			try {
				cf.doUpdate(null);
			}
			catch(NullPointerException e){
				flag=true;
			}
			
			assertEquals(flag,true);
		}
	@Override
	protected DataSource getDataSource() {
		JdbcDataSource dataSource = new JdbcDataSource();
        dataSource.setURL("jdbc:h2:mem:test;DB_CLOSE_DELAY=-1;init=runscript from 'classpath:resources/db/init/cartafedelta.sql'");
        dataSource.setUser("root");
        dataSource.setPassword("veloce123");
        return dataSource;
	}

	@Override
	protected IDataSet getDataSet() throws Exception {
		return new FlatXmlDataSetBuilder().build(this.getClass().getClassLoader().getResourceAsStream("resources/db/init/cartafedelta.xml"));
	}

}
