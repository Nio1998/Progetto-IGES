package prodottosistema;

// Generated by Selenium IDE
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
public class RecensioneTest {
  private WebDriver driver;
  private Map<String, Object> vars;
  JavascriptExecutor js;
  @Before
  public void setUp() {
	System.setProperty("webdriver.chrome.driver", "test/acquistosistema/chromedriver.exe");
    driver = new ChromeDriver();
    js = (JavascriptExecutor) driver;
    vars = new HashMap<String, Object>();
  }
  @After
  public void tearDown() {
    driver.quit();
  }
  @Test
  public void recensioneEffettuataConSuccesso() throws InterruptedException {
    // Test name: RecensioneEffettuataConSuccesso
    // Step # | name | target | value
    // 1 | open | http://localhost:8080/RAAF-GAMING/servletloginfirst | 
	  Thread.sleep(1800);
    driver.get("http://localhost:8080/RAAF-GAMING/servletloginfirst");
    // 2 | setWindowSize | 945x1020 | 
    Thread.sleep(1800);
    driver.manage().window().setSize(new Dimension(945, 1020));
    // 3 | click | name=email | 
    Thread.sleep(1800);
    driver.findElement(By.name("email")).click();
    // 4 | type | name=email | f.peluso25@gmail.com
    driver.findElement(By.name("email")).sendKeys("f.peluso25@gmail.com");
    // 5 | click | name=password | 
    Thread.sleep(1800);
    driver.findElement(By.name("password")).click();
    // 6 | type | name=password | veloce123
    Thread.sleep(1800);
    driver.findElement(By.name("password")).sendKeys("veloce123");
    // 7 | click | css=.invio | 
    Thread.sleep(1800);
    driver.findElement(By.cssSelector(".invio")).click();
    // 8 | click | css=.row:nth-child(2) li:nth-child(1) .card__header | 
    Thread.sleep(1800);
    driver.findElement(By.cssSelector(".row:nth-child(2) li:nth-child(1) .card__header")).click();
    // 9 | click | id=commento | 
    Thread.sleep(1800);
    driver.findElement(By.id("commento")).click();
    // 10 | type | id=commento | belliii
    Thread.sleep(1800);
    driver.findElement(By.id("commento")).sendKeys("belliii");
    // 11 | click | id=stella9 | 
    Thread.sleep(1800);
    driver.findElement(By.id("stella9")).click();
    // 12 | click | css=.btn-dark |
    Thread.sleep(1800);
    driver.findElement(By.cssSelector(".btn-dark")).click();
    // 13 | assertAlert | Recensione effettuata con voto 9 | 
    Thread.sleep(1800);
    Alert alert=driver.switchTo().alert();
    String text=alert.getText();
    assertEquals("Recensione effettuata con voto 9",text);
  }
  
  @Test
  public void recensioneGiaEffettuata() throws InterruptedException {
    // Test name: RecensioneGiaEffettuata
    // Step # | name | target | value
    // 1 | open | http://localhost:8080/RAAF-GAMING/servletloginfirst | 
	  Thread.sleep(1800);
    driver.get("http://localhost:8080/RAAF-GAMING/servletloginfirst");
    // 2 | setWindowSize | 945x1020 | 
    Thread.sleep(1800);
    driver.manage().window().setSize(new Dimension(945, 1020));
    // 3 | click | name=email | 
    Thread.sleep(1800);
    driver.findElement(By.name("email")).click();
    // 4 | type | name=email | f.peluso25@gmail.com
    Thread.sleep(1800);
    driver.findElement(By.name("email")).sendKeys("f.peluso25@gmail.com");
    // 5 | click | name=password | 
    Thread.sleep(1800);
    driver.findElement(By.name("password")).click();
    // 6 | type | name=password | veloce123
    Thread.sleep(1800);
    driver.findElement(By.name("password")).sendKeys("veloce123");
    // 7 | click | css=.invio | 
    Thread.sleep(1800);
    driver.findElement(By.cssSelector(".invio")).click();
    // 8 | click | css=.row:nth-child(2) li:nth-child(1) .card__image |
    Thread.sleep(1800);
    driver.findElement(By.cssSelector(".row:nth-child(2) li:nth-child(1) .card__image")).click();
    // 9 | click | id=commento | 
    Thread.sleep(1800);
    driver.findElement(By.id("commento")).click();
    // 10 | type | id=commento | ciao
    Thread.sleep(1800);
    driver.findElement(By.id("commento")).sendKeys("ciao");
    // 11 | click | id=stella9 | 
    Thread.sleep(1800);
    driver.findElement(By.id("stella9")).click();
    // 12 | click | css=.btn-dark | 
    Thread.sleep(1800);
    driver.findElement(By.cssSelector(".btn-dark")).click();
    Thread.sleep(1800);
    // 13 | assertAlert | Hai gia effettuato una recensione per questo prodotto | 
    Thread.sleep(1800);
    Alert alert=driver.switchTo().alert();
    String text=alert.getText();
    assertEquals("Hai gia effettuato una recensione per questo prodotto",text);
  }
  
  @Test
  public void recensioneSenzaCommento() throws InterruptedException {
    // Test name: RecensioneSenzaCommento
    // Step # | name | target | value
    // 1 | open | http://localhost:8080/RAAF-GAMING/servletloginfirst | 
	  Thread.sleep(1800);
    driver.get("http://localhost:8080/RAAF-GAMING/servletloginfirst");
    // 2 | setWindowSize | 945x1020 | 
    Thread.sleep(1800);
    driver.manage().window().setSize(new Dimension(945, 1020));
    // 3 | click | name=email | 
    Thread.sleep(1800);
    driver.findElement(By.name("email")).click();
    // 4 | type | name=email | f.peluso25@gmail.com
    Thread.sleep(1800);
    driver.findElement(By.name("email")).sendKeys("f.peluso25@gmail.com");
    // 5 | click | name=password |
    Thread.sleep(1800);
    driver.findElement(By.name("password")).click();
    // 6 | type | name=password | veloce123
    Thread.sleep(1800);
    driver.findElement(By.name("password")).sendKeys("veloce123");
    // 7 | click | css=.invio | 
    Thread.sleep(1800);
    driver.findElement(By.cssSelector(".invio")).click();
    // 8 | click | css=.row:nth-child(2) li:nth-child(2) .card__title | 
    Thread.sleep(1800);
    driver.findElement(By.cssSelector(".row:nth-child(2) li:nth-child(2) .card__title")).click();
    // 9 | click | id=stella7 | 
    Thread.sleep(1800);
    driver.findElement(By.id("stella7")).click();
    // 10 | click | css=.btn-dark | 
    Thread.sleep(1800);
    driver.findElement(By.cssSelector(".btn-dark")).click();
    // 11 | assertAlert | commento non inserito | 
    Thread.sleep(1800);
    Alert alert=driver.switchTo().alert();
    String text=alert.getText();
    assertEquals("commento non inserito",text);
  }
  
  @Test
  public void recensioneSenzaLoggare() throws InterruptedException {
    // Test name: RecensioneSenzaLoggare
    // Step # | name | target | value
    // 1 | open | http://localhost:8080/RAAF-GAMING/servletprodotto?id=9 | 
	 Thread.sleep(1800);
    driver.get("http://localhost:8080/RAAF-GAMING/servletprodotto?id=9");
    // 2 | setWindowSize | 945x1020 | 
    Thread.sleep(1800);
    driver.manage().window().setSize(new Dimension(945, 1020));
    // 3 | click | id=commento |
    Thread.sleep(1800);
    driver.findElement(By.id("commento")).click();
    // 4 | type | id=commento | sdsdsf
    Thread.sleep(1800);
    driver.findElement(By.id("commento")).sendKeys("sdsdsf");
    // 5 | click | id=stella7 | 
    Thread.sleep(1800);
    driver.findElement(By.id("stella7")).click();
    // 6 | click | css=.btn-dark | 
    Thread.sleep(1800);
    driver.findElement(By.cssSelector(".btn-dark")).click();
    // 7 | assertAlert | Effettua l'accesso per recensire! | 
    Thread.sleep(1800);
    Alert alert=driver.switchTo().alert();
    String text=alert.getText();
    assertEquals("Effettua l'accesso per recensire!",text);
  }
  
  @Test
  public void recensioneSenzaVotoECommento() throws InterruptedException {
    // Test name: RecensioneSenzaVoto
    // Step # | name | target | value
    // 1 | open | http://localhost:8080/RAAF-GAMING/servletloginfirst | 
	  Thread.sleep(1800);
    driver.get("http://localhost:8080/RAAF-GAMING/servletloginfirst");
    // 2 | setWindowSize | 945x1020 | 
    Thread.sleep(1800);
    driver.manage().window().setSize(new Dimension(945, 1020));
    // 3 | click | name=email | 
    Thread.sleep(1800);
    driver.findElement(By.name("email")).click();
    // 4 | type | name=email | f.peluso25@gmail.com
    Thread.sleep(1800);
    driver.findElement(By.name("email")).sendKeys("f.peluso25@gmail.com");
    // 5 | click | name=password | 
    Thread.sleep(1800);
    driver.findElement(By.name("password")).click();
    // 6 | type | name=password | veloce123
    Thread.sleep(1800);
    driver.findElement(By.name("password")).sendKeys("veloce123");
    // 7 | click | css=.invio | 
    Thread.sleep(1800);
    driver.findElement(By.cssSelector(".invio")).click();
    // 8 | click | css=.row:nth-child(2) li:nth-child(2) .card__header |
    Thread.sleep(1800);
    driver.findElement(By.cssSelector(".row:nth-child(2) li:nth-child(2) .card__header")).click();
    // 9 | click | id=commento | 
    Thread.sleep(1800);
    driver.findElement(By.cssSelector(".btn-dark")).click();
    // 12 | assertAlert | non hai inserito il voto | 
    Thread.sleep(1800);
    Alert alert=driver.switchTo().alert();
    String text=alert.getText();
    assertEquals("non hai inserito il voto",text);
  }
  
  @Test
  public void recensioneSenzaVoto() throws InterruptedException {
    // Test name: RecensioneSenzaVoto
    // Step # | name | target | value
    // 1 | open | http://localhost:8080/RAAF-GAMING/servletloginfirst | 
	  Thread.sleep(1800);
    driver.get("http://localhost:8080/RAAF-GAMING/servletloginfirst");
    // 2 | setWindowSize | 945x1020 | 
    Thread.sleep(1800);
    driver.manage().window().setSize(new Dimension(945, 1020));
    // 3 | click | name=email | 
    Thread.sleep(1800);
    driver.findElement(By.name("email")).click();
    // 4 | type | name=email | f.peluso25@gmail.com
    Thread.sleep(1800);
    driver.findElement(By.name("email")).sendKeys("f.peluso25@gmail.com");
    // 5 | click | name=password | 
    Thread.sleep(1800);
    driver.findElement(By.name("password")).click();
    // 6 | type | name=password | veloce123
    Thread.sleep(1800);
    driver.findElement(By.name("password")).sendKeys("veloce123");
    // 7 | click | css=.invio | 
    Thread.sleep(1800);
    driver.findElement(By.cssSelector(".invio")).click();
    // 8 | click | css=.row:nth-child(2) li:nth-child(2) .card__header |
    Thread.sleep(1800);
    driver.findElement(By.cssSelector(".row:nth-child(2) li:nth-child(2) .card__header")).click();
    // 9 | click | id=commento | 
    Thread.sleep(1800);
    driver.findElement(By.id("commento")).click();
    // 10 | type | id=commento | bello
    Thread.sleep(1800);
    driver.findElement(By.id("commento")).sendKeys("bello");
    // 11 | click | css=.btn-dark | 
    Thread.sleep(1800);
    driver.findElement(By.cssSelector(".btn-dark")).click();
    // 12 | assertAlert | non hai inserito il voto | 
    Thread.sleep(1800);
    Alert alert=driver.switchTo().alert();
    String text=alert.getText();
    assertEquals("non hai inserito il voto",text);
  }
  
  
}
