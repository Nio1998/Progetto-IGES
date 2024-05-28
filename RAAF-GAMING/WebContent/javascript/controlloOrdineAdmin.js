
function controlloProdEsistente(x){
	if($("#quantitaProdottoEx").val()>=1){
		$("#quantitaProdottoEx").css("border","2px solid green");
		return true;
	}
	else{
		$("#quantitaProdottoEx").css("border","2px solid red");
		return true;
	}
	
}

function controlloProdNuovo(){
	var prezzo=$("#prezzoProdotto").val().length; 
	if(($("#prezzoProdotto").val()>=0) && (prezzo>0)){
		
		$("#prezzoProdotto").css("border","2px solid green");
		
		var sconto=$("#scontoProdotto").val().length;
		if(($("#scontoProdotto").val()>=0) && ($("#scontoProdotto").val()<=99) && (sconto>0)){
			
			$("#scontoProdotto").css("border","2px solid green");
			
			var quantita=$("#quantitaProdottoNew").val().length;
			if(($("#quantitaProdottoNew").val()>=1) && (quantita>0)){
				$("#quantitaProdottoNew").css("border","2px solid green");
				
				//controllo che tipo di prodotto vuole mettere
				if(document.getElementById("videogiocoRadio").checked){ //se e' un videogioco

					var dim= $("#dim").val().length; 
					if($("#dim").val()>=1 && (dim>0)){//se la dimensione del videogioco e' ok
						$("#dim").css("border","2px solid green");
						
						var pegi=$("#pegi").val().length;
						if(($("#pegi").val()>=3) && ($("#pegi").val()<=18) && (pegi>0)){//se la pegi e' ok
							$("#pegi").css("border","2px solid green");
							
							var ncd= $("#ncd").val().length;
							var key= $("#chiave").val().length;
							if((ncd==0) && (key==0)){// se non ha inserito ne cd e key
								$("#ncd").css("border","2px solid red");
								$("#chiave").css("border","2px solid red");
								return false;
							}
							else if(ncd==0){//se non ha messo cd allora c'e' chiave
								
								if($("#chiave").val()<=14 && key>0){
									$("#chiave").css("border","2px solid green");
								}
								else{
									$("#chiave").css("border","2px solid red");
									return false;
								}
								
							}
							else if(key==0){//se non ha messo chiave allora c'e' cd
								
								if($("#ncd").val()>=1 && ncd>0){
									$("#ncd").css("border","2px solid green");
								}
								else{
									$("#ncd").css("border","2px solid red");
									return false;
								}
							}
							
							var limitata= $("#limitata").val().length;
							if(($("#limitata").val()==0 || $("#limitata").val()==1) && limitata>0){
								$("#limitata").css("border","2px solid green");		
							}
							else{
								$("#limitata").css("border","2px solid red");
								return false;
							}
					
							return true;
						}
						else{
							$("#pegi").css("border","2px solid red");
							return false;
						}
						
					}
					else{
						$("#dim").css("border","2px solid red");
						return false;
					}
				}
				else if(document.getElementById("consoleRadio").checked){//se e' una console
					
				}
				else if(document.getElementById("dlcRadio").checked){//se e' un dlc
				
				}
				else if(document.getElementById("abbonamentoRadio").checked){//se e' un abbonamento
				
				}
				
			}
			else{
				$("#quantitaProdottoNew").css("border","2px solid red");
				return false;
			}
		}
		else{
			$("#scontoProdotto").css("border","2px solid red");
			return false;		
		}
		
	}
	else{
		$("#prezzoProdotto").css("border","2px solid red");	
		return false;
	}
}