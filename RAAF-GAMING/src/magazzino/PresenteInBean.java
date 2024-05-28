package magazzino;

import java.io.Serializable;

public class PresenteInBean implements Serializable{

	private static final long serialVersionUID = 1L;
	
	private String magazzino;
	private int prodotto;
	private int quantita_disponibile;
	
	
	public PresenteInBean()
	{
		magazzino="";
		prodotto=0;
		quantita_disponibile=0;
	}


	public String getMagazzino() {
		return magazzino;
	}


	public void setMagazzino(String magazzino) {
		this.magazzino = magazzino;
	}


	public int getProdotto() {
		return prodotto;
	}


	public void setProdotto(int prodotto) {
		this.prodotto = prodotto;
	}


	public int getQuantita_disponibile() {
		return quantita_disponibile;
	}


	public void setQuantita_disponibile(int quantita_disponibile) {
		this.quantita_disponibile = quantita_disponibile;
	}
	
	

}
