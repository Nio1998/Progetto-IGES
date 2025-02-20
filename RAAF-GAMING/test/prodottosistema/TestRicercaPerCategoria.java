package prodottosistema;

//Generated by Selenium IDE
import org.junit.Test;
import org.junit.Before;
import org.junit.After;
import static org.junit.Assert.*;
import static org.hamcrest.CoreMatchers.is;
import static org.hamcrest.core.IsNot.not;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.firefox.FirefoxDriver;
import org.openqa.selenium.chrome.ChromeDriver;
import org.openqa.selenium.remote.RemoteWebDriver;
import org.openqa.selenium.remote.DesiredCapabilities;
import org.openqa.selenium.Dimension;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.interactions.Actions;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;
import org.openqa.selenium.JavascriptExecutor;
import org.openqa.selenium.Alert;
import org.openqa.selenium.Keys;
import java.util.*;
import java.net.MalformedURLException;
import java.net.URL;

public class TestRicercaPerCategoria {
	private WebDriver driver;
	private Map<String, Object> vars;
	JavascriptExecutor js;
	
	@Before
	public void setUp() {
		System.setProperty("webdriver.chrome.driver", "test/prodottosistema/chromedriver");
		driver = new ChromeDriver();
		js = (JavascriptExecutor) driver;
		vars = new HashMap<String, Object>();
	}
	
	@After
	public void tearDown() {
		driver.quit();
	}
	
	@Test
	public void testRicercaPerCategoriaOK() {
	    // Test name: testRicercaPerCategoriaOK
	    // Step # | name | target | value | comment
	    // 1 | open | http://localhost:8080/RAAF-GAMING/ |  | 
	    driver.get("http://localhost:8080/RAAF-GAMING/");
	    // 2 | click | css=.navbar-toggler |  | 
	    driver.findElement(By.cssSelector(".navbar-toggler")).click();
	    // 3 | click | css=.nav-item:nth-child(5) .fa |  | 
	    driver.findElement(By.cssSelector(".nav-item:nth-child(5) .fa")).click();
	    // 4 | assertText | name=nomeProdotto | fifa 21 | 
	    assertEquals("fifa 21",driver.findElement(By.name("nomeProdotto0")).getText());
	}
	
	@Test
	public void testRicercaPerCategoriaNO() {
	    // Test name: testRicercaPerCategoriaNO
	    // Step # | name | target | value | comment
	    // 1 | open | http://localhost:8080/RAAF-GAMING/ |  | 
	    driver.get("http://localhost:8080/RAAF-GAMING/");
	    // 2 | setWindowSize | 912x600 |  | 
	    driver.manage().window().setSize(new Dimension(912, 600));
	    // 3 | click | css=.navbar-toggler |  | 
	    driver.findElement(By.cssSelector(".navbar-toggler")).click();
	    
	    // 5 | open | http://localhost:8080/RAAF-GAMING/servletcategorie?per=abc |  | 
	    driver.get("http://localhost:8080/RAAF-GAMING/servletcategorie?per=abc");
	    // 6 | assertTitle | RAAF-GAMING |  | 
	    assertEquals(driver.getTitle(), "RAAF-GAMING");
	}
}
