package profilo;

import static org.junit.Assert.*;

import java.sql.SQLException;

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
import magazzino.PresenteInDAOTest;



public class CartaDiCreditoDAOTest extends DataSourceBasedDBTestCase {

	private DataSource ds;
	private CartaDiCreditoDAO cdc;
	@Before
	public void setUp() throws Exception {
		super.setUp();
        ds = this.getDataSource();
        cdc = new CartaDiCreditoDAO(ds);
	}

	@After
	public void tearDown() throws Exception {
		super.tearDown();
	}


	@Override
	protected DataSource getDataSource() {
		JdbcDataSource dataSource = new JdbcDataSource();
        dataSource.setURL("jdbc:h2:mem:test;DB_CLOSE_DELAY=-1;init=runscript from 'classpath:resources/db/init/cartadicredito.sql'");
        dataSource.setUser("root");
        dataSource.setPassword("veloce123");
        return dataSource;
	}

	@Override
	protected IDataSet getDataSet() throws Exception {
		return new FlatXmlDataSetBuilder().build(this.getClass().getClassLoader().getResourceAsStream("resources/db/init/cartadicredito.xml"));
	}
	@Test
	public void testricercaPerChiavePresente() throws SQLException {
		CartaDiCreditoBean a= cdc.ricercaPerChiave("1234123412341235");
		CartaDiCreditoBean b = new CartaDiCreditoBean();

		b.setCodicecarta("1234123412341235");
		b.setData_scadenza(java.sql.Date.valueOf("2023-10-25"));
		b.setCodice_cvv(789);

		assertEquals(a.getCodicecarta(), b.getCodicecarta());
		assertEquals(a.getCodice_cvv(), b.getCodice_cvv());
		assertEquals(a.getData_scadenza(), b.getData_scadenza());
	}

	@Test
	public void testricercaPerChiaveNonPresente() throws SQLException {
		CartaDiCreditoBean a= cdc.ricercaPerChiave("1234123412341239");
		assertNull(a);
	}
	
	@Test
	public void testricercaPerChiaveVuota() throws SQLException {
		CartaDiCreditoBean a=null;
		try {
			a=cdc.ricercaPerChiave("");
		}catch(NullPointerException e) {
		assertNull(a);
		}
	}
	@Test
	public void testricercaPerChiaveNull() throws SQLException {
		CartaDiCreditoBean a=null;
		try {
			a=cdc.ricercaPerChiave(null);
		}catch(NullPointerException e) {
		assertNull(a);
		}
	}
	
	@Test
	public void testNewInsertNew() throws Exception {      
	      
        ITable expectedTable = new FlatXmlDataSetBuilder()
                   .build(OrdineDAOTest.class.getClassLoader().getResourceAsStream("resources/db/expected/cartadicredito.xml"))
                   .getTable("cartadicredito");
     
        CartaDiCreditoBean bean = new  CartaDiCreditoBean();
        
        bean.setCodicecarta("1234123412341239");
        bean.setData_scadenza(java.sql.Date.valueOf("2025-12-25"));
        bean.setCodice_cvv(119);
        cdc.newInsert(bean);
        IDatabaseTester tester = this.getDatabaseTester();
        
        ITable actualTable =  tester.getConnection().createDataSet().getTable("cartadicredito");       
       
       Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
	}
	
	@Test
	public void testNewInsertNotNew() throws Exception {      
        CartaDiCreditoBean bean = new  CartaDiCreditoBean();
        
        bean.setCodice_cvv(123);
        bean.setCodicecarta("1234123412341234");
        bean.setData_scadenza(java.sql.Date.valueOf("2025-11-12"));
        
        
        int i=0;
		try {
			cdc.newInsert(bean);
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
			cdc.newInsert(null);
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
                .build(PresenteInDAOTest.class.getClassLoader().getResourceAsStream("resources/db/expected/cartadicreditoDoUpdatePresente.xml"))
                .getTable("cartadicredito");
		
		CartaDiCreditoBean aggiornato= new CartaDiCreditoBean();
		aggiornato.setCodice_cvv(111);
		aggiornato.setData_scadenza(java.sql.Date.valueOf("2028-12-25"));
		aggiornato.setCodicecarta("1234123412341230");
		cdc.doUpdate(aggiornato,"1234123412341236" );
		
		IDatabaseTester tester= this.getDatabaseTester();
		
        ITable actualTable = tester.getConnection().createDataSet().getTable("cartadicredito");
		
        Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
	}
	@Test
	public void testDoUpdateGiaPresente() throws Exception {
		ITable expectedTable = new FlatXmlDataSetBuilder()
                .build(PresenteInDAOTest.class.getClassLoader().getResourceAsStream("resources/db/expected/cartadicreditoDoUpdateGiaPresente.xml"))
                .getTable("cartadicredito");
		CartaDiCreditoBean aggiornato= new CartaDiCreditoBean();
		aggiornato.setCodicecarta("1234123412341234");
		aggiornato.setCodice_cvv(125);
		aggiornato.setData_scadenza(java.sql.Date.valueOf("2025-11-10"));
		try {
			cdc.doUpdate(aggiornato, "1234123412341236");
		}catch(SQLException e) {
			
			IDatabaseTester tester= this.getDatabaseTester();
			
			ITable actualTable = tester.getConnection().createDataSet().getTable("cartadicredito");
		
			Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
		}
		
	}
	@Test
	public void testDoUpdateCodNonValido() throws Exception {
		ITable expectedTable = new FlatXmlDataSetBuilder()
                .build(PresenteInDAOTest.class.getClassLoader().getResourceAsStream("resources/db/expected/cartadicreditoDoUpdateGiaPresente.xml"))
                .getTable("cartadicredito");
		CartaDiCreditoBean aggiornato= new CartaDiCreditoBean();
		aggiornato.setCodicecarta("1234123412341234");
		aggiornato.setCodice_cvv(125);
		aggiornato.setData_scadenza(java.sql.Date.valueOf("2025-11-10"));
		try {
			cdc.doUpdate(aggiornato, "123");
		}catch(SQLException e) {
			
			IDatabaseTester tester= this.getDatabaseTester();
			
			ITable actualTable = tester.getConnection().createDataSet().getTable("cartadicredito");
		
			Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
		}
	}
	
	@Test
	public void testDoUpdateItemNull() throws Exception {
		ITable expectedTable = new FlatXmlDataSetBuilder()
                .build(PresenteInDAOTest.class.getClassLoader().getResourceAsStream("resources/db/expected/cartadicreditoDoUpdateGiaPresente.xml"))
                .getTable("cartadicredito");
		try {
			cdc.doUpdate(null, "1234123412341234");
		}catch(NullPointerException e) {
			
			IDatabaseTester tester= this.getDatabaseTester();
			
			ITable actualTable = tester.getConnection().createDataSet().getTable("cartadicredito");
		
			Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
		}
	}
	
	@Test
	public void testDoUpdateCodNull() throws Exception {
		ITable expectedTable = new FlatXmlDataSetBuilder()
                .build(PresenteInDAOTest.class.getClassLoader().getResourceAsStream("resources/db/expected/cartadicreditoDoUpdateGiaPresente.xml"))
                .getTable("cartadicredito");
		CartaDiCreditoBean aggiornato= new  CartaDiCreditoBean();
		aggiornato.setCodice_cvv(200);
		aggiornato.setData_scadenza(java.sql.Date.valueOf("2030-12-25"));
		aggiornato.setCodicecarta("123412341231");
		try {
			cdc.doUpdate(aggiornato, null);
		}catch(NullPointerException e) {
			
			IDatabaseTester tester= this.getDatabaseTester();
			
			ITable actualTable = tester.getConnection().createDataSet().getTable("cartadicredito");
		
			Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
		}
	}
}
