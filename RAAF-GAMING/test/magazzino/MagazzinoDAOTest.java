package magazzino;

import static org.junit.Assert.*;

import java.sql.SQLException;
import java.util.ArrayList;

import javax.sql.DataSource;

import org.dbunit.DataSourceBasedDBTestCase;
import org.dbunit.dataset.IDataSet;
import org.dbunit.dataset.xml.FlatXmlDataSetBuilder;
import org.dbunit.operation.DatabaseOperation;
import org.h2.jdbcx.JdbcDataSource;
import org.junit.After;
import org.junit.Before;
import org.junit.Test;

/**
 * Classe per testare OrdineDAO
 * @author rocco
 *
 */

public class MagazzinoDAOTest extends DataSourceBasedDBTestCase{

	private DataSource ds;
	private MagazzinoDAO mdao;
	
	@Before
	public void setUp() throws Exception {
		super.setUp();
        ds = this.getDataSource();
        mdao= new MagazzinoDAO(ds);
	}

	@After
	public void tearDown() throws Exception {
		super.tearDown();
	}
	
	@Override
	protected DataSource getDataSource() {
		JdbcDataSource dataSource = new JdbcDataSource();
        dataSource.setURL("jdbc:h2:mem:test;DB_CLOSE_DELAY=-1;init=runscript from 'classpath:resources/db/init/magazzino.sql'");
        dataSource.setUser("root");
        dataSource.setPassword("veloce123");
        return dataSource;
	}

	@Override
	protected IDataSet getDataSet() throws Exception {
		return new FlatXmlDataSetBuilder().build(this.getClass().getClassLoader().getResourceAsStream("resources/db/init/magazzino.xml"));
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
	public void testRicercaPerChiaveIdPresente() throws SQLException {
		
		MagazzinoBean magazzino= mdao.ricercaPerChiave("Italia,Nocera Superiore");
		
		MagazzinoBean test= new MagazzinoBean();
		test.setIndirizzo("Italia,Nocera Superiore");
		test.setCapienza(1000);
		
		assertEquals(magazzino.getIndirizzo(),test.getIndirizzo());
	}

	@Test
	public void testRicercaPerChiaveIdNonPresente() throws SQLException {
		
		MagazzinoBean magazzino= mdao.ricercaPerChiave("abc");
		assertNull(magazzino);
	}
	
	@Test
	public void testRicercaPerChiaveEmpty() throws SQLException {
		
		MagazzinoBean magazzino=null;
		
		try {
			magazzino= mdao.ricercaPerChiave("");
		}
		catch(NullPointerException e) {
			assertNull(magazzino);
		}
	}
	
	@Test
	public void testRicercaPerChiaveNull() throws SQLException {
		
		MagazzinoBean magazzino=null;
		
		try {
			magazzino= mdao.ricercaPerChiave(null);
		}
		catch(NullPointerException e) {
			assertNull(magazzino);
		}
	}
	
	@Test
	public void testAllElementsASC() throws SQLException {
		
		ArrayList<MagazzinoBean> magazzini= mdao.allElements("indirizzo asc");
		
		ArrayList<MagazzinoBean> test= new ArrayList<MagazzinoBean>();
		
		MagazzinoBean m1= new MagazzinoBean();
		m1.setIndirizzo("Italia,Nocera Superiore");
		m1.setCapienza(1000);
		test.add(m1);
		
		MagazzinoBean m2= new MagazzinoBean();
		m2.setIndirizzo("Italia,Solofra");
		m2.setCapienza(1000);
		test.add(m2);
		
		for(int i=0;i<magazzini.size();i++) {
			assertEquals(magazzini.get(i).getIndirizzo(),test.get(i).getIndirizzo());
			assertEquals(magazzini.get(i).getCapienza(),test.get(i).getCapienza());
		}
	}
	
	@Test
	public void testAllElementsDESC() throws SQLException {
		
		ArrayList<MagazzinoBean> magazzini= mdao.allElements("indirizzo desc");
		
		ArrayList<MagazzinoBean> test= new ArrayList<MagazzinoBean>();
		
		MagazzinoBean m1= new MagazzinoBean();
		m1.setIndirizzo("Italia,Solofra");
		m1.setCapienza(1000);
		test.add(m1);
		
		MagazzinoBean m2= new MagazzinoBean();
		m2.setIndirizzo("Italia,Nocera Superiore");
		m2.setCapienza(1000);
		test.add(m2);
		
		for(int i=0;i<magazzini.size();i++) {
			assertEquals(magazzini.get(i).getIndirizzo(),test.get(i).getIndirizzo());
			assertEquals(magazzini.get(i).getCapienza(),test.get(i).getCapienza());
		}
	}

	@Test
	public void testAllElementsEmpty() throws SQLException {
		
		ArrayList<MagazzinoBean> magazzini=null;
		try {
			magazzini= mdao.allElements("");
		}
		catch(NullPointerException e) {
			assertNull(magazzini);
		}
	}
	
	@Test
	public void testAllElementsNull() throws SQLException {
		
		ArrayList<MagazzinoBean> magazzini=null;
		try {
			magazzini= mdao.allElements(null);
		}
		catch(NullPointerException e){
			assertNull(magazzini);
		}
		
	}
	
	@Test
	public void testAllElementsNonValido() {
		ArrayList<MagazzinoBean> magazzini= null;
		try {
			magazzini=mdao.allElements("abc asc");
		}
		catch(SQLException e) {
			assertNull(magazzini);
		}
	}
	
}
