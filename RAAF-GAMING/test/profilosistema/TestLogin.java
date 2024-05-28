package profilosistema;
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
public class TestLogin {
  private WebDriver driver;
  private Map<String, Object> vars;
  JavascriptExecutor js;
  @Before
  public void setUp() {
	System.setProperty("webdriver.chrome.driver", "test/profilosistema/chromedriver.exe");
    driver = new ChromeDriver();
    js = (JavascriptExecutor) driver;
    vars = new HashMap<String, Object>();
  }
  @After
  public void tearDown() {
    driver.quit();
  }
  @Test
  public void testLoginFallitoEntrambi() throws InterruptedException {
    // Test name: TestLoginFallitoEntrambi
    // Step # | name | target | value | comment
    // 1 | open | http://localhost:8080/RAAF-GAMING/servletloginfirst |  | 
    driver.get("http://localhost:8080/RAAF-GAMING/servletloginfirst");
    Thread.sleep(2000);
    // 2 | click | name=email |  | 
    driver.findElement(By.name("email")).click();
    Thread.sleep(2000);
    // 3 | type | name=email | abc@gmail.com | 
    driver.findElement(By.name("email")).sendKeys("abc@gmail.com");
    Thread.sleep(2000);
    // 4 | click | name=password |  | 
    driver.findElement(By.name("password")).click();
    Thread.sleep(2000);
    // 5 | type | name=password | abcdesfg123 | 
    driver.findElement(By.name("password")).sendKeys("abcdesfg123");
    Thread.sleep(2000);
    // 6 | click | css=.invio |  | 
    driver.findElement(By.cssSelector(".invio")).click();
    Thread.sleep(2000);
    // 7 | assertText | name=messaggioerrore | Email/Password errata! | 
    assertThat(driver.findElement(By.name("messaggioerrore")).getText(), is("Email/Password errata!"));
    Thread.sleep(2000);
  }
  @Test
  public void testLoginFallitoPassword() throws InterruptedException {
    // Test name: TestLoginFallitoPassword
    // Step # | name | target | value | comment
    // 1 | open | http://localhost:8080/RAAF-GAMING/servletloginfirst |  | 
    driver.get("http://localhost:8080/RAAF-GAMING/servletloginfirst");
    Thread.sleep(2000);
    // 2 | click | name=email |  | 
    driver.findElement(By.name("email")).click();
    Thread.sleep(2000);
    // 3 | type | name=email | f.peluso25@gmail.com | 
    driver.findElement(By.name("email")).sendKeys("f.peluso25@gmail.com");
    Thread.sleep(2000);
    // 4 | click | name=password |  | 
    driver.findElement(By.name("password")).click();
    Thread.sleep(2000);
    // 5 | type | name=password | veloce1234 | 
    driver.findElement(By.name("password")).sendKeys("veloce1234");
    Thread.sleep(2000);
    // 6 | click | css=.invio |  | 
    driver.findElement(By.cssSelector(".invio")).click();
    Thread.sleep(2000);
    // 7 | assertText | name=messaggioerrore | Email/Password errata! | 
    assertThat(driver.findElement(By.name("messaggioerrore")).getText(), is("Email/Password errata!"));
    Thread.sleep(2000);
  }
  
  @Test
  public void testLoginEseguito() throws InterruptedException {
    // Test name: TestLoginEseguito
    // Step # | name | target | value
    // 1 | open | http://localhost:8080/RAAF-GAMING/servletloginfirst | 
    driver.get("http://localhost:8080/RAAF-GAMING/servletloginfirst");
    // 2 | click | name=email | 
    Thread.sleep(2000);
    driver.findElement(By.name("email")).click();
    Thread.sleep(2000);
    // 3 | type | name=email | f.peluso25@gmail.com
    driver.findElement(By.name("email")).sendKeys("f.peluso25@gmail.com");
    Thread.sleep(2000);
    // 4 | click | name=password | 
    driver.findElement(By.name("password")).click();
    Thread.sleep(2000);
    // 5 | type | name=password | veloce123
    driver.findElement(By.name("password")).sendKeys("veloce123");
    Thread.sleep(2000);
    // 6 | click | css=.invio | 
    driver.findElement(By.cssSelector(".invio")).click();
    Thread.sleep(2000);
    // 7 | assertTitle | RAAF-GAMING | 
    assertThat(driver.getTitle(), is("RAAF-GAMING"));
    Thread.sleep(2000);
  }
}
