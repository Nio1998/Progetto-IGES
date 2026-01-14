

function controlloValori(x){
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
