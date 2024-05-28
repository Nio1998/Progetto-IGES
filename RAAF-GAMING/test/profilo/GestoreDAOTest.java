package profilo;

import static org.junit.Assert.*;

import java.sql.SQLException;

import javax.sql.DataSource;

import org.dbunit.DataSourceBasedDBTestCase;
import org.dbunit.dataset.IDataSet;
import org.dbunit.dataset.xml.FlatXmlDataSetBuilder;
import org.h2.jdbcx.JdbcDataSource;
import org.junit.After;
import org.junit.Before;
import org.junit.Test;

public class GestoreDAOTest extends DataSourceBasedDBTestCase {
	private DataSource ds;
	private GestoreDAO gd;
	@Before
	public void setUp() throws Exception {
		super.setUp();
        ds = this.getDataSource();
        gd = new GestoreDAO(ds);
	}

	@After
	public void tearDown() throws Exception {
		super.tearDown();
	}


	@Override
	protected DataSource getDataSource() {
		JdbcDataSource dataSource = new JdbcDataSource();
        dataSource.setURL("jdbc:h2:mem:test;DB_CLOSE_DELAY=-1;init=runscript from 'classpath:resources/db/init/gestore.sql'");
        dataSource.setUser("root");
        dataSource.setPassword("veloce123");
        return dataSource;
	}

	@Override
	protected IDataSet getDataSet() throws Exception {
		return new FlatXmlDataSetBuilder().build(this.getClass().getClassLoader().getResourceAsStream("resources/db/init/gestore.xml"));
	}

	@Test
	public void testRicercaPerChiavePresente() throws SQLException {
	GestoreBean a = gd.ricercaPerChiave("gestore@hotmail.it");
	GestoreBean b= new GestoreBean();
	
	b.setEmail("gestore@hotmail.it");
	b.setRuolo("ordine");
	b.setPassword("123456789");
	
	assertEquals(a.getEmail(), b.getEmail());
	assertEquals(a.getRuolo(), b.getRuolo());
	assertEquals(a.getPassword(), b.getPassword());
	}
	@Test
	public void testRicercaPerChiaveNonPresente() throws SQLException {
		GestoreBean a = gd.ricercaPerChiave("gestoreabc@hotmail.it");
		assertNull(a);
	}
	
	
	@Test
	public void testRicercaPerChiaveVuoto() throws SQLException {
		GestoreBean a = null;
		try {
		a= gd.ricercaPerChiave("");
		}catch( NullPointerException e){
		assertNull(a);
		}
	}
	@Test
	public void testRicercaPerChiaveNull() throws SQLException {
		GestoreBean a = null;
		try {
		a= gd.ricercaPerChiave(null);
		}catch( NullPointerException e){
		assertNull(a);
		}
	}
}
