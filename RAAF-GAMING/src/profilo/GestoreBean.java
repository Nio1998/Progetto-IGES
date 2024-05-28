package profilo;

import java.io.Serializable;

public class GestoreBean implements Serializable{

	private static final long serialVersionUID = 1L;

	private String email;
	private String ruolo;
	private String password;
	
	
	
	public GestoreBean() {
		email="";
		ruolo="";
		password="";
	}
	
	public String getEmail() {
		return email;
	}
	
	public void setEmail(String email) {
		this.email = email;
	}
	
	public String getRuolo() {
		return ruolo;
	}
	
	public void setRuolo(String ruolo) {
		this.ruolo = ruolo;
	}
	
	public String getPassword() {
		return password;
	}
	
	public void setPassword(String password) {
		this.password = password;
	}
	
}
