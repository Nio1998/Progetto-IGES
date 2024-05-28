package prodotto;

import java.io.Serializable;

public class VideogiocoBean implements Serializable {

	private static final long serialVersionUID = 1L;

	private int prodotto;
	private double dimensione;
	private int pegi;
	private boolean edizione_limitata;
	private int ncd;
	private String vkey;
	private String software_house;
	
	public VideogiocoBean() {
		prodotto=0;
		dimensione=0;
		pegi=0;
		edizione_limitata=false;
		ncd=0;
		vkey=null;
		software_house=null;
	}
	
	public int getProdotto() {
		return prodotto;
	}
	public void setProdotto(int prodotto) {
		this.prodotto=prodotto;
	}
	
	public double getDimensione() {
		return dimensione;
	}
	public void setDimensione(double dimensione) {
		this.dimensione=dimensione;
	}
	
	public int getPegi() {
		return pegi;
	}
	public void setPegi(int pegi) {
		this.pegi=pegi;
	}
	
	public boolean getEdizione_limitata() {
		return edizione_limitata;
	}
	
	public void setEdizione_limitata(boolean edizione_limitata) {
		this.edizione_limitata=edizione_limitata;
	}
	public int getNcd() {
		return ncd;
	}
	public void setNcd(int ncd) {
		this.ncd=ncd;
	}
	public String getVkey() {
		return vkey;
	}
	public void setVkey(String vkey) {
		this.vkey=vkey;
	}
	
	public String getSoftware_house() {
		return software_house;
	}
	public void setSoftware_house(String software_house) {
		this.software_house=software_house;
	}
	
}
