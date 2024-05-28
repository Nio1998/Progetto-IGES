package profilo;

import java.io.Serializable;
import java.sql.Date;

public class CartaDiCreditoBean implements Serializable{

	private static final long serialVersionUID = 1L;
	private String codicecarta;
	private Date data_scadenza;
	private int codice_cvv;
	
	
	
	public CartaDiCreditoBean() {
		codicecarta="";
		data_scadenza=null;
		codice_cvv=0;
	}
	
	public String getCodicecarta() {
		return codicecarta;
	}
	
	public void setCodicecarta(String codicecarta) {
		this.codicecarta = codicecarta;
	}
	
	public Date getData_scadenza() {
		return data_scadenza;
	}
	
	public void setData_scadenza(Date data_scadenza) {
		this.data_scadenza = data_scadenza;
	}
	
	public int getCodice_cvv() {
		return codice_cvv;
	}
	
	public void setCodice_cvv(int codice_cvv) {
		this.codice_cvv = codice_cvv;
	}
	
	
}
