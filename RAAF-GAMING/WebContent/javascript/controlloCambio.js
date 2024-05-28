function controlloCambioCredenziali(pass1,pass2,codiceCarta,cvv,data){
	
	
	if(pass1.value.length>0 && pass2.value.length>0){ //se ha scritto qualcosa nel campo nuova password
	
		if(pass1.value.length>=8){//se la password è valida
			pass1.style.border="2px solid green";
			if(pass1.value==pass2.value){ //lunghezza conferma password corretta e coincide con quella di prima

				pass2.style.border="2px solid green";
			} 
			else if(pass1.value!=pass2.value){
				alert("Le password non corrispondono");
				pass2.value="";
				pass2.style.border="2px solid red";
				return false;
			}
		}
		else{
			alert("password non valida");
		}
	}
	else if(pass1.value.length>0 && pass2.value.length==0){
		alert("Conferma la password");
		pass2.style.border="2px solid red";
		return false;
	}
	else if(pass1.value.length==0 && pass2.value.length>0){
		alert("Conferma la password");
		pass1.style.border="2px solid red";
		return false;
	}	

	
	const d2= new Date(data.value);
	const dataAttuale= new Date();
	
	if(codiceCarta.value.length>0 && cvv.value.length>0 && (d2.getFullYear()!=NaN && data.value.length==10)){ //se ha scritto qualcosa per modificare la carta
		
		if(codiceCarta.value.length==16){//se il codice dell carta è corretto
			codiceCarta.style.border="2px solid green";
		}
		else{
			alert("Hai inserito una carta non valida");
			codiceCarta.value="";
			codiceCarta.style.border="2px solid red";
			return false;	
		}
		
		//CONTROLLO IL CVV
		
		if(cvv.value.length==3){//se il cvv è corretto
			cvv.style.border="2px solid green";
		}
		else{
			alert("Hai inserito un cvv non valido");
			cvv.value="";
			cvv.style.border="2px solid red";
			return false;	
		}
		
		
		//CONTROLLO LA DATA DI SCADENZA
		
		
		if(d2.getFullYear()==dataAttuale.getFullYear()){ //se l'anno di scadenza è uguale all'anno attuale
			if((d2.getMonth()+1)==(dataAttuale.getMonth()+1)){ //se il mese della data di scadenza è uguale a quella attuale
				
				if(d2.getDate()>dataAttuale.getDate()){//se il giorno della data di scadenza è maggiore rispetto a quello di oggi OK
					data.style.border="2px solid green";
				}
				else{//il giorno della scadenza coincide al giorno attuale o prima NO
					alert("La carta di credito e' scaduta");
					data.style.border="2px solid red";
					return false;
				}
			}
			else if((d2.getMonth()+1)>(dataAttuale.getMonth()+1)){//il mese della data di scadenza è maggiore al mese attuale OK
				data.style.border="2px solid green";
			}
			else{//il mese della data di scadenza è precendete a quello attuale NO
				alert("La carta di credito e' scaduta");
				data.style.border="2px solid red";
				return false;
			}
		}
		else if(d2.getFullYear()>dataAttuale.getFullYear()){//se l'anno di scadenza e' maggiore rispetto a quello attuale OK
			data.style.border="2px solid green";
		}
		else{//se l'anno di scadenza è minore rispetto all'anno attuale NO
			alert("La carta di credito e' scaduta");
			data.style.border="2px solid red";
			return false;
		}
		
	}
	else if(codiceCarta.value.length>0 && (cvv.value.length==0 || data.value.length==0)){ //se inserisco solo il codice della carta NO
		alert("Dati carta mancante");
		cvv.style.border="2px solid red";
		data.style.border="2px solid red";
		return false;
	}
	else if(cvv.value.length>0 && (codiceCarta.value.length==0 || data.value.length==0)){ //se inserisco solo il CVV della carta NO
		alert("Dati carta mancante");
		codiceCarta.style.border="2px solid red";
		data.style.border="2px solid red";
		return false;
	}
	else if((d2.getFullYear()!=NaN && data.value.length==10) && (codiceCarta.value.length==0 || cvv.value.length==0)){ //se ho inserito solo la data di scadenza NO
		alert("Dati carta mancante");
		codiceCarta.style.border="2px solid red";
		cvv.style.border="2px solid red";
		return false;
	}
	 
	return true;
		
}