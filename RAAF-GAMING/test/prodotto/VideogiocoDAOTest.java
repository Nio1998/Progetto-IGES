package prodotto;

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
import org.dbunit.operation.DatabaseOperation;
import org.h2.jdbcx.JdbcDataSource;
import org.junit.After;
import org.junit.Before;
import org.junit.Test;

import magazzino.PresenteInDAO;
import magazzino.PresenteInDAOTest;

public class VideogiocoDAOTest extends DataSourceBasedDBTestCase{

	private DataSource ds;
	private VideogiocoDAO vdao;
	
	@Before
	public void setUp() throws Exception {
		super.setUp();
        ds = this.getDataSource();
        vdao= new VideogiocoDAO(ds);
	}

	@After
	public void tearDown() throws Exception {
		super.tearDown();
	}
	
	@Override
	protected DataSource getDataSource() {
		JdbcDataSource dataSource = new JdbcDataSource();
        dataSource.setURL("jdbc:h2:mem:test;DB_CLOSE_DELAY=-1;init=runscript from 'classpath:resources/db/init/videogioco.sql'");
        dataSource.setUser("root");
        dataSource.setPassword("veloce123");
        return dataSource;
	}

	@Override
	protected IDataSet getDataSet() throws Exception {
		return new FlatXmlDataSetBuilder().build(this.getClass().getClassLoader().getResourceAsStream("resources/db/init/videogioco.xml"));
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
	public void testRicercaPerChiavePresente() throws SQLException {
		VideogiocoBean ottenuto= vdao.ricercaPerChiave("1");
		
		assertEquals(ottenuto.getProdotto(),1);
		assertEquals(ottenuto.getDimensione(),50.5);
		assertEquals(ottenuto.getPegi(),18);
		assertEquals(ottenuto.getEdizione_limitata(),true);
		assertEquals(ottenuto.getNcd(),1);
		assertNull(ottenuto.getVkey());
		assertEquals(ottenuto.getSoftware_house(),"Naughty Dog");
	}
	
	@Test
	public void testRicercaPerChiaveNonPresente() throws SQLException {
		VideogiocoBean ottenuto= vdao.ricercaPerChiave("50");
		assertNull(ottenuto);
	}
	
	@Test
	public void testRicercaPerChiaveEmpty() throws SQLException {
		VideogiocoBean ottenuto= null;
		try {
			vdao.ricercaPerChiave("");
		}
		catch(NullPointerException e) {
			assertNull(ottenuto);
		}
		
	}
	
	@Test
	public void testRicercaPerChiaveNull() throws SQLException {
		VideogiocoBean ottenuto= null;
		try {
			vdao.ricercaPerChiave(null);
		}
		catch(NullPointerException e) {
			assertNull(ottenuto);
		}
		
	}

	@Test
	public void testAllElementsASC() throws SQLException {
		ArrayList<VideogiocoBean> ottenuti= vdao.allElements("prodotto asc");
		
		ArrayList<VideogiocoBean> test= new ArrayList<VideogiocoBean>();
		VideogiocoBean bean1= new VideogiocoBean();
		bean1.setProdotto(1);
		bean1.setDimensione(50.5);
		bean1.setPegi(18);
		bean1.setEdizione_limitata(true);
		bean1.setNcd(1);
		bean1.setVkey(null);
		bean1.setSoftware_house("Naughty Dog");
		test.add(bean1);
		
		VideogiocoBean bean2= new VideogiocoBean();
		bean2.setProdotto(2);
		bean2.setDimensione(50.5);
		bean2.setPegi(18);
		bean2.setEdizione_limitata(false);
		bean2.setNcd(0);
		bean2.setVkey("avchdcjjdeerju");
		bean2.setSoftware_house("Activision");
		test.add(bean2);
		
		VideogiocoBean bean3= new VideogiocoBean();
		bean3.setProdotto(3);
		bean3.setDimensione(40.0);
		bean3.setPegi(18);
		bean3.setEdizione_limitata(true);
		bean3.setNcd(2);
		bean3.setVkey(null);
		bean3.setSoftware_house("Epic Games");
		test.add(bean3);
		
		VideogiocoBean bean4= new VideogiocoBean();
		bean4.setProdotto(4);
		bean4.setDimensione(50.0);
		bean4.setPegi(12);
		bean4.setEdizione_limitata(false);
		bean4.setNcd(0);
		bean4.setVkey("cxwdfc64fdbewa");
		bean4.setSoftware_house("Ubisoft");
		test.add(bean4);
		
		assertEquals(ottenuti.size(),test.size());
		
		for(int i=0;i<ottenuti.size();i++) {
			assertEquals(ottenuti.get(i).getProdotto(),test.get(i).getProdotto());
			assertEquals(ottenuti.get(i).getDimensione(),test.get(i).getDimensione());
			assertEquals(ottenuti.get(i).getPegi(),test.get(i).getPegi());
			assertEquals(ottenuti.get(i).getEdizione_limitata(),test.get(i).getEdizione_limitata());
			assertEquals(ottenuti.get(i).getNcd(),test.get(i).getNcd());
			assertEquals(ottenuti.get(i).getVkey(),test.get(i).getVkey());
			assertEquals(ottenuti.get(i).getSoftware_house(),test.get(i).getSoftware_house());
		}
	}

	@Test
	public void testAllElementsDESC() throws SQLException {
		ArrayList<VideogiocoBean> ottenuti= vdao.allElements("prodotto desc");
		
		ArrayList<VideogiocoBean> test= new ArrayList<VideogiocoBean>();
		VideogiocoBean bean4= new VideogiocoBean();
		bean4.setProdotto(4);
		bean4.setDimensione(50.0);
		bean4.setPegi(12);
		bean4.setEdizione_limitata(false);
		bean4.setNcd(0);
		bean4.setVkey("cxwdfc64fdbewa");
		bean4.setSoftware_house("Ubisoft");
		test.add(bean4);
		
		VideogiocoBean bean3= new VideogiocoBean();
		bean3.setProdotto(3);
		bean3.setDimensione(40.0);
		bean3.setPegi(18);
		bean3.setEdizione_limitata(true);
		bean3.setNcd(2);
		bean3.setVkey(null);
		bean3.setSoftware_house("Epic Games");
		test.add(bean3);
		
		VideogiocoBean bean2= new VideogiocoBean();
		bean2.setProdotto(2);
		bean2.setDimensione(50.5);
		bean2.setPegi(18);
		bean2.setEdizione_limitata(false);
		bean2.setNcd(0);
		bean2.setVkey("avchdcjjdeerju");
		bean2.setSoftware_house("Activision");
		test.add(bean2);
		
		VideogiocoBean bean1= new VideogiocoBean();
		bean1.setProdotto(1);
		bean1.setDimensione(50.5);
		bean1.setPegi(18);
		bean1.setEdizione_limitata(true);
		bean1.setNcd(1);
		bean1.setVkey(null);
		bean1.setSoftware_house("Naughty Dog");
		test.add(bean1);
		
		assertEquals(ottenuti.size(),test.size());
		
		for(int i=0;i<ottenuti.size();i++) {
			assertEquals(ottenuti.get(i).getProdotto(),test.get(i).getProdotto());
			assertEquals(ottenuti.get(i).getDimensione(),test.get(i).getDimensione());
			assertEquals(ottenuti.get(i).getPegi(),test.get(i).getPegi());
			assertEquals(ottenuti.get(i).getEdizione_limitata(),test.get(i).getEdizione_limitata());
			assertEquals(ottenuti.get(i).getNcd(),test.get(i).getNcd());
			assertEquals(ottenuti.get(i).getVkey(),test.get(i).getVkey());
			assertEquals(ottenuti.get(i).getSoftware_house(),test.get(i).getSoftware_house());
		}
	}
	
	@Test
	public void testAllElementsNonValido() throws SQLException {
		
		ArrayList<VideogiocoBean> ottenuti;
		try {
			ottenuti=vdao.allElements("abc desc");
		}
		catch(SQLException e) {
			ottenuti=null;
		}
		assertNull(ottenuti);
		
	}
	
	@Test
	public void testAllElementsEmpty() throws SQLException {
		
		ArrayList<VideogiocoBean> ottenuti;
		try {
			ottenuti=vdao.allElements("");
		}
		catch(NullPointerException e) {
			ottenuti=null;
		}
		assertNull(ottenuti);
		
	}
	
	@Test
	public void testAllElementsNull() throws SQLException {
		
		ArrayList<VideogiocoBean> ottenuti;
		try {
			ottenuti=vdao.allElements(null);
		}
		catch(NullPointerException e) {
			ottenuti=null;
		}
		assertNull(ottenuti);
		
	}
	
	@Test
	public void testNewInsertNuovo() throws Exception {
		VideogiocoBean nuovo= new VideogiocoBean();
		nuovo.setProdotto(5);
		nuovo.setDimensione(30.5);
		nuovo.setEdizione_limitata(true);
		nuovo.setNcd(3);
		nuovo.setVkey(null);
		nuovo.setPegi(8);
		nuovo.setSoftware_house("Epic Games");
		
		vdao.newInsert(nuovo);
		
		//rappresenta il db dopo la insert, come me lo aspetto
		ITable expectedTable = new FlatXmlDataSetBuilder()
                .build(VideogiocoDAOTest.class.getClassLoader().getResourceAsStream("resources/db/expected/videogiocoInsertExpected.xml"))
                .getTable("videogioco");
		
		
		IDatabaseTester tester= this.getDatabaseTester();
		
        ITable actualTable = tester.getConnection().createDataSet().getTable("videogioco");
		
        Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
	}
	
	@Test
	public void testNewInsertExsists()  {
		VideogiocoBean nuovo= new VideogiocoBean();
		nuovo.setProdotto(1);
		nuovo.setDimensione(30.5);
		nuovo.setEdizione_limitata(true);
		nuovo.setNcd(3);
		nuovo.setVkey(null);
		nuovo.setPegi(8);
		nuovo.setSoftware_house("Epic Games");
		
		boolean flag=false;
		try {
			vdao.newInsert(nuovo);
		}
		catch(SQLException e) {
			flag=true;
		}
		
		assertEquals(flag,true);
		
	}
	
	@Test
	public void testNewInsertNull() throws SQLException  {
		boolean flag=false;
		try {
			vdao.newInsert(null);
		}
		catch(NullPointerException e) {
			flag=true;
		}
		
		assertEquals(flag,true);
		
	}
	
	@Test
	public void testGetTopRecensioneExists() throws SQLException {
		VideogiocoBean migliorVotato= vdao.getTopRecensione();
		
		assertEquals(migliorVotato.getProdotto(),3);
		assertEquals(migliorVotato.getDimensione(),40.0);
		assertEquals(migliorVotato.getPegi(),18);
		assertEquals(migliorVotato.getEdizione_limitata(),true);
		assertEquals(migliorVotato.getNcd(),2);
		assertNull(migliorVotato.getVkey());
		assertEquals(migliorVotato.getSoftware_house(),"Epic Games");
	}
	
	@Test
	public void testGetTopRecensioneNotExists() throws SQLException {
		ds.getConnection().prepareStatement("DELETE FROM videogioco WHERE prodotto=1;").execute();
		ds.getConnection().prepareStatement("DELETE FROM videogioco WHERE prodotto=2;").execute();
		ds.getConnection().prepareStatement("DELETE FROM videogioco WHERE prodotto=3;").execute();
		ds.getConnection().prepareStatement("DELETE FROM videogioco WHERE prodotto=4;").execute();
		
		VideogiocoBean migliorVotato= vdao.getTopRecensione();
		assertNull(migliorVotato);

	}
	
	@Test
	public void testGetUltimoUscitoCodeOk() throws SQLException {
		VideogiocoBean ultimo= vdao.getUltimoUscito(1);
		assertEquals(ultimo.getProdotto(),2);
		assertEquals(ultimo.getDimensione(),50.5);
		assertEquals(ultimo.getPegi(),18);
		assertEquals(ultimo.getEdizione_limitata(),false);
		assertEquals(ultimo.getNcd(),0);
		assertEquals(ultimo.getVkey(),"avchdcjjdeerju");
		assertEquals(ultimo.getSoftware_house(),"Activision");
	}
	
	@Test
	public void testGetUltimoUscitoCodeNO() throws SQLException {
		VideogiocoBean ultimo=null;
		try {
			ultimo= vdao.getUltimoUscito(-10);
		}
		catch(NullPointerException e) {
			assertNull(ultimo);
		}
	}
	
	@Test
	public void testGetVideogiochiScontatiOk() throws SQLException {
		ArrayList<VideogiocoBean> ottenuti= vdao.getVideogiochiScontati(1, 2);
		
		ArrayList<VideogiocoBean> test= new ArrayList<VideogiocoBean>();
		VideogiocoBean bean1= new VideogiocoBean();
		bean1.setProdotto(3);
		bean1.setDimensione(40.0);
		bean1.setEdizione_limitata(true);
		bean1.setNcd(2);
		bean1.setVkey(null);
		bean1.setPegi(18);
		bean1.setSoftware_house("Epic Games");
		test.add(bean1);
		
		VideogiocoBean bean2= new VideogiocoBean();
		bean2.setProdotto(4);
		bean2.setDimensione(50.0);
		bean2.setEdizione_limitata(false);
		bean2.setNcd(0);
		bean2.setVkey("cxwdfc64fdbewa");
		bean2.setPegi(12);
		bean2.setSoftware_house("Ubisoft");
		test.add(bean2);
		
		assertEquals(ottenuti.size(),test.size());
		for(int i=0;i<ottenuti.size();i++) {
			assertEquals(ottenuti.get(i).getProdotto(),test.get(i).getProdotto());
			assertEquals(ottenuti.get(i).getDimensione(),test.get(i).getDimensione());
			assertEquals(ottenuti.get(i).getPegi(),test.get(i).getPegi());
			assertEquals(ottenuti.get(i).getEdizione_limitata(),test.get(i).getEdizione_limitata());
			assertEquals(ottenuti.get(i).getNcd(),test.get(i).getNcd());
			assertEquals(ottenuti.get(i).getVkey(),test.get(i).getVkey());
			assertEquals(ottenuti.get(i).getSoftware_house(),test.get(i).getSoftware_house());
		}
	}
	
	@Test
	public void testGetVideogiochiScontatiCode2NO() throws SQLException {
		ArrayList<VideogiocoBean> ottenuti=null;
		try {
			ottenuti= vdao.getVideogiochiScontati(1, -2);
		}
		catch(NullPointerException e) {
			assertNull(ottenuti);
		}
	}
	
	@Test
	public void testGetVideogiochiScontatiCode1NO() throws SQLException {
		ArrayList<VideogiocoBean> ottenuti=null;
		try {
			ottenuti= vdao.getVideogiochiScontati(-1, 2);
		}
		catch(NullPointerException e) {
			assertNull(ottenuti);
		}
	}
	
	@Test
	public void testGetVideogiochiScontatiEquals() throws SQLException {
		ArrayList<VideogiocoBean> ottenuti=null;
		try {
			ottenuti= vdao.getVideogiochiScontati(1, 1);
		}
		catch(NullPointerException e) {
			assertNull(ottenuti);
		}
	}

}
