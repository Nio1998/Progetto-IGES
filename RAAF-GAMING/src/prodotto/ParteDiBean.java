package prodotto;

import java.io.Serializable;

public class ParteDiBean implements Serializable {

	private static final long serialVersionUID = 1L;
	
	private int videogioco;
	private String categoria;
	
	public ParteDiBean()
	{
		videogioco = 0;
		categoria = "";
	}

	public int getVideogioco() {
		return videogioco;
	}

	public void setVideogioco(int videogioco) {
		this.videogioco = videogioco;
	}

	public String getCategoria() {
		return categoria;
	}

	public void setCategoria(String categoria) {
		this.categoria = categoria;
	}
	
	

}
