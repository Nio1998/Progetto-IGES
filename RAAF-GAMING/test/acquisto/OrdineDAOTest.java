/**
 * 
 */
package acquisto;

import static org.junit.Assert.*;

import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;

import javax.sql.DataSource;

import org.dbunit.Assertion;
import org.dbunit.DataSourceBasedDBTestCase;
import org.dbunit.IDatabaseTester;
import org.dbunit.JdbcDatabaseTester;
import org.dbunit.dataset.DataSetException;
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
public class OrdineDAOTest  extends DataSourceBasedDBTestCase {
	private DataSource ds;
    private OrdineDAO od;
    
    @Override
	protected DataSource getDataSource() {
		JdbcDataSource dataSource = new JdbcDataSource();
        dataSource.setURL("jdbc:h2:mem:test;DB_CLOSE_DELAY=-1;init=runscript from 'classpath:resources/db/init/ordine.sql'");
        dataSource.setUser("root");
        dataSource.setPassword("veloce123");
        return dataSource;
	}

	@Override
	protected IDataSet getDataSet() throws Exception {
		return new FlatXmlDataSetBuilder().build(this.getClass().getClassLoader().getResourceAsStream("resources/db/init/ordine.xml"));
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
        od = new OrdineDAO(ds);
	}

	/**
	 * @throws java.lang.Exception
	 */
	@After
	public void tearDown() throws Exception {
		 super.tearDown();
	}

	/**
	 * Test method for {@link acquisto.OrdineDAO#ricercaPerChiave(java.lang.String)}.
	 * @throws SQLException 
	 */
	@Test
	public void testRicercaPerChiaveIdPresente() throws SQLException {
		OrdineBean b = od.ricercaPerChiave("15280754012");
		
		assertEquals(b.getCodice(), "15280754012");
		assertEquals(b.getCliente(), "f.peluso25@gmail.com");
		assertEquals(b.getGestore(),"ordine@admin.com");
		assertEquals(b.getIndirizzo_di_consegna(), "viale croce");
		assertEquals(b.getMetodo_di_pagamento(), "2134567891234567");
		assertEquals(b.getPrezzo_totale(), 78.831);
		assertEquals(b.getStato(), "spedito");
		assertEquals(b.getData_acquisto().toString(), "2021-12-31");
		
		
		
	}
	
	
	/**
	 * Test method for {@link acquisto.OrdineDAO#ricercaPerChiave(java.lang.String)}.
	 * @throws SQLException 
	 */
	@Test
	public void testRicercaPerChiaveIdNonPresente() throws SQLException {
		
		OrdineBean b = od.ricercaPerChiave("1");	
		assertNull(b);
		
	}
	
	
	/**
	 * Test method for {@link acquisto.OrdineDAO#ricercaPerChiave(java.lang.String)}.
	 * 
	 */
	@Test
	public void testRicercaPerChiaveIdVuota()  {
		
		OrdineBean b = null;
		try {
		b=od.ricercaPerChiave("");	
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
	 * Test method for {@link acquisto.OrdineDAO#ricercaPerChiave(java.lang.String)}.
	 * 
	 */
	@Test
	public void testRicercaPerChiaveIdNull()  {
		
		OrdineBean b = null;
		try {
		b=od.ricercaPerChiave(null);	
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
	 * Test method for {@link acquisto.OrdineDAO#allElements(java.lang.String)}.
	 * throws SQLException
	 */
	@Test
	public void testAllElementsASC() throws SQLException {
		
		ArrayList<OrdineBean> a = od.allElements("codice asc");
		ArrayList<OrdineBean> b = new ArrayList<OrdineBean>();
		OrdineBean bean = new OrdineBean();
		
		bean.setCodice("15280754012");
		bean.setCliente("f.peluso25@gmail.com");
		bean.setData_acquisto(java.sql.Date.valueOf("2021-12-31"));
		bean.setGestore("ordine@admin.com");
		bean.setIndirizzo_di_consegna("viale croce");
		bean.setMetodo_di_pagamento("2134567891234567");
		bean.setPrezzo_totale(78.831);
		bean.setStato("spedito");
		
		b.add(bean);
		
		
		OrdineBean bean2 = new OrdineBean();
		
		bean2.setCodice("26134054612");
		bean2.setCliente("f.peluso25@gmail.com");
		bean2.setData_acquisto(java.sql.Date.valueOf("2021-12-30"));
		bean2.setGestore("ordine@admin.com");
		bean2.setIndirizzo_di_consegna("viale croce");
		bean2.setMetodo_di_pagamento("2134567891234567");
		bean2.setPrezzo_totale(80.5);
		bean2.setStato("spedito");
		
		
		b.add(bean2);
		
		assertEquals(b.size(),a.size());
		
		
		for(int i=0; i<a.size();i++)
		{
			assertEquals(a.get(i).getCliente(),b.get(i).getCliente());
			assertEquals(a.get(i).getCodice(),b.get(i).getCodice());
			assertEquals(a.get(i).getGestore(),b.get(i).getGestore());
			assertEquals(a.get(i).getIndirizzo_di_consegna(),b.get(i).getIndirizzo_di_consegna());
			assertEquals(a.get(i).getData_acquisto().toString(),b.get(i).getData_acquisto().toString());
			assertEquals(a.get(i).getMetodo_di_pagamento(),b.get(i).getMetodo_di_pagamento());
			assertEquals(a.get(i).getPrezzo_totale(),b.get(i).getPrezzo_totale());
			assertEquals(a.get(i).getStato(),b.get(i).getStato());
		}
		
		
	}
	
	@Test
	public void testAllElementsDESC() throws SQLException {
		
		ArrayList<OrdineBean> a = od.allElements("codice desc");
		ArrayList<OrdineBean> b = new ArrayList<OrdineBean>();	
		
		OrdineBean bean2 = new OrdineBean();
		
		bean2.setCodice("26134054612");
		bean2.setCliente("f.peluso25@gmail.com");
		bean2.setData_acquisto(java.sql.Date.valueOf("2021-12-30"));
		bean2.setGestore("ordine@admin.com");
		bean2.setIndirizzo_di_consegna("viale croce");
		bean2.setMetodo_di_pagamento("2134567891234567");
		bean2.setPrezzo_totale(80.5);
		bean2.setStato("spedito");
		
		
		b.add(bean2);
		
		OrdineBean bean = new OrdineBean();
		
		bean.setCodice("15280754012");
		bean.setCliente("f.peluso25@gmail.com");
		bean.setData_acquisto(java.sql.Date.valueOf("2021-12-31"));
		bean.setGestore("ordine@admin.com");
		bean.setIndirizzo_di_consegna("viale croce");
		bean.setMetodo_di_pagamento("2134567891234567");
		bean.setPrezzo_totale(78.831);
		bean.setStato("spedito");
		
		b.add(bean);
		
		assertEquals(b.size(),a.size());
		
		
		for(int i=0; i<a.size();i++)
		{
			assertEquals(a.get(i).getCliente(),b.get(i).getCliente());
			assertEquals(a.get(i).getCodice(),b.get(i).getCodice());
			assertEquals(a.get(i).getGestore(),b.get(i).getGestore());
			assertEquals(a.get(i).getIndirizzo_di_consegna(),b.get(i).getIndirizzo_di_consegna());
			assertEquals(a.get(i).getData_acquisto().toString(),b.get(i).getData_acquisto().toString());
			assertEquals(a.get(i).getMetodo_di_pagamento(),b.get(i).getMetodo_di_pagamento());
			assertEquals(a.get(i).getPrezzo_totale(),b.get(i).getPrezzo_totale());
			assertEquals(a.get(i).getStato(),b.get(i).getStato());
		}
		
		
		
	}
	
	
	@Test
	public void testAllElementsNULL()  {
		
		ArrayList<OrdineBean> a = null;
		try {
		a = od.allElements(null);
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
		catch(SQLException e)
		{
			fail("Not throws NullPointer");
		}
		
	}
	
	
	@Test
	public void testAllElementsNotValid() {
		
		ArrayList<OrdineBean> a = null;
		try {
		a = od.allElements("codice as");
		}catch(SQLException e)
		{
			assertNull(a);
		}
		catch(NullPointerException e)
		{
			fail("Not throws SqlExceptionr");
		}
		
	}
	
	
	@Test
	public void testAllElementsVoid() {
		
		ArrayList<OrdineBean> a = null;
		try {
		a = od.allElements("");
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
	 * Test method for {@link acquisto.OrdineDAO#newInsert(acquisto.OrdineBean)}.
	 * @throws Exception 
	 */
	@Test
	public void testNewInsertNew() throws Exception {      
      
         ITable expectedTable = new FlatXmlDataSetBuilder()
                    .build(OrdineDAOTest.class.getClassLoader().getResourceAsStream("resources/db/expected/ordine.xml"))
                    .getTable("ordine");
      
        OrdineBean bean = new OrdineBean();
      
        bean.setCodice("15280754013");
        bean.setCliente("a.maddaloni25@gmail.com");
        bean.setData_acquisto(java.sql.Date.valueOf("2019-11-31"));
        bean.setGestore("null");
        bean.setIndirizzo_di_consegna("viale croce");
        bean.setMetodo_di_pagamento("2134567891234567");
        bean.setPrezzo_totale(80);
        bean.setStato("elaborazione");
      
        od.newInsert(bean);
        
        IDatabaseTester tester = this.getDatabaseTester();
        
        ITable actualTable =  tester.getConnection().createDataSet().getTable("ordine");       
       
       Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
		
	}
	
	
	/**
	 * Test method for {@link acquisto.OrdineDAO#newInsert(acquisto.OrdineBean)}.
	 * @throws Exception 
	 */
	@Test
	public void testNewInsertOld() throws Exception {

		
		OrdineBean bean = new OrdineBean();
		
		bean.setCodice("15280754012");
		bean.setCliente("a.maddaloni25@gmail.com");
		bean.setData_acquisto(java.sql.Date.valueOf("2019-11-31"));
		bean.setGestore("ordine@admin.com");
		bean.setIndirizzo_di_consegna("viale croce");
		bean.setMetodo_di_pagamento("2134567891234567");
		bean.setPrezzo_totale(80);
		bean.setStato("spedito");
		
		int i=0;
		try {
		od.newInsert(bean);
		}
		catch(SQLException e)
		{
			i++;
		}
		
		assertEquals(1,i);
		
		
		

		
		
		
		
	}
	
	
	/**
	 * Test method for {@link acquisto.OrdineDAO#newInsert(acquisto.OrdineBean)}.
	 */
	@Test
	public void testNewInsertNull() {
		
		int i=0;
		try {
			od.newInsert(null);
		}
		catch(NullPointerException e)
		{
			i++;
		} catch (SQLException e) {
			i=0;
		}
		
		assertEquals(1,i);
		
	}
	
	
	/**
	 * Test method for {@link acquisto.OrdineDAO#ricercaPerCliente(java.lang.String)}.
	 * @throws SQLException 
	 */
	@Test
	public void testRicercaPerClienteEsistente() throws SQLException {
		ArrayList<OrdineBean> a = od.ricercaPerCliente("f.peluso25@gmail.com");
		ArrayList<OrdineBean> b = new ArrayList<OrdineBean>();	
		
		
		OrdineBean bean = new OrdineBean();
		
		bean.setCodice("15280754012");
		bean.setCliente("f.peluso25@gmail.com");
		bean.setData_acquisto(java.sql.Date.valueOf("2021-12-31"));
		bean.setGestore("ordine@admin.com");
		bean.setIndirizzo_di_consegna("viale croce");
		bean.setMetodo_di_pagamento("2134567891234567");
		bean.setPrezzo_totale(78.831);
		bean.setStato("spedito");
		
		b.add(bean);
		
		
		
		OrdineBean bean2 = new OrdineBean();
		
		bean2.setCodice("26134054612");
		bean2.setCliente("f.peluso25@gmail.com");
		bean2.setData_acquisto(java.sql.Date.valueOf("2021-12-30"));
		bean2.setGestore("ordine@admin.com");
		bean2.setIndirizzo_di_consegna("viale croce");
		bean2.setMetodo_di_pagamento("2134567891234567");
		bean2.setPrezzo_totale(80.5);
		bean2.setStato("spedito");
		
		
		b.add(bean2);
		
		assertEquals(b.size(),a.size());
		
		
		for(int i=0; i<a.size();i++)
		{
			assertEquals(a.get(i).getCliente(),b.get(i).getCliente());
			assertEquals(a.get(i).getCodice(),b.get(i).getCodice());
			assertEquals(a.get(i).getGestore(),b.get(i).getGestore());
			assertEquals(a.get(i).getIndirizzo_di_consegna(),b.get(i).getIndirizzo_di_consegna());
			assertEquals(a.get(i).getData_acquisto().toString(),b.get(i).getData_acquisto().toString());
			assertEquals(a.get(i).getMetodo_di_pagamento(),b.get(i).getMetodo_di_pagamento());
			assertEquals(a.get(i).getPrezzo_totale(),b.get(i).getPrezzo_totale());
			assertEquals(a.get(i).getStato(),b.get(i).getStato());
		}
		
		
	}
	
	
	/**
	 * Test method for {@link acquisto.OrdineDAO#ricercaPerCliente(java.lang.String)}.
	 * @throws SQLException 
	 */
	@Test
	public void testRicercaPerClienteNonEsistente() throws SQLException {
		ArrayList<OrdineBean> a = od.ricercaPerCliente("a");
		assertEquals(0,a.size());
		
	}
	
	/**
	 * Test method for {@link acquisto.OrdineDAO#ricercaPerCliente(java.lang.String)}.
	 * @throws SQLException 
	 */
	@Test
	public void testRicercaPerClienteVoid() {
		ArrayList<OrdineBean> a = null;
		
		try {
		od.ricercaPerCliente("");
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
		catch(SQLException e)
		{
			assertNull(a);
		}
		
		
		
	}
	
	
	/**
	 * Test method for {@link acquisto.OrdineDAO#ricercaPerCliente(java.lang.String)}.
	 */
	@Test
	public void testRicercaPerClienteNull() {
		ArrayList<OrdineBean> a = null;
		
		try {
		od.ricercaPerCliente("");
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
		catch(SQLException e)
		{
			assertNull(a);
		}
	}
	
	
	/**
	 * Test method for {@link acquisto.OrdineDAO#getOrdiniNonConsegnati()}.
	 * @throws SQLException 
	 */
	@Test
	public void testGetOrdiniNonConsegnatiTuttiGiaConsegnati() throws SQLException {
		
		ArrayList<OrdineBean> a = od.getOrdiniNonConsegnati();
		
		assertEquals(0,a.size());
		
	}
	
	
	/**
	 * Test method for {@link acquisto.OrdineDAO#getOrdiniNonConsegnati()}.
	 * @throws SQLException 
	 */
	@Test
	public void testGetOrdiniNonConsegnati() throws Exception {

		ds.getConnection().prepareStatement("INSERT INTO ordine values('15280754013',"+"'"+java.sql.Date.valueOf("2019-11-31")+"'"+",'viale croce','a.maddaloni25@gmail.com','80',null,'elaborazione','2134567891234567');").executeUpdate();
		
		
		ArrayList<OrdineBean> a = od.getOrdiniNonConsegnati();

		if(a.size()==0)
			fail("Size 0");
		
		for(OrdineBean b : a)
			assertNull(b.getGestore());
			
	}
	
	/**
	 * Test method for {@link acquisto.OrdineDAO#doUpdate(acquisto.OrdineBean)}.
	 * @throws Exception 
	 */
	@Test
	public void testDoUpdatePresente() throws Exception {
		
		DataSource dataSource = new JdbcDataSource();
        ((JdbcDataSource) dataSource).setURL("jdbc:h2:mem:test;DB_CLOSE_DELAY=-1;init=runscript from 'classpath:resources/db/init/ordine.sql'");
        ((JdbcDataSource) dataSource).setUser("root");
        ((JdbcDataSource) dataSource).setPassword("veloce123");
        
       IDataSet i = new FlatXmlDataSetBuilder().build(this.getClass().getClassLoader().getResourceAsStream("resources/db/init/ordineupdate.xml"));
        
       ITable expectedTable = new FlatXmlDataSetBuilder()
               .build(OrdineDAOTest.class.getClassLoader().getResourceAsStream("resources/db/expected/ordineupdate.xml"))
               .getTable("ordine");
       
       
       OrdineBean bean2 = new OrdineBean();
		
		bean2.setCodice("26134054612");
		bean2.setCliente("f.peluso25@gmail.com");
		bean2.setData_acquisto(java.sql.Date.valueOf("2021-12-30"));
		bean2.setGestore("ordine@admin.com");
		bean2.setIndirizzo_di_consegna("viale croce");
		bean2.setMetodo_di_pagamento("2134567891234567");
		bean2.setPrezzo_totale(80.5);
		bean2.setStato("spedito");
		
		OrdineDAO d = new OrdineDAO(dataSource);
		
		d.doUpdate(bean2);
       
		 IDatabaseTester tester = this.getDatabaseTester();
       
		 ITable actualTable =  tester.getConnection().createDataSet().getTable("ordine");       
	       
	     Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
		
	}
	
	/**
	 * Test method for {@link acquisto.OrdineDAO#doUpdate(acquisto.OrdineBean)}.
	 * @throws Exception 
	 */
	@Test
	public void testDoUpdateNon() throws Exception {
		

        ITable expectedTable = new FlatXmlDataSetBuilder()
                   .build(OrdineDAOTest.class.getClassLoader().getResourceAsStream("resources/db/init/ordine.xml"))
                   .getTable("ordine");
		
		OrdineBean bean = new OrdineBean();
		bean.setCodice("1");
		bean.setCliente("f.peluso25@gmail.com");
		bean.setData_acquisto(java.sql.Date.valueOf("2021-12-31"));
		bean.setGestore("ordine@admin.com");
		bean.setIndirizzo_di_consegna("viale croce");
		bean.setMetodo_di_pagamento("2134567891234567");
		bean.setPrezzo_totale(78.831);
		bean.setStato("spedito");
		
		 od.doUpdate(bean);
	        
	     IDatabaseTester tester = this.getDatabaseTester();
	        
	     ITable actualTable =  tester.getConnection().createDataSet().getTable("ordine");       
	       
	     Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
		
		
		
	}
	
	/**
	 * Test method for {@link acquisto.OrdineDAO#doUpdate(acquisto.OrdineBean)}.
	 * @throws Exception 
	 */
	@Test
	public void testDoUpdatePresenteNull() throws Exception {		
		
		
		 ITable expectedTable = new FlatXmlDataSetBuilder()
                 .build(OrdineDAOTest.class.getClassLoader().getResourceAsStream("resources/db/init/ordine.xml"))
                 .getTable("ordine");
		
		try {
			od.doUpdate(null);
		} catch (NullPointerException e) {
			
			 	IDatabaseTester tester = this.getDatabaseTester();
		        ITable actualTable =  tester.getConnection().createDataSet().getTable("ordine"); 
			
			
		        Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
		}
		
		
		
	}



}
