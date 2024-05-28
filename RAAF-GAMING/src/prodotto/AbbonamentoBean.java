package prodotto;

import java.io.Serializable;

public class AbbonamentoBean implements Serializable {

	private static final long serialVersionUID = 1L;
	private String codice;
	private int prodotto;
	private int durata_abbonamento;
	
	
	public AbbonamentoBean() {
		codice="";
		prodotto=0;
		durata_abbonamento=1;
	}


	public String getCodice() {
		return codice;
	}


	public void setCodice(String codice) {
		this.codice = codice;
	}


	public int getProdotto() {
		return prodotto;
	}


	public void setProdotto(int prodotto) {
		this.prodotto = prodotto;
	}


	public int getDurata_abbonamento() {
		return durata_abbonamento;
	}


	public void setDurata_abbonamento(int durata_abbonamento) {
		this.durata_abbonamento = durata_abbonamento;
	}
	
	
}
