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

public class ParteDiDAOTest extends DataSourceBasedDBTestCase{
	private DataSource ds;
	private ParteDiDAO pad;
	
	@Before
	public void setUp() throws Exception {
		super.setUp();
        ds = this.getDataSource();
        pad = new ParteDiDAO(ds);
	}

	@After
	public void tearDown() throws Exception {
		super.tearDown();
	}

	@Override
	protected DataSource getDataSource() {
		JdbcDataSource dataSource = new JdbcDataSource();
        dataSource.setURL("jdbc:h2:mem:test;DB_CLOSE_DELAY=-1;init=runscript from 'classpath:resources/db/init/parteDi.sql'");
        dataSource.setUser("root");
        dataSource.setPassword("veloce123");
        return dataSource;
	}

	@Override
	protected IDataSet getDataSet() throws Exception {
		return new FlatXmlDataSetBuilder().build(this.getClass().getClassLoader().getResourceAsStream("resources/db/init/parteDi.xml"));
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
	public void testricercaPerChiave() throws NullPointerException, SQLException {
		ParteDiBean r = pad.ricercaPerChiave(11,"Avventura");
		ParteDiBean b = new ParteDiBean();
		
		b.setVideogioco(11);
		b.setCategoria("Avventura");
		
		assertEquals(r.getVideogioco(),b.getVideogioco());
		assertEquals(r.getCategoria(),b.getCategoria());
	}
	@Test
	public void testRicercaPerChiaveVideogiocoNotValid() throws SQLException{
		ParteDiBean a = null;
		try {
		a = pad.ricercaPerChiave(-1,"Avventura");
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
	}
	@Test
	public void testRicercaPerChiaveCategoriaNotValid() throws NullPointerException{
		ParteDiBean a = null;
		try {
		a = pad.ricercaPerChiave(11,"Avve");
		}catch(SQLException e)
		{
			assertNull(a);
		}
	}
	@Test
	public void testRicercaPerChiaveCategoriaNull() throws SQLException{
		ParteDiBean a = null;
		try {
		a = pad.ricercaPerChiave(11,null);
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
	}
	@Test
	public void testRicercaPerChiaveVideogiocoECategoriaNotValid() throws SQLException{
		ParteDiBean a = null;
		try {
		a = pad.ricercaPerChiave(-1,null);
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
	}
	@Test
	public void testAllElementsASC() throws SQLException
	{
		ArrayList<ParteDiBean> s = pad.allElements("videogioco asc");
		ArrayList<ParteDiBean> bean = new ArrayList<ParteDiBean>();
		
		ParteDiBean b = new ParteDiBean();
		b.setVideogioco(11);
		b.setCategoria("Avventura");
		bean.add(b);
		
		ParteDiBean b1 = new ParteDiBean();
		b1.setVideogioco(17);
		b1.setCategoria("Avventura");
		bean.add(b1);
		
		ParteDiBean b2 = new ParteDiBean();
		b2.setVideogioco(22);
		b2.setCategoria("Arcade");
		bean.add(b2);

		
		assertEquals(s.size(), bean.size());
		
		for(int i=0; i<s.size();i++)
		{			
		assertEquals(s.get(i).getVideogioco(),bean.get(i).getVideogioco());
		assertEquals(s.get(i).getCategoria(),bean.get(i).getCategoria());
		}
	}
	@Test
	public void testAllElementsDESC() throws SQLException
	{
		ArrayList<ParteDiBean> s = pad.allElements("videogioco desc");
		ArrayList<ParteDiBean> bean = new ArrayList<ParteDiBean>();
		
		ParteDiBean b2 = new ParteDiBean();
		b2.setVideogioco(22);
		b2.setCategoria("Arcade");
		bean.add(b2);
		
		ParteDiBean b1 = new ParteDiBean();
		b1.setVideogioco(17);
		b1.setCategoria("Avventura");
		bean.add(b1);
		
		ParteDiBean b = new ParteDiBean();
		b.setVideogioco(11);
		b.setCategoria("Avventura");
		bean.add(b);
		
		assertEquals(s.size(), bean.size());
		
		for(int i=0; i<s.size();i++)
		{			
		assertEquals(s.get(i).getVideogioco(),bean.get(i).getVideogioco());
		assertEquals(s.get(i).getCategoria(),bean.get(i).getCategoria());
		}
	}
	@Test
	public void testAllElementsNULL() throws SQLException
	{
		ArrayList<ParteDiBean> a = null;
		try {
		a = pad.allElements(null);
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
	}
	@Test
	public void testAllElementsNotValid() throws NullPointerException
	{
		ArrayList<ParteDiBean> a = null;
		try {
		a = pad.allElements("videogioco");
		}catch(SQLException e)
		{
			assertNull(a);
		}
	}
	@Test
	public void testAllElementsVoid() throws SQLException 
	{
		ArrayList<ParteDiBean> a = null;
		try {
		a = pad.allElements("");
		}catch(NullPointerException e)
		{
			assertNull(a);
		}
	}
	@Test
	public void testNewInsertNew() throws Exception {      
	      
        ITable expectedTable = new FlatXmlDataSetBuilder()
                   .build(OrdineDAOTest.class.getClassLoader().getResourceAsStream("resources/db/expected/parteDi.xml"))
                   .getTable("parte_di");
     
        ParteDiBean b = new ParteDiBean();
        b.setVideogioco(10);
        b.setCategoria("Simulazione");
        pad.newInsert(b);

       IDatabaseTester tester = this.getDatabaseTester();
       
       ITable actualTable =  tester.getConnection().createDataSet().getTable("parte_di");       
      
      Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
		
	}
	@Test
	public void testNewInsertOld() {

		ParteDiBean b1 = new ParteDiBean();
		b1.setVideogioco(17);
		b1.setCategoria("Avventura");

		int i=0;
		try {
			pad.newInsert(b1);
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
			pad.newInsert(null);
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
		public void testRicercaPerCategoria() throws SQLException {
		ArrayList<ParteDiBean> s = pad.ricercaPerCategoria("Avventura");
		ArrayList<ParteDiBean> bean = new ArrayList<ParteDiBean>();
		
		ParteDiBean b = new ParteDiBean();
		b.setVideogioco(11);
		b.setCategoria("Avventura");
		bean.add(b);
		
		ParteDiBean b1 = new ParteDiBean();
		b1.setVideogioco(17);
		b1.setCategoria("Avventura");
		bean.add(b1);
		
		assertEquals(s.size(), bean.size());
		
		for(int i=0; i<s.size();i++)
		{			
		assertEquals(s.get(i).getVideogioco(),bean.get(i).getVideogioco());
		assertEquals(s.get(i).getCategoria(),bean.get(i).getCategoria());
		}
	}
		@Test
		public void testRicercaPerCategoriaNotValid() throws NullPointerException
		{
			ArrayList<ParteDiBean> a = null;
			try {
			a = pad.ricercaPerCategoria("Avvenura");
			}catch(SQLException e)
			{
				assertNull(a);
			}
		}
		@Test
		public void testRicercaPerCategoriaVoid() throws SQLException
		{
			ArrayList<ParteDiBean> a = null;
			try {
			a = pad.ricercaPerCategoria("");
			}catch(NullPointerException e)
			{
				assertNull(a);
			}
		}
		@Test
		public void testRicercaPerCategoriaNull() throws SQLException
		{
			ArrayList<ParteDiBean> a = null;
			try {
			a = pad.ricercaPerCategoria("");
			}catch(NullPointerException e)
			{
				assertNull(a);
			}
		}
	
	

}
