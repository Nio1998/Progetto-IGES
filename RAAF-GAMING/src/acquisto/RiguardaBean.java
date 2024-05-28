package acquisto;

import java.io.Serializable;

public class RiguardaBean implements Serializable{

	private static final long serialVersionUID = 1L;
	
	private int prodotto;
	private String ordine;
	private int quantita_acquistata;
	
	public RiguardaBean() {
		prodotto=0;
		ordine="";
		quantita_acquistata=0;
	}
	
	public int getProdotto() {
		return prodotto;
	}
	
	public void setProdotto(int prodotto) {
		this.prodotto=prodotto;
	}
	
	public String getOrdine() {
		return ordine;
	}
	
	public void setOrdine(String ordine) {
		this.ordine=ordine;
	}
	
	public int getQuantita_acquistata() {
		return quantita_acquistata;
	}
	
	public void setQuantita_acquistata(int quantita_acquistata) {
		this.quantita_acquistata=quantita_acquistata;
	}
}
