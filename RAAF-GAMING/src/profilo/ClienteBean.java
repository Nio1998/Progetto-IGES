package profilo;

import java.io.Serializable;
import java.sql.Date;

/**
 * E' il bean che rappresenta la tabella del DB Cliente
 *
 */
public class ClienteBean implements Serializable{

	private static final long serialVersionUID = 1L;

	private String email;
	private String nome;
	private String cognome;
	private Date data_di_nascita;
	private String password;
	private String cartadicredito;
	private String carta_fedelta;
	
	public ClienteBean() {
		email="";
		nome="";
		cognome="";
		data_di_nascita=null;
		password="";
		carta_fedelta="";
		cartadicredito = "";
	}

	public String getEmail() {
		return email;
	}

	public void setEmail(String email) {
		this.email = email;
	}

	public String getNome() {
		return nome;
	}

	public void setNome(String nome) {
		this.nome = nome;
	}

	public String getCognome() {
		return cognome;
	}

	public void setCognome(String cognome) {
		this.cognome = cognome;
	}

	public Date getData_di_nascita() {
		return data_di_nascita;
	}

	public void setData_di_nascita(Date data_di_nascita) {
		this.data_di_nascita = data_di_nascita;
	}

	public String getPassword() {
		return password;
	}

	public void setPassword(String password) {
		this.password = password;
	}

	public String getCarta_fedelta() {
		return carta_fedelta;
	}

	public void setCarta_fedelta(String carta_fedelta) {
		this.carta_fedelta = carta_fedelta;
	}
	
	public String getCartadicredito() {
		return cartadicredito;
	}

	public void setCartadicredito(String cartadicredito) {
		this.cartadicredito = cartadicredito;
	}
	
}

