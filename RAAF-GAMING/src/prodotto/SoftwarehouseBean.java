package prodotto;

import java.io.Serializable;

public class SoftwarehouseBean implements Serializable {

	private static final long serialVersionUID = 1L;
	
	private String nomesfh;
	private byte[] logo;
	
	public SoftwarehouseBean() {
		nomesfh="";
		logo=null;
	}
	
	public String getNomesfh() {
		return nomesfh;
	}
	
	public void setNomesfh(String nomesfh) {
		this.nomesfh=nomesfh;
	}
	
	public byte[] getLogo(){
	 return logo ;
	 }
	 
	
	public void setLogo(byte[] logo){
	 this.logo=logo;
	 }

}
