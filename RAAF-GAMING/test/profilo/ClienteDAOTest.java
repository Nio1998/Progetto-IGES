package profilo;

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
import org.h2.jdbcx.JdbcDataSource;
import org.junit.After;
import org.junit.Before;
import org.junit.Test;

import acquisto.OrdineDAOTest;
import magazzino.PresenteInDAOTest;
import prodotto.AbbonamentoBean;

public class ClienteDAOTest extends DataSourceBasedDBTestCase{

	private DataSource ds;
	private ClienteDAO cd;
	
	@Before
	public void setUp() throws Exception {
		super.setUp();
        ds = this.getDataSource();
        cd = new ClienteDAO(ds);
	}

	@After
	public void tearDown() throws Exception {
		super.tearDown();
	}

	@Test
	public void testRicercaPerChiavePresente() throws Exception {
	ClienteBean a=cd.ricercaPerChiave("antoniodelucia@hotmail.com");
	ClienteBean b= new ClienteBean();
	
	b.setEmail("antoniodelucia@hotmail.com");
	b.setNome("antonio");
	b.setCognome("de lucia");
	b.setData_di_nascita(java.sql.Date.valueOf("1998-12-06"));
	b.setPassword("Zlatanpato98");
	b.setCarta_fedelta("1234567899");
	b.setCartadicredito("1234123412341235");
	
	assertEquals(a.getNome(), b.getNome());
	assertEquals(a.getCognome(), b.getCognome());
	assertEquals(a.getData_di_nascita(), b.getData_di_nascita());
	assertEquals(a.getPassword(), b.getPassword());
	assertEquals(a.getCarta_fedelta(), b.getCarta_fedelta());
	assertEquals(a.getCartadicredito(), b.getCartadicredito());
	}
	
	@Test
	public void testRicercaPerChiaveNonPresente() throws Exception {
	ClienteBean a=cd.ricercaPerChiave("antoniodelucia98@hotmail.com");
	assertNull(a);
	
	}
	
	@Test
	public void testRicercaPerChiaveVuota() throws Exception {
	ClienteBean a=null;
	try {
	a=cd.ricercaPerChiave("");
	}catch(NullPointerException e){
	assertNull(a);
	}
	
	}

	@Test
	public void testRicercaPerChiaveNull() throws Exception {
	ClienteBean a=null;
	try {
	a=cd.ricercaPerChiave(null);
	}catch(NullPointerException e){
	assertNull(a);
	}
	}
	
	@Test
	public void testAllElementsASC() throws SQLException
	{
		ArrayList<ClienteBean> s = cd.allElements("email asc");
		ArrayList<ClienteBean> bean = new ArrayList<ClienteBean>();
		
		ClienteBean bean1=new ClienteBean();
		bean1.setEmail("antoniodelucia@hotmail.com");
		bean1.setNome("antonio");
		bean1.setCognome("de lucia");
		bean1.setData_di_nascita(java.sql.Date.valueOf("1998-12-06"));
		bean1.setPassword("Zlatanpato98");
		bean1.setCarta_fedelta("1234567899");
		bean1.setCartadicredito("1234123412341235");
		bean.add(bean1);
		
		ClienteBean bean2=new ClienteBean();
		bean2.setEmail("antoniomaddaloni@hotmail.com");
		bean2.setNome("antonio");
		bean2.setCognome("maddaloni");
		bean2.setData_di_nascita(java.sql.Date.valueOf("1998-11-15"));
		bean2.setPassword("Nola123");
		bean2.setCarta_fedelta("1234567898");
		bean2.setCartadicredito("1234123412341236");
		bean.add(bean2);
		
		assertEquals(bean.size(),s.size());
		
		for(int i=0; i<s.size();i++)
		{			
			assertEquals(s.get(i).getEmail(),bean.get(i).getEmail());
			assertEquals(s.get(i).getNome(),bean.get(i).getNome());
			assertEquals(s.get(i).getCognome(),bean.get(i).getCognome());
			assertEquals(s.get(i).getData_di_nascita(), bean.get(i).getData_di_nascita());
			assertEquals(s.get(i).getPassword(), bean.get(i).getPassword());
			assertEquals(s.get(i).getCarta_fedelta(), bean.get(i).getCarta_fedelta());
			assertEquals(s.get(i).getCartadicredito(), bean.get(i).getCartadicredito());
		}
	}
	
	@Test
	public void testAllElementsDESC() throws SQLException
	{
		ArrayList<ClienteBean> s = cd.allElements("email desc");
		ArrayList<ClienteBean> bean = new ArrayList<ClienteBean>();
		
		
		ClienteBean bean2=new ClienteBean();
		bean2.setEmail("antoniomaddaloni@hotmail.com");
		bean2.setNome("antonio");
		bean2.setCognome("maddaloni");
		bean2.setData_di_nascita(java.sql.Date.valueOf("1998-11-15"));
		bean2.setPassword("Nola123");
		bean2.setCarta_fedelta("1234567898");
		bean2.setCartadicredito("1234123412341236");
		bean.add(bean2);
		
		ClienteBean bean1=new ClienteBean();
		bean1.setEmail("antoniodelucia@hotmail.com");
		bean1.setNome("antonio");
		bean1.setCognome("de lucia");
		bean1.setData_di_nascita(java.sql.Date.valueOf("1998-12-06"));
		bean1.setPassword("Zlatanpato98");
		bean1.setCarta_fedelta("1234567899");
		bean1.setCartadicredito("1234123412341235");
		bean.add(bean1);
		
		assertEquals(bean.size(),s.size());
		
		for(int i=0; i<s.size();i++)
		{			
			assertEquals(s.get(i).getEmail(),bean.get(i).getEmail());
			assertEquals(s.get(i).getNome(),bean.get(i).getNome());
			assertEquals(s.get(i).getCognome(),bean.get(i).getCognome());
			assertEquals(s.get(i).getData_di_nascita(), bean.get(i).getData_di_nascita());
			assertEquals(s.get(i).getPassword(), bean.get(i).getPassword());
			assertEquals(s.get(i).getCarta_fedelta(), bean.get(i).getCarta_fedelta());
			assertEquals(s.get(i).getCartadicredito(), bean.get(i).getCartadicredito());
		}
	}
	@Test
	public void testAllElementsNull() throws SQLException{
		ArrayList<ClienteBean> a = null;
		try {
			a = cd.allElements(null);
			}catch(NullPointerException e)
			{
				assertNull(a);
			}
	}
	@Test
	public void testAllElementsVuoto() throws SQLException{
		ArrayList<ClienteBean> a = null;
		try {
			a = cd.allElements("");
			}catch(NullPointerException e)
			{
				assertNull(a);
			}
	}
	
	@Test
	public void testAllElementsNonValido()  {
		ArrayList<ClienteBean> a=null;
		try {
		a=cd.allElements("peppe dasc");
		}catch(SQLException e) {
			assertNull(a);
		}
	}
	
	@Test
	public void testDoUpdatePresente() throws Exception {
		ITable expectedTable = new FlatXmlDataSetBuilder()
                .build(PresenteInDAOTest.class.getClassLoader().getResourceAsStream("resources/db/expected/cartafedeltaDoUpdate.xml"))
                .getTable("cartafedelta");
		
		ClienteBean aggiornato= new ClienteBean();
		aggiornato.setEmail("antoniodelucia123@hotmail.com");
		aggiornato.setPassword("Zlatanpato1998");
		
		cd.doUpdate(aggiornato);
		
		IDatabaseTester tester= this.getDatabaseTester();
		
        ITable actualTable = tester.getConnection().createDataSet().getTable("cartafedelta");
		
        Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
	}
	
	@Test
	public void testDoUpdateNonPresente() throws Exception {
		ITable expectedTable = new FlatXmlDataSetBuilder()
                .build(PresenteInDAOTest.class.getClassLoader().getResourceAsStream("resources/db/expected/clienteDoUpdateNo.xml"))
                .getTable("cliente");
		
		ClienteBean aggiornato= new ClienteBean();
		aggiornato.setEmail("antonioilmigliore@hotmail.it");
		aggiornato.setPassword("nononono");
		
		cd.doUpdate(aggiornato);
		
		IDatabaseTester tester= this.getDatabaseTester();
		
        ITable actualTable = tester.getConnection().createDataSet().getTable("cliente");
		
        Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
	}
	
	@Test
	public void testDoUpdateNull() throws SQLException {
		
		boolean flag=false;
		try {
			cd.doUpdate(null);
		}
		catch(NullPointerException e){
			flag=true;
		}
		
		assertEquals(flag,true);
	}
	public void testNewInsertNew() throws Exception {      
	      
        ITable expectedTable = new FlatXmlDataSetBuilder()
                   .build(OrdineDAOTest.class.getClassLoader().getResourceAsStream("resources/db/expected/cliente.xml"))
                   .getTable("cliente");
     
        ClienteBean bean = new  ClienteBean();
     
       bean.setEmail("roccoiuliano@hotmail.com");
       bean.setNome("rocco");
       bean.setCognome("iuliano");
       bean.setData_di_nascita(java.sql.Date.valueOf("1998-01-25"));
       bean.setPassword("Nocera456");
       bean.setCarta_fedelta("1234567897");
       bean.setCartadicredito("1234123412341237");
      
       cd.newInsert(bean);
       
       IDatabaseTester tester = this.getDatabaseTester();
       
       ITable actualTable =  tester.getConnection().createDataSet().getTable("cliente");       
      
      Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
		
	}
	@Test
	public void testNewInsertNotNew() throws Exception {      
	      
		ClienteBean bean= new ClienteBean();
		
		bean.setEmail("roccoiuliano@hotmail.com");
		bean.setNome("rocco");
		bean.setCognome("iuliano");
		bean.setData_di_nascita(java.sql.Date.valueOf("1998-01-25"));
		bean.setPassword("Nocera456");
		bean.setCarta_fedelta("1234567897");
		bean.setCartadicredito("1234123412341237");
		
	      
		
		int i=0;
		try {
			cd.newInsert(bean);
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
			cd.newInsert(null);
		}
		catch(NullPointerException e)
		{
			i++;
		} catch (SQLException e) {
			i=0;
		}
		
		assertEquals(1,i);
		
	}
	
	@Override
	protected DataSource getDataSource() {
		JdbcDataSource dataSource = new JdbcDataSource();
        dataSource.setURL("jdbc:h2:mem:test;DB_CLOSE_DELAY=-1;init=runscript from 'classpath:resources/db/init/cliente.sql'");
        dataSource.setUser("root");
        dataSource.setPassword("veloce123");
        return dataSource;
	}

	@Override
	protected IDataSet getDataSet() throws Exception {
		return new FlatXmlDataSetBuilder().build(this.getClass().getClassLoader().getResourceAsStream("resources/db/init/cliente.xml"));
	}

}
