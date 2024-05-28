package magazzino;

import java.io.Serializable;

public class MagazzinoBean implements Serializable{

	private static final long serialVersionUID = 1L;
	
	private String indirizzo;
	private int capienza;
	
	public MagazzinoBean()
	{
		indirizzo = "";
		capienza = 0;
	}

	public String getIndirizzo() {
		return indirizzo;
	}

	public void setIndirizzo(String indirizzo) {
		this.indirizzo = indirizzo;
	}

	public int getCapienza() {
		return capienza;
	}

	public void setCapienza(int capienza) {
		this.capienza = capienza;
	}
	
	

}