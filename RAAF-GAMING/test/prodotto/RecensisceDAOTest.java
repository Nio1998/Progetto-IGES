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

public class RecensisceDAOTest extends DataSourceBasedDBTestCase{

	private DataSource ds;
	private RecensisceDAO rec;
	
	@Before
	public void setUp() throws Exception {
		super.setUp();
        ds = this.getDataSource();
        rec = new RecensisceDAO(ds);
	}

	@After
	public void tearDown() throws Exception {
		super.tearDown();
	}

	
	@Override
	protected DataSource getDataSource() {
		JdbcDataSource dataSource = new JdbcDataSource();
        dataSource.setURL("jdbc:h2:mem:test;DB_CLOSE_DELAY=-1;init=runscript from 'classpath:resources/db/init/recensisce.sql'");
        dataSource.setUser("root");
        dataSource.setPassword("veloce123");
        return dataSource;
	}

	@Override
	protected IDataSet getDataSet() throws Exception {
		return new FlatXmlDataSetBuilder().build(this.getClass().getClassLoader().getResourceAsStream("resources/db/init/recensisce.xml"));
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
		RecensisceBean r = rec.ricercaPerChiave("a.delucia19@gmail.com",1);
		RecensisceBean b = new RecensisceBean();
		
		b.setCliente("a.delucia19@gmail.com");
		b.setProdotto(1);
		b.setVoto(10);
		b.setCommento("bellissmo");
		
		assertEquals(r.getProdotto(), b.getProdotto());
		assertEquals(r.getCliente(),b.getCliente());
		assertEquals(r.getVoto(),b.getVoto());
		assertEquals(r.getCommento(),b.getCommento());
	}
	@Test
	public void testRicercaPerChiaveClienteVoid() throws SQLException{
		RecensisceBean a = null;
		try {
		a = rec.ricercaPerChiave("",1);
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
	}
	@Test
	public void testRicercaPerChiaveClienteNULL() throws SQLException{
		RecensisceBean a = null;
		try {
		a = rec.ricercaPerChiave(null,1);
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
	}
	@Test
	public void testRicercaPerChiaveClienteNotValid() throws SQLException{
		RecensisceBean a = null;
		try {
		a = rec.ricercaPerChiave("peppino",1);
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
	}
	@Test
	public void testRicercaPerChiaveProdottoNotValid() throws SQLException{
		RecensisceBean a = null;
		try {
		a = rec.ricercaPerChiave("a.delucia19@gmail.com",68);
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
	}
	@Test
	public void testRicercaPerChiaveCampiNotValid() throws SQLException{
		RecensisceBean a = null;
		try {
		a = rec.ricercaPerChiave("ia19@gmail.com",68);
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
	}
	@Test
	public void testAllElementsASC() throws SQLException
	{
		ArrayList<RecensisceBean> s = rec.allElements("prodotto asc");
		ArrayList<RecensisceBean> bean = new ArrayList<RecensisceBean>();
		
		RecensisceBean b = new RecensisceBean();
		b.setCliente("a.delucia19@gmail.com");
		b.setProdotto(1);
		b.setVoto(10);
		b.setCommento("bellissmo");
		bean.add(b);
		
		RecensisceBean b2 = new RecensisceBean();
		b2.setCliente("r.iuliano13@gmail.com");
		b2.setProdotto(1);
		b2.setVoto(10);
		b2.setCommento("awesome");
		bean.add(b2);
		
		RecensisceBean b1 = new RecensisceBean();
		b1.setCliente("a.maddaloni10@gmail.com");
		b1.setProdotto(2);
		b1.setVoto(7);
		b1.setCommento("vero veramente");
		bean.add(b1);
		
		
		
		
		assertEquals(s.size(), bean.size());
		
		for(int i=0; i<s.size();i++)
		{			
			assertEquals(s.get(i).getCliente(),bean.get(i).getCliente());
			assertEquals(s.get(i).getProdotto(),bean.get(i).getProdotto());
			assertEquals(s.get(i).getVoto(),bean.get(i).getVoto());
			assertEquals(s.get(i).getCommento(),bean.get(i).getCommento());
		}
		
	}
	@Test
	public void testAllElementsDESC() throws SQLException
	{
		ArrayList<RecensisceBean> s = rec.allElements("prodotto desc");
		ArrayList<RecensisceBean> bean = new ArrayList<RecensisceBean>();
		
		RecensisceBean b1 = new RecensisceBean();
		b1.setCliente("a.maddaloni10@gmail.com");
		b1.setProdotto(2);
		b1.setVoto(7);
		b1.setCommento("vero veramente");
		bean.add(b1);
		
		
		RecensisceBean b = new RecensisceBean();
		b.setCliente("a.delucia19@gmail.com");
		b.setProdotto(1);
		b.setVoto(10);
		b.setCommento("bellissmo");
		bean.add(b);
		
		RecensisceBean b2 = new RecensisceBean();
		b2.setCliente("r.iuliano13@gmail.com");
		b2.setProdotto(1);
		b2.setVoto(10);
		b2.setCommento("awesome");
		bean.add(b2);
		

		assertEquals(s.size(), bean.size());
		
		for(int i=0; i<s.size();i++)
		{			
			assertEquals(s.get(i).getCliente(),bean.get(i).getCliente());
			assertEquals(s.get(i).getProdotto(),bean.get(i).getProdotto());
			assertEquals(s.get(i).getVoto(),bean.get(i).getVoto());
			assertEquals(s.get(i).getCommento(),bean.get(i).getCommento());
		}
		
	}
	@Test
	public void testAllElementsNULL() throws SQLException
	{
		ArrayList<RecensisceBean> a = null;
		try {
		a = rec.allElements(null);
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
	}
	@Test
	public void testAllElementsNotValid() throws NullPointerException
	{
		ArrayList<RecensisceBean> a = null;
		try {
		a = rec.allElements("prodotto sc");
		}catch(SQLException e)
		{
			assertNull(a);
		}
	}
	@Test
	public void testAllElementsVoid() throws SQLException
	{
		ArrayList<RecensisceBean> a = null;
		try {
		a = rec.allElements("");
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
	}
	@Test
	public void testNewInsertNew() throws Exception {      
	      
        ITable expectedTable = new FlatXmlDataSetBuilder()
                   .build(OrdineDAOTest.class.getClassLoader().getResourceAsStream("resources/db/expected/recensisce.xml"))
                   .getTable("recensisce");
     
        RecensisceBean b1 = new RecensisceBean();
		b1.setCliente("f.peluso25@gmail.com");
		b1.setProdotto(3);
		b1.setVoto(8);
		b1.setCommento("mi pace");
      
     
       rec.newInsert(b1);
       
       IDatabaseTester tester = this.getDatabaseTester();
       
       ITable actualTable =  tester.getConnection().createDataSet().getTable("recensisce");       
      
      Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
		
	}
	@Test
	public void testNewInsertOld() {

		RecensisceBean b1 = new RecensisceBean();
		b1.setCliente("a.maddaloni10@gmail.com");
		b1.setProdotto(2);
		b1.setVoto(7);
		b1.setCommento("vero veramente");
	      
		
		int i=0;
		try {
			rec.newInsert(b1);
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
			rec.newInsert(null);
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
	public void testRicercaPerProdotto() throws SQLException{
		ArrayList<RecensisceBean> r = rec.ricercaPerProdotto(1);
		ArrayList<RecensisceBean> bean = new ArrayList<RecensisceBean>();
		
		RecensisceBean b = new RecensisceBean();;
		b.setCliente("a.delucia19@gmail.com");
		b.setProdotto(1);
		b.setVoto(10);
		b.setCommento("bellissmo");
		bean.add(b);
		
		RecensisceBean b2 = new RecensisceBean();
		
		b2.setCliente("r.iuliano13@gmail.com");
		b2.setProdotto(1);
		b2.setVoto(10);
		b2.setCommento("awesome");
		bean.add(b2);
		
		assertEquals(r.size(), bean.size());
		
		for(int i=0; i<r.size();i++)
		{			
			assertEquals(r.get(i).getCliente(),bean.get(i).getCliente());
			assertEquals(r.get(i).getProdotto(),bean.get(i).getProdotto());
			assertEquals(r.get(i).getVoto(),bean.get(i).getVoto());
			assertEquals(r.get(i).getCommento(),bean.get(i).getCommento());
		}
	}
	@Test
	public void testRicercaPerProdottoMinDiZero() throws SQLException
	{
		ArrayList<RecensisceBean> a = null;
		try {
		a = rec.ricercaPerProdotto(-1);
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
	}
	@Test
	public void testRicercaPerProdottoNotValid() throws NullPointerException
	{
		ArrayList<RecensisceBean> a = null;
		try {
		a = rec.ricercaPerProdotto(50);
		}catch(SQLException e)
		{
			assertNull(a);
		}
	}


}
