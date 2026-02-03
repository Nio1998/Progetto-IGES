

function controlloEmailPassword(x){
	var e= x.elements["email"];
	
	var mailformat=/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
	if(e.value.match(mailformat)){//controllo formato email
		e.style.border="2px solid green";
		var p= x.elements["password"]; //controllo lunghezza password
		var valore= p.value.length;
		if(valore<5){
			alert("Hai inserito una password errata");
			p.value="";
			p.style.border="2px solid red";
			return false;
		}
		else{
			p.style.border="2px solid green";
			return true;
		}
	}else{
		alert("Hai inserito un'email errata");
		e.value="";
		e.style.border="2px solid red";
		return false;
	}
	
	
}

function controlloRegistrazione(x){
	var n=x.elements["nome"];
	if(n.value.length<=15 && n.value.length>0){ //lunghezza nome corretta
		n.style.border="2px solid green";
		var c=x.elements["cognome"];
		if(c.value.length<=15 && c.value.length>0){ //lunghezza cognome corretta
			c.style.border="2px solid green";
			
			const dataAttuale= new Date();
			const dataForm= new Date(x.elements["data"].value);
			
			var eta=(dataAttuale.getFullYear()-dataForm.getFullYear());
			
			
			if(eta>=10 && eta<=110)
			{
			
				x.elements["data"].style.border="2px solid green";
				var i=x.elements["codicecarta"];
				var formatocarta = /^[0-9]+$/;
				if(i.value.length==16 && i.value.length>0 && i.value.match(formatocarta)!=null)
				{ //codice carta corretto
					i.style.border="2px solid green";
					return controlloData(x);
				}
				else{
					alert("Hai inserito un codice carta non valida");
					i.value="";
					i.style.border="2px solid red";
					return false;//codice carta non corretto 
					}
				
			}
			else
			{
				x.elements["data"].style.border="2px solid red";
				x.elements["data"].value="";
				alert("Non hai l'età per registrarti!");
				return false;
			}
			
		
		} else{
			alert("Hai inserito un cognome non valido");
			c.value="";
			c.style.border="2px solid red";
			return false;
		}
	}else{
		alert("Hai inserito un nome non valido");
		n.value="";
		n.style.border="2px solid red";
		return false;
	}
}

function controlloData(x){
	const dataAttuale= new Date();
	const dataForm= new Date(x.elements["data_scadenza"].value);
	if(dataForm.getFullYear()==dataAttuale.getFullYear()){
		if((dataForm.getMonth()+1)>=(dataAttuale.getMonth()+1)){
			if(dataForm.getDate()>=dataAttuale.getDate()){
				x.elements["data_scadenza"].style.border="2px solid green";//data scadenza>data attuale
				return controlloCVV(x);				
			}
		}
		x.elements["data_scadenza"].style.border="2px solid red";//data scadenza<data attuale
		x.elements["data_scadenza"].value="";
		alert("Data scadenza non valida")
		return false;
	}
	else if(dataForm.getFullYear()>dataAttuale.getFullYear())
	{
				x.elements["data_scadenza"].style.border="2px solid green";//data scadenza>data attuale
				return controlloCVV(x);
	}
	else{
			x.elements["data_scadenza"].style.border="2px solid red";//data scadenza<data attuale
			x.elements["data_scadenza"].value="";
			alert("Data scadenza non valida")
			return false;
		}
}
	
		
function controlloCVV(x){
	var n=x.elements["codice_cvv"];
	var formatocvv = /^[0-9]+$/;
		if(n.value.length==3 && n.value.length>0 && n.value.match(formatocvv)!=null){
			n.style.border="2px solid green";
			return controlloEmailPassword(x);
		}
		else{
			alert("Hai inserito un CVV non valido");
			n.value="";
			n.style.border="2px solid red";
			return false;
		}
}