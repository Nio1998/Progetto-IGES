package magazzino;

import static org.junit.Assert.*;

import java.io.InputStream;
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
import org.junit.Before;
import org.junit.Test;

import acquisto.OrdineDAOTest;

public class PresenteInDAOTest extends DataSourceBasedDBTestCase{

	private DataSource ds;
	private PresenteInDAO pdao;
	
	@Before
	public void setUp() throws Exception {
		super.setUp();
        ds = this.getDataSource();
        pdao= new PresenteInDAO(ds);
	}

	@After
	public void tearDown() throws Exception {
		super.tearDown();
	}
	
	@Override
	protected DataSource getDataSource() {
		JdbcDataSource dataSource = new JdbcDataSource();
        dataSource.setURL("jdbc:h2:mem:test;DB_CLOSE_DELAY=-1;init=runscript from 'classpath:resources/db/init/presentein.sql'");
        dataSource.setUser("root");
        dataSource.setPassword("veloce123");
        return dataSource;
	}

	@Override
	protected IDataSet getDataSet() throws Exception {
		return new FlatXmlDataSetBuilder().build(this.getClass().getClassLoader().getResourceAsStream("resources/db/init/presentein.xml"));
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
	public void testRicercaPerProdottoPresente() throws SQLException {
		
		ArrayList<PresenteInBean> presenti= pdao.ricercaPerProdotto("1");
		
		ArrayList<PresenteInBean> test= new ArrayList<PresenteInBean>();
		
		PresenteInBean bean= new PresenteInBean();
		bean.setMagazzino("Italia,Nocera Superiore");
		bean.setProdotto(1);
		bean.setQuantita_disponibile(197);
		test.add(bean);
		
		assertEquals(presenti.size(),test.size());
		
		for(int i=0;i<presenti.size();i++) {
			assertEquals(presenti.get(i).getMagazzino(),test.get(i).getMagazzino());
			assertEquals(presenti.get(i).getProdotto(),test.get(i).getProdotto());
			assertEquals(presenti.get(i).getQuantita_disponibile(),test.get(i).getQuantita_disponibile());
		}
	}
	
	@Test
	public void testRicercaPerProdottoNonPresente() throws SQLException {
		
		ArrayList<PresenteInBean> presenti= pdao.ricercaPerProdotto("10");
		assertEquals(presenti.size(),0);
	}
	
	@Test
	public void testRicercaPerProdottoEmpty() throws SQLException {
		
		ArrayList<PresenteInBean> presenti=null; 
		try {
			presenti= pdao.ricercaPerProdotto("");
		}
		catch(NullPointerException e) {
			assertNull(presenti);
		}
		
	}
	
	@Test
	public void testRicercaPerProdottoNull() throws SQLException {
		
		ArrayList<PresenteInBean> presenti=null; 
		try {
			presenti= pdao.ricercaPerProdotto(null);
		}
		catch(NullPointerException e) {
			assertNull(presenti);
		}
		
	}

	
	@Test
	public void testRicercaPerChiavePresente() throws SQLException {
		PresenteInBean presente= pdao.ricercaPerChiave(1, "Italia,Nocera Superiore");
		assertNotNull(presente);
		assertEquals(presente.getMagazzino(),"Italia,Nocera Superiore");
		assertEquals(presente.getProdotto(),1);
		assertEquals(presente.getQuantita_disponibile(),197);
	}
	
	@Test
	public void testRicercaPerChiaveNonPresenteMagazzino() throws SQLException {
		PresenteInBean presente= pdao.ricercaPerChiave(1, "abc");
		assertNull(presente);
	}
	
	@Test
	public void testRicercaPerChiaveNonPresenteProdotto() throws SQLException {
		PresenteInBean presente= pdao.ricercaPerChiave(50, "Italia,Nocera Superiore");
		assertNull(presente);
	}
	
	@Test
	public void testRicercaPerChiaveMagazzinoEmpty() throws SQLException {
		PresenteInBean presente=null;
		try {
			presente= pdao.ricercaPerChiave(1, "");
		}
		catch(NullPointerException e){
			assertNull(presente);
		}
	}
	
	@Test
	public void testRicercaPerChiaveMagazzinoNull() throws SQLException {
		PresenteInBean presente=null;
		try {
			presente= pdao.ricercaPerChiave(1, null);
		}
		catch(NullPointerException e){
			assertNull(presente);
		}
	}

	@Test
	public void testAllElementsASC() throws SQLException {
		ArrayList<PresenteInBean> test= new ArrayList<PresenteInBean>();
		PresenteInBean bean1= new PresenteInBean();
		bean1.setMagazzino("Italia,Nocera Superiore");
		bean1.setProdotto(1);
		bean1.setQuantita_disponibile(197);
		test.add(bean1);
		
		PresenteInBean bean2= new PresenteInBean();
		bean2.setMagazzino("Italia,Nocera Superiore");
		bean2.setProdotto(2);
		bean2.setQuantita_disponibile(19);
		test.add(bean2);
		
		PresenteInBean bean3= new PresenteInBean();
		bean3.setMagazzino("Italia,Solofra");
		bean3.setProdotto(3);
		bean3.setQuantita_disponibile(50);
		test.add(bean3);
		
		ArrayList<PresenteInBean> presenti= pdao.allElements("magazzino asc");
		
		assertEquals(presenti.size(),test.size());
		
		for(int i=0;i<presenti.size();i++) {
			assertEquals(presenti.get(i).getProdotto(),test.get(i).getProdotto());
			assertEquals(presenti.get(i).getMagazzino(),test.get(i).getMagazzino());
			assertEquals(presenti.get(i).getQuantita_disponibile(),test.get(i).getQuantita_disponibile());
		}
	}
	
	@Test
	public void testAllElementsDESC() throws SQLException {
		ArrayList<PresenteInBean> test= new ArrayList<PresenteInBean>();

		PresenteInBean bean3= new PresenteInBean();
		bean3.setMagazzino("Italia,Solofra");
		bean3.setProdotto(3);
		bean3.setQuantita_disponibile(50);
		test.add(bean3);
		
		PresenteInBean bean1= new PresenteInBean();
		bean1.setMagazzino("Italia,Nocera Superiore");
		bean1.setProdotto(1);
		bean1.setQuantita_disponibile(197);
		test.add(bean1);
		
		PresenteInBean bean2= new PresenteInBean();
		bean2.setMagazzino("Italia,Nocera Superiore");
		bean2.setProdotto(2);
		bean2.setQuantita_disponibile(19);
		test.add(bean2);
	
		ArrayList<PresenteInBean> presenti= pdao.allElements("magazzino desc");
		
		assertEquals(presenti.size(),test.size());
		
		for(int i=0;i<presenti.size();i++) {
			assertEquals(presenti.get(i).getProdotto(),test.get(i).getProdotto());
			assertEquals(presenti.get(i).getMagazzino(),test.get(i).getMagazzino());
			assertEquals(presenti.get(i).getQuantita_disponibile(),test.get(i).getQuantita_disponibile());
		}
	}
	
	@Test
	public void testAllElementsNonValido() {
		ArrayList<PresenteInBean> presenti= null;
		try {
			presenti=pdao.allElements("abc asc");
		}
		catch(SQLException e) {
			assertNull(presenti);
		}
	}
	
	@Test
	public void testAllElementsEmpty() throws SQLException {
		ArrayList<PresenteInBean> presenti= null;
		try {
			presenti=pdao.allElements("");
		}
		catch(NullPointerException e) {
			assertNull(presenti);
		}
	}
	
	@Test
	public void testAllElementsNull() throws SQLException {
		ArrayList<PresenteInBean> presenti= null;
		try {
			presenti=pdao.allElements(null);
		}
		catch(NullPointerException e) {
			assertNull(presenti);
		}
	}
	
	
	

	@Test
	public void testNewInsertNuovo() throws Exception  {	
		//rappresenta il db dopo la insert, come me lo aspetto
		ITable expectedTable = new FlatXmlDataSetBuilder()
                .build(PresenteInDAOTest.class.getClassLoader().getResourceAsStream("resources/db/expected/presenteinInsertExpected.xml"))
                .getTable("presente_in");
		
		PresenteInBean nuovo= new PresenteInBean();
		nuovo.setProdotto(5);
		nuovo.setMagazzino("Italia,Nocera Superiore");
		nuovo.setQuantita_disponibile(10);
		
		pdao.newInsert(nuovo);
		
		IDatabaseTester tester= this.getDatabaseTester();
	
        ITable actualTable = tester.getConnection().createDataSet().getTable("presente_in");
		
        Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
	}
	
	@Test
	public void testNewInsertExists(){	
		PresenteInBean nuovo= new PresenteInBean();
		nuovo.setProdotto(1);
		nuovo.setMagazzino("Italia,Nocera Superiore");
		nuovo.setQuantita_disponibile(197);
		
		boolean flag=false;
		try {
			pdao.newInsert(nuovo);
		}
		catch(SQLException e) {
			flag=true;
			assertEquals(flag,true);
		}
	
	}
	
	@Test
	public void testNewInsertNull() throws SQLException  {	
		//rappresenta il db dopo la insert
		boolean flag=false;
		try {
			pdao.newInsert(null);
		}
		catch(NullPointerException e) {
			flag=true;
			assertEquals(flag,true);
		}
	
	}

	@Test
	public void testDoUpdateExists() throws Exception {
		//rappresenta il db dopo la insert, come me lo aspetto
		ITable expectedTable = new FlatXmlDataSetBuilder()
                .build(PresenteInDAOTest.class.getClassLoader().getResourceAsStream("resources/db/expected/presenteinUpdateExpected.xml"))
                .getTable("presente_in");
		
		PresenteInBean aggiornato= new PresenteInBean();
		aggiornato.setProdotto(1);
		aggiornato.setMagazzino("Italia,Nocera Superiore");
		aggiornato.setQuantita_disponibile(196);
		
		pdao.doUpdate(aggiornato);
		
		IDatabaseTester tester= this.getDatabaseTester();
		
        ITable actualTable = tester.getConnection().createDataSet().getTable("presente_in");
		
        Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
		
	}
	
	@Test
	public void testDoUpdateNotExists() throws Exception {
		
		//rappresenta il db dopo la insert, come me lo aspetto
		ITable expectedTable = new FlatXmlDataSetBuilder()
                .build(PresenteInDAOTest.class.getClassLoader().getResourceAsStream("resources/db/expected/presenteinNonModificatoExpected.xml"))
                .getTable("presente_in");
		
		PresenteInBean aggiornato= new PresenteInBean();
		aggiornato.setProdotto(50);//codice prodotto non esistente
		aggiornato.setMagazzino("Italia,Nocera Superiore");
		aggiornato.setQuantita_disponibile(196);
	
		pdao.doUpdate(aggiornato);
	
		IDatabaseTester tester= this.getDatabaseTester();
		
        ITable actualTable = tester.getConnection().createDataSet().getTable("presente_in");
		
        Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
	}
	
	@Test
	public void testDoUpdateNull() throws SQLException {
		
		boolean flag=false;
		try {
			pdao.doUpdate(null);
		}
		catch(NullPointerException e){
			flag=true;
		}
		
		assertEquals(flag,true);
	}
	
	@Test
	public void testRifornituraPresente() throws Exception {
		//rappresenta il db dopo la insert, come me lo aspetto
		ITable expectedTable = new FlatXmlDataSetBuilder()
                .build(PresenteInDAOTest.class.getClassLoader().getResourceAsStream("resources/db/expected/presenteinRifornituraPresenteExpected.xml"))
                .getTable("presente_in");
		
		PresenteInBean aggiornato= new PresenteInBean();
		aggiornato.setProdotto(1);//codice prodotto non esistente
		aggiornato.setMagazzino("Italia,Nocera Superiore");
		aggiornato.setQuantita_disponibile(200);
		
		pdao.rifornitura(aggiornato);
		
		IDatabaseTester tester= this.getDatabaseTester();
		
        ITable actualTable = tester.getConnection().createDataSet().getTable("presente_in");
		
        Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
	}
	
	@Test
	public void testRifornituraNonPresente() throws Exception {
		//rappresenta il db dopo la insert, come me lo aspetto
		ITable expectedTable = new FlatXmlDataSetBuilder()
                .build(PresenteInDAOTest.class.getClassLoader().getResourceAsStream("resources/db/expected/presenteinNonModificatoExpected.xml"))
                .getTable("presente_in");
		
		PresenteInBean aggiornato= new PresenteInBean();
		aggiornato.setProdotto(1);//codice prodotto non esistente
		aggiornato.setMagazzino("abc");
		aggiornato.setQuantita_disponibile(200);
		
		pdao.rifornitura(aggiornato);
		
		IDatabaseTester tester= this.getDatabaseTester();
		
        ITable actualTable = tester.getConnection().createDataSet().getTable("presente_in");
		
        Assertion.assertEquals(new SortedTable(expectedTable), new SortedTable(actualTable));
	}
	
	@Test
	public void testRifornituraNull() throws SQLException{
		boolean flag=false;
		try {
			pdao.rifornitura(null);
		}
		catch(NullPointerException e) {
			flag=true;
		}
		
		assertEquals(flag,true);
		
	}
	
	
	@Test
	public void testRicercaPerMagazzinoPresente() throws SQLException {
		ArrayList<PresenteInBean> ottenuto= pdao.ricercaPerMagazzino("Italia,Nocera Superiore");
		
		ArrayList<PresenteInBean> test= new ArrayList<PresenteInBean>();
		PresenteInBean bean1= new PresenteInBean();
		bean1.setProdotto(1);
		bean1.setMagazzino("Italia,Nocera Superiore");
		bean1.setQuantita_disponibile(197);
		test.add(bean1);
		
		PresenteInBean bean2= new PresenteInBean();
		bean2.setProdotto(2);
		bean2.setMagazzino("Italia,Nocera Superiore");
		bean2.setQuantita_disponibile(19);
		test.add(bean2);
		
		assertEquals(ottenuto.size(),test.size());
		for(int i=0;i<ottenuto.size();i++) {
			assertEquals(ottenuto.get(i).getProdotto(),test.get(i).getProdotto());
			assertEquals(ottenuto.get(i).getMagazzino(),test.get(i).getMagazzino());
			assertEquals(ottenuto.get(i).getQuantita_disponibile(),test.get(i).getQuantita_disponibile());
		}
		
		
	}
	
	@Test
	public void testRicercaPerMagazzinoNonPresente() throws SQLException {
		ArrayList<PresenteInBean> ottenuto= pdao.ricercaPerMagazzino("abc");
		assertEquals(ottenuto.size(),0);
	}
	
	@Test
	public void testRicercaPerMagazzinoEmpty() throws SQLException {
		boolean flag=false;
		ArrayList<PresenteInBean> ottenuto=null;
		try {
			ottenuto= pdao.ricercaPerMagazzino("");
		}
		catch(NullPointerException e){
			flag=true;
		}
		assertEquals(flag,true);
		assertNull(ottenuto);
	}
	
	@Test
	public void testRicercaPerMagazzinoNull() throws SQLException {
		boolean flag=false;
		ArrayList<PresenteInBean> ottenuto=null;
		try {
			ottenuto= pdao.ricercaPerMagazzino(null);
		}
		catch(NullPointerException e){
			flag=true;
		}
		assertEquals(flag,true);
		assertNull(ottenuto);
	}


}
