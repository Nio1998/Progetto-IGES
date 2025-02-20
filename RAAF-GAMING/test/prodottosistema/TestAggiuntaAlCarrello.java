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
import java.util.concurrent.TimeUnit;
import java.net.MalformedURLException;
import java.net.URL;
public class TestAggiuntaAlCarrello {
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
  public void testAggiuntaAlCarrelloProdottoNonDisponibile() throws InterruptedException {
    // Test name: TestAggiuntaAlCarrello_ProdottoNonDisponibile
    // Step # | name | target | value
    // 1 | open | http://localhost:8080/RAAF-GAMING/servletprodotto?id=2 | 
    driver.get("http://localhost:8080/RAAF-GAMING/servletprodotto?id=2");
    // 2 | click | css=.pl-2 | 
    Thread.sleep(5000);
    driver.findElement(By.cssSelector(".pl-2")).click();
    // 3 | assertAlert | Prodotto non disponibile in magazzino | 
    assertThat(driver.switchTo().alert().getText(), is("Prodotto non disponibile in magazzino"));
    Thread.sleep(5000);
  }
  @Test
  public void testAggiuntaAlCarrelloProdottoGiaAggiunto() throws InterruptedException {
    // Test name: TestAggiuntaAlCarrello_ProdottoGiaAggiunto
    // Step # | name | target | value
    // 1 | open | http://localhost:8080/RAAF-GAMING/ | 
    driver.get("http://localhost:8080/RAAF-GAMING/");
    // 2 | click | css=.row:nth-child(1) li:nth-child(1) span:nth-child(1) | 
    Thread.sleep(5000);
    driver.findElement(By.cssSelector(".row:nth-child(1) li:nth-child(1) span:nth-child(1)")).click();
    // 3 | click | css=.pl-2 | 
    Thread.sleep(5000);
    driver.findElement(By.cssSelector(".pl-2")).click();
    // 4 | assertAlert | Aggiunta nel carrello fatta con successo! |
    Thread.sleep(5000);
    Alert alert=driver.switchTo().alert();
    String t=alert.getText();
    if(t.equals("Aggiunta nel carrello fatta con successo!"))
    {
    	alert.accept();
    }
    // 5 | click | css=.navbar-brand > img | 
    Thread.sleep(5000);
    driver.findElement(By.cssSelector(".navbar-brand > img")).click();
    // 6 | click | css=.row:nth-child(1) li:nth-child(1) .card__title | 
    Thread.sleep(5000);
    driver.findElement(By.cssSelector(".row:nth-child(1) li:nth-child(1) .card__title")).click();
    // 7 | click | css=.fa-shopping-cart | 
    Thread.sleep(5000);
    driver.findElement(By.cssSelector(".fa-shopping-cart")).click();
    // 8 | assertAlert | Hai gia questo prodotto nell carrello | 
    Thread.sleep(5000);
    assertThat(driver.switchTo().alert().getText(), is("Hai gia questo prodotto nell carrello"));
    Thread.sleep(5000);
  }
  @Test
  public void testAggiuntaAlCarrelloProdottoAggiunto() throws InterruptedException {
    // Test name: TestAggiuntaAlCarrello_ProdottoAggiunto
    // Step # | name | target | value
    // 1 | open | http://localhost:8080/RAAF-GAMING/servletprodotto?id=1 | 
    driver.get("http://localhost:8080/RAAF-GAMING/servletprodotto?id=1");
    Thread.sleep(5000);
    // 2 | click | css=.pl-2 | 
    driver.findElement(By.cssSelector(".pl-2")).click();
    // 3 | assertAlert | Aggiunta nel carrello fatta con successo! | 
    assertThat(driver.switchTo().alert().getText(), is("Aggiunta nel carrello fatta con successo!"));
    Thread.sleep(5000);
  }
}
