

function controlloProdNuovo(){

	var nome=$("#nomeProdotto").val().length;
	
	if(nome>0 && nome<=50){ //se la lunghezza del nome del prodotto è compatibile con il DB
		$("#nomeProdotto").css("border","2px solid green");
	}
	else{
		$("#nomeProdotto").css("border","2px solid red");
		return false;	
	}

	var prezzo=$("#prezzoProdotto").val().length; 
	if(($("#prezzoProdotto").val()>=0) && (prezzo>0) && ($("#prezzoProdotto").val()<=5000)){
		
		$("#prezzoProdotto").css("border","2px solid green");
		
		var sconto=$("#scontoProdotto").val().length;
		if(($("#scontoProdotto").val()>=0) && ($("#scontoProdotto").val()<=99) && (sconto>0)){
			
			$("#scontoProdotto").css("border","2px solid green");
			
			var dataUscita= $("#uscitaProdotto").val().length;
			if(dataUscita==10){
				$("#uscitaProdotto").css("border","2px solid green");
			}
			else{
				$("#uscitaProdotto").css("border","2px solid red");
				return false;
			}
			
			var quantita=$("#quantitaProdottoNew").val().length;
			if(($("#quantitaProdottoNew").val()>=1) && (quantita<=9) && (quantita>0)){
				$("#quantitaProdottoNew").css("border","2px solid green");
				
				if($("#copertinaP").get(0).files.length === 0){	//se non ha inserito l'immagine del prodotto
					$("#copertinaP").css("border","2px solid red");
					return false;
				}
				else{
					$("#copertinaP").css("border","2px solid green");
				}
				
				//controllo che tipo di prodotto vuole mettere
				if(document.getElementById("videogiocoRadio").checked){ //se e' un videogioco

					var dim= $("#dim").val().length; 
					
					if((dim>0) && dim<=3 && $("#dim").val()>=1 &&  ($("#dim").val()<=900)){//se la dimensione del videogioco e' ok
						$("#dim").css("border","2px solid green");
						
						var pegi=$("#pegi").val().length;
						
						if(($("#pegi").val()>=3) && ($("#pegi").val()<=18) && (pegi>0)){//se la pegi e' ok
							$("#pegi").css("border","2px solid green");
							
							var ncd= $("#ncd").val().length;
							var key= $("#chiave").val().length;
							alert(key);
							if((ncd==0) && (key==0)){// se non ha inserito ne cd e key
								$("#ncd").css("border","2px solid red");
								$("#chiave").css("border","2px solid red");
								return false;
							}
							else if(ncd>0 && key>0){//se ha inserito sia num cd e key
								$("#ncd").css("border","2px solid red");
								$("#chiave").css("border","2px solid red");
								return false;
							}
							else if(ncd==0){//se non ha messo cd allora c'e' chiave
								
								if(key>0 && key<=14){
									$("#chiave").css("border","2px solid green");
								}
								else{
									$("#chiave").css("border","2px solid red");
									return false;
								}
								
							}
							else if(key==0){//se non ha messo chiave allora c'e' cd
								
								if($("#ncd").val()>=1 && $("#ncd").val()<=2 && ncd>0){
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
					var specifica= $("#specifiche").val().length;
					var colore= $("#colore").val().length;
					if(specifica>0 && specifica<=20){
						
						$("#specifiche").css("border","2px solid green");
						
						if(colore>0 && colore<=8){
							$("#colore").css("border","2px solid green");
						}
						else{
							$("#colore").css("border","2px solid red");
							return false;
						}
					}
					else{
						$("#specifiche").css("border","2px solid red");
						return false;					
					}
					return true;
				}
				else if(document.getElementById("dlcRadio").checked){//se e' un dlc
					var descrizione= $("#descDLC").val().length;
					var dimensione= $("#dimDLC").val().length;
					
					if(descrizione>0 && descrizione<=50){
						
						$("#descDLC").css("border","2px solid green");						
						
						if(dimensione>0 && dimensione<=2){
							$("#dimDLC").css("border","2px solid green");
						}
						else{
							$("#dimDLC").css("border","2px solid red");
							return false;
						}
					}
					else{
						$("#descDLC").css("border","2px solid red");
						return false;
					}
					return true;
				}
				else if(document.getElementById("abbonamentoRadio").checked){//se e' un abbonamento
					var codAbb= $("#codiceAbb").val().length;
					var durAbb= $("#durAbb").val().length;
					
					if(codAbb>0 && codAbb<=11){
						$("#codiceAbb").css("border","2px solid green");
					
						if(durAbb>0 && $("#durAbb").val()>=1 && $("#durAbb").val()<=12){
							$("#durAbb").css("border","2px solid green");						
						}
						else{
							$("#durAbb").css("border","2px solid red");
							return false;						
						}
					}
					else{
						$("#codiceAbb").css("border","2px solid red");
						return false;					
					}
					return true;
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