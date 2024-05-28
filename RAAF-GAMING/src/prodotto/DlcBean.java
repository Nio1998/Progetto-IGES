package prodotto;

import java.io.Serializable;

public class DlcBean implements Serializable{

	/**
	 * 
	 */
	private static final long serialVersionUID = 1L;

	private int prodotto;
	private double dimensione;
	private String descrizione;
	
	public DlcBean()
	{
		prodotto=0;
		dimensione=0;
		descrizione="";
	}

	public int getProdotto() {
		return prodotto;
	}

	public void setProdotto(int prodotto) {
		this.prodotto = prodotto;
	}

	public double getDimensione() {
		return dimensione;
	}

	public void setDimensione(double dimensione) {
		this.dimensione = dimensione;
	}

	public String getDescrizione() {
		return descrizione;
	}

	public void setDescrizione(String descrizione) {
		this.descrizione = descrizione;
	}
	
	
}
