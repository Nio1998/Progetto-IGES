package prodotto;

import java.io.Serializable;

public class CategoriaBean implements Serializable {

	private static final long serialVersionUID = 1L;

	private String nome;
	
	public CategoriaBean() {
		nome="";
	}

	public String getNome() {
		return nome;
	}

	public void setNome(String nome) {
		this.nome = nome;
	}
	
}
