package prodotto;

import java.io.Serializable;

import java.sql.Date;

public class ProdottoBean implements Serializable{

	private static final long serialVersionUID = 1L;
	
	private int codice_prodotto;
	private double prezzo;
	private byte[] copertina;
	private int sconto;
	private Date data_uscita;
	private String nome;
	private int quantita_fornitura;
	private Date ultima_fornitura;
	private String fornitore;
	private String gestore;
	

	public ProdottoBean()
	{
		codice_prodotto=0;
		prezzo=0;
		copertina = null;
		data_uscita=null;
		nome="";
		quantita_fornitura=0;
		ultima_fornitura=null;
		fornitore="";
	}

	public String getGestore() {
		return gestore;
	}

	public void setGestore(String gestore) {
		this.gestore = gestore;
	}
	
	public int getCodice_prodotto() {
		return codice_prodotto;
	}

	public void setCodice_prodotto(int codice_prodotto) {
		this.codice_prodotto = codice_prodotto;
	}

	public double getPrezzo() {
		return prezzo;
	}

	public void setPrezzo(double prezzo) {
		this.prezzo = prezzo;
	}

	public int getSconto() {
		return sconto;
	}

	public void setSconto(int sconto) {
		this.sconto = sconto;
	}

	public Date getData_uscita() {
		return data_uscita;
	}

	public void setData_uscita(Date data_uscita) {
		this.data_uscita = data_uscita;
	}

	public String getNome() {
		return nome;
	}

	public void setNome(String nome) {
		this.nome = nome;
	}

	public int getQuantita_fornitura() {
		return quantita_fornitura;
	}

	public void setQuantita_fornitura(int quantita_fornitura) {
		this.quantita_fornitura = quantita_fornitura;
	}

	public Date getUltima_fornitura() {
		return ultima_fornitura;
	}

	public void setUltima_fornitura(Date ultima_fornitura) {
		this.ultima_fornitura = ultima_fornitura;
	}

	public String getFornitore() {
		return fornitore;
	}

	public void setFornitore(String fornitore) {
		this.fornitore = fornitore;
	}

	public byte[] getCopertina() {
		return copertina;
	}

	public void setCopertina(byte[] copertina) {
		this.copertina = copertina;
	}
	
	
	

}
