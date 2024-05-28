package profilo;

import java.io.Serializable;

public class CartaFedeltaBean implements Serializable {

	private static final long serialVersionUID = 1L;

	private String codice;
	private int punti;
	
	public CartaFedeltaBean() {
		codice="";
		punti=0;
	}

	public String getCodice() {
		return codice;
	}

	public void setCodice(String codice) {
		this.codice = codice;
	}

	public int getPunti() {
		return punti;
	}

	public void setPunti(int punti) {
		this.punti = punti;
	}
	
	
	
}
