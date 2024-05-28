package acquisto;

import java.io.Serializable;
import java.sql.Date;

public class SpeditoBean implements Serializable {
	
	private static final long serialVersionUID = 1L;
	
	private String ordine;
	private String corriere_espresso;
	private Date data_consegna;
	
	public SpeditoBean() {
		ordine="";
		corriere_espresso="";
		data_consegna=null;
	}
	
	public String getOrdine() {
		return ordine;
	}
	public void setOrdine(String ordine) {
		this.ordine=ordine;
	}
	
	public String getCorriere_espresso() {
		return corriere_espresso;
	}
	
	public void setCorriere_esprersso(String corriere_espresso) {
		this.corriere_espresso=corriere_espresso;
	}
	
	public Date getData_consegna() {
		return data_consegna;
	}
	
	public void setData_consegna(Date data_consegna) {
		this.data_consegna=data_consegna;
	}
}
