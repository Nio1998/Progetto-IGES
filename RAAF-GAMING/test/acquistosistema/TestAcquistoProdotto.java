package acquistosistema;
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
public class TestAcquistoProdotto {
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
  public void testAcquistoDeiProdottiNessunProdotto() {
    // Test name: TestAcquistoDeiProdotti_NessunProdotto
    // Step # | name | target | value
    // 1 | open | http://localhost:8080/RAAF-GAMING/servletcarrello | 
    driver.get("http://localhost:8080/RAAF-GAMING/servletcarrello");
    // 2 | click | css=.btn-outline-warning | 
    driver.findElement(By.cssSelector(".btn-outline-warning")).click();
    // 3 | assertAlert | Non hai prodotti nel carrello | 
    assertThat(driver.switchTo().alert().getText(), is("Non hai prodotti nel carrello"));
  }
  
  
 
 
  @Test
  public void testAcquistoDeiProdottiNonAutenticato() throws InterruptedException {
    // Test name: TestAcquistoDeiProdotti_NonAutenticato
    // Step # | name | target | value
    // 1 | open | http://localhost:8080/RAAF-GAMING/ | 
    driver.get("http://localhost:8080/RAAF-GAMING/");
    // 2 | click | id=dropdownMenuButton | 
    driver.findElement(By.id("dropdownMenuButton")).click();
    Thread.sleep(3000);
    // 3 | click | linkText=Login | 
    driver.findElement(By.linkText("Login")).click();
    // 4 | click | name=email | 
    driver.findElement(By.name("email")).click();
    // 5 | type | name=email | f.peluso25@gmail.com
    driver.findElement(By.name("email")).sendKeys("f.peluso25@gmail.com");
    // 6 | click | name=password | 
    driver.findElement(By.name("password")).click();
    // 7 | type | name=password | veloce123
    driver.findElement(By.name("password")).sendKeys("veloce123");
    // 8 | click | css=.invio | 
    Thread.sleep(3000);
    driver.findElement(By.cssSelector(".invio")).click();
    // 9 | click | css=.fa-user-astronaut | 
    driver.findElement(By.cssSelector(".fa-user-astronaut")).click();
    // 10 | click | linkText=LogOut | 
    Thread.sleep(3000);
    driver.findElement(By.linkText("LogOut")).click();
    Thread.sleep(3000);
    // 11 | click | css=.row:nth-child(1) li:nth-child(1) .card__title | 
    driver.findElement(By.cssSelector(".row:nth-child(1) li:nth-child(1) .card__title")).click();
    // 12 | click | css=.pl-2 | 
    Thread.sleep(3000);
    driver.findElement(By.cssSelector(".pl-2")).click();
    // 13 | assertAlert | Aggiunta nel carrello fatta con successo! |
    Thread.sleep(3000);
    Alert alert=driver.switchTo().alert();
    String text= alert.getText();
    if(text.equals("Aggiunta nel carrello fatta con successo!"))
    {
    	alert.accept();
    }
    Thread.sleep(3000);
    // 14 | click | id=sostituisciCarrello | 
    driver.findElement(By.id("sostituisciCarrello")).click();
    // 15 | click | name=indirizzodiconsegna | 
    Thread.sleep(3000);
    driver.findElement(By.name("indirizzodiconsegna")).click();
    // 16 | type | name=indirizzodiconsegna | viale croce
    driver.findElement(By.name("indirizzodiconsegna")).sendKeys("viale croce");
    // 17 | click | css=.btn-outline-warning | 
    Thread.sleep(3000);
    driver.findElement(By.cssSelector(".btn-outline-warning")).click();
    // 18 | assertTitle | LOGIN | 
    assertThat(driver.getTitle(), is("LOGIN"));
    
  }
 

  @Test
  public void testAcquistoDeiProdottiIndirizzoDiConsegnaNonValido() throws InterruptedException {
    // Test name: TestAcquistoDeiProdotti_IndirizzoDiConsegnaNonValido
    // Step # | name | target | value
    // 1 | open | http://localhost:8080/RAAF-GAMING/servletprodotto?id=1 | 
    driver.get("http://localhost:8080/RAAF-GAMING/servletprodotto?id=1");
    Thread.sleep(5000);
    // 2 | click | css=.pl-2 | 
    driver.findElement(By.cssSelector(".pl-2")).click();
    Thread.sleep(5000);
    // 3 | assertAlert | Aggiunta nel carrello fatta con successo! | 
    Alert alert = driver.switchTo().alert();
    String t= alert.getText();
    if(t.equals("Aggiunta nel carrello fatta con successo!"))
    {
    	alert.accept();
    	 Thread.sleep(5000);
    	 // 4 | click | id=sostituisciCarrello | 
        driver.findElement(By.id("sostituisciCarrello")).click();
        Thread.sleep(5000);
        // 5 | click | name=indirizzodiconsegna | 
        driver.findElement(By.name("indirizzodiconsegna")).click();
        // 6 | type | name=indirizzodiconsegna | fgfffggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg
        driver.findElement(By.name("indirizzodiconsegna")).sendKeys("fgfffggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg");
        // 7 | click | css=.btn-outline-warning | 
        Thread.sleep(5000);
        driver.findElement(By.cssSelector(".btn-outline-warning")).click();
        // 8 | assertAlert | INDIRIZZO DI CONSEGNA NON VALIDO! | 
        assertThat(driver.switchTo().alert().getText(), is("INDIRIZZO DI CONSEGNA NON VALIDO!"));
        Thread.sleep(5000);
    }
  }
 
  @Test
  public void testAcquistoDeiProdottiRiuscito() throws InterruptedException {
    // Test name: TestAcquistoDeiProdotti_Riuscito
    // Step # | name | target | value
    // 1 | open | http://localhost:8080/RAAF-GAMING/servletloginfirst | 
    driver.get("http://localhost:8080/RAAF-GAMING/servletloginfirst");
    // 2 | click | name=email | 
    Thread.sleep(5000);
    driver.findElement(By.name("email")).click();
    // 3 | type | name=email | f.peluso25@gmail.com
    driver.findElement(By.name("email")).sendKeys("f.peluso25@gmail.com");
    // 4 | click | name=password | 
    driver.findElement(By.name("password")).click();
    // 5 | type | name=password | veloce123
    driver.findElement(By.name("password")).sendKeys("veloce123");
    // 6 | click | css=.invio | 
    Thread.sleep(5000);
    driver.findElement(By.cssSelector(".invio")).click();
    Thread.sleep(5000);
    // 7 | click | css=.row:nth-child(1) li:nth-child(1) .card__title | 
    driver.findElement(By.cssSelector(".row:nth-child(1) li:nth-child(1) .card__title")).click();
    // 8 | click | css=.pl-2 | 
    Thread.sleep(5000);
    driver.findElement(By.cssSelector(".pl-2")).click();
    Thread.sleep(5000);
    // 9 | assertAlert | Aggiunta nel carrello fatta con successo! | 
    Alert alert=driver.switchTo().alert();
    String text= alert.getText();
    if(text.equals("Aggiunta nel carrello fatta con successo!"))
    {
    	alert.accept();
    }
    Thread.sleep(5000);
    // 10 | click | id=sostituisciCarrello | 
    driver.findElement(By.id("sostituisciCarrello")).click();
    // 11 | click | name=indirizzodiconsegna | 
    Thread.sleep(5000);
    driver.findElement(By.name("indirizzodiconsegna")).click();
    // 12 | type | name=indirizzodiconsegna | viale traiano
    driver.findElement(By.name("indirizzodiconsegna")).sendKeys("viale traiano");
    // 13 | click | css=.btn-outline-warning | 
    driver.findElement(By.cssSelector(".btn-outline-warning")).click();
  }

 
  @Test
  public void testAcquistoDeiProdottiNonDisponibile() throws InterruptedException {
    // Test name: TestAcquistoDeiProdotti_NonDisponibile
    // Step # | name | target | value
    // 1 | open | http://localhost:8080/RAAF-GAMING/servletindex | 
    driver.get("http://localhost:8080/RAAF-GAMING/servletindex");
    // 2 | click | id=dropdownMenuButton | 
    Thread.sleep(5000);
    driver.findElement(By.id("dropdownMenuButton")).click();
    // 3 | click | linkText=Login | 
    driver.findElement(By.linkText("Login")).click();
    // 4 | click | name=email | 
    Thread.sleep(5000);
    driver.findElement(By.name("email")).click();
    // 5 | type | name=email | f.peluso25@gmail.com
    driver.findElement(By.name("email")).sendKeys("f.peluso25@gmail.com");
    // 6 | click | name=password | 
    driver.findElement(By.name("password")).click();
    // 7 | type | name=password | veloce123
    driver.findElement(By.name("password")).sendKeys("veloce123");
    // 8 | click | css=.invio | 
    Thread.sleep(5000);
    driver.findElement(By.cssSelector(".invio")).click();
    // 9 | click | css=.row:nth-child(1) li:nth-child(3) .card__header | 
    driver.findElement(By.cssSelector(".row:nth-child(1) li:nth-child(3) .card__header")).click();
    // 10 | click | css=.pl-2 | 
    Thread.sleep(5000);
    driver.findElement(By.cssSelector(".pl-2")).click();
    // 11 | assertAlert | Aggiunta nel carrello fatta con successo! |
    Thread.sleep(5000);
    Alert alert=driver.switchTo().alert();
    String halo=alert.getText();
    if(halo.equals("Aggiunta nel carrello fatta con successo!"))
    {
    	alert.accept();
    	// 12 | click | id=sostituisciCarrello | 
    	Thread.sleep(5000);
        driver.findElement(By.id("sostituisciCarrello")).click();
        // 13 | click | name=indirizzodiconsegna | 
        Thread.sleep(9000);
        driver.findElement(By.name("indirizzodiconsegna")).click();
        // 14 | type | name=indirizzodiconsegna | viale croce
        Thread.sleep(5000);
        driver.findElement(By.name("indirizzodiconsegna")).sendKeys("viale croce");
        // 15 | click | css=.btn-outline-warning | 
        Thread.sleep(5000);
        driver.findElement(By.cssSelector(".btn-outline-warning")).click();
        // 16 | assertText | name=prodottoNonDisponibile | Qualche o tutti i prodotti nel carrello non sono piu' disponibili
        /*Alert alert2=driver.switchTo().alert();
        String Aggiunta=alert2.getText();
        if(Aggiunta.equals("Qualche o tutti i prodotti nel carrello non sono piu' disponibili"))*/
        assertThat(driver.findElement(By.name("prodottoNonDisponibile")).getText(), is("Qualche o tutti i prodotti nel carrello non sono piu' disponibili"));
    }
   
  }
  
}
