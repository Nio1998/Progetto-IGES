package prodotto;
import java.io.Serializable;

/*
 * è il bean della tabella Console del DB
 * */
public class ConsoleBean implements Serializable{

	private static final long serialVersionUID = 1L;
	
	private int prodotto;
	private String specifica;
	private String colore;
	
	public ConsoleBean()
	{
	prodotto=0;
	specifica="";
	colore="";
	}

	public int getProdotto() {
		return prodotto;
	}

	public void setProdotto(int prodotto) {
		this.prodotto = prodotto;
	}

	public String getSpecifica() {
		return specifica;
	}

	public void setSpecifica(String specifica) {
		this.specifica = specifica;
	}

	public String getColore() {
		return colore;
	}

	public void setColore(String colore) {
		this.colore = colore;
	}
	
}
