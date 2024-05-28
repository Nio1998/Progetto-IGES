package prodotto;

import java.io.Serializable;

public class RecensisceBean implements Serializable {
	
	private static final long serialVersionUID = 1L;
	
	private String cliente;
	private int prodotto;
	private int voto;
	private String commento;
	
	public RecensisceBean() {
		cliente="";
		prodotto=0;
		voto=0;
		commento="";
	}
	public String getCliente() {
		return cliente;
	}
	
	public void setCliente(String cliente) {
		 this.cliente=cliente;
	}
	
	public int getProdotto() {
		return prodotto;
	}

	public void setProdotto(int prodotto) {
		this.prodotto=prodotto;
	}
	
	public int getVoto() {
		return voto;
	}
	
	public void setVoto(int voto) {
		this.voto=voto;
	}
	
	public String getCommento() {
		return commento;
	}
	
	public void setCommento(String commento) {
		this.commento = commento;
	}
	
	
}
