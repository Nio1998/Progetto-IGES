package acquisto;

import java.io.Serializable;

public class CorriereEspressoBean implements Serializable{

	/**
	 *  è il bean della tabella CorriereEspresso del DB
	 */
	private static final long serialVersionUID = 1L;
	
	private String nome;
	private String sito;
	
	public CorriereEspressoBean()
	{
		nome="";
		sito="";
	}
	
	public String getNome() {
		return nome;
	}
	public void setNome(String nome) {
		this.nome = nome;
	}
	public String getSito() {
		return sito;
	}
	public void setSito(String sito) {
		this.sito = sito;
	}
	
	

}
