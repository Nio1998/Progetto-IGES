@php
$prodotti = $data['prodotti'];
$fornitori = $data['fornitori'];
$categorie = $data['categorie'];
$softwarehouses = $data['softwarehouses'];
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!-- css loghi -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    
    <!-- css nostri -->
    <link rel="stylesheet" href="{{ asset('css/stileAdmin.css') }}" type="text/css">
    
    <meta charset="UTF-8">
    <title>Pagina-Amministrazione</title>
    
    <style>
        /* Fix scroll orizzontale */
        body {
            overflow-x: hidden;
        }
        
        /* Header responsive */
        .header-admin {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }
        
        @media (max-width: 768px) {
            .header-admin {
                flex-direction: column;
                text-align: center;
            }
            
            .header-admin img {
                margin-bottom: 1rem;
            }
            
            .header-admin .btn {
                margin-top: 0 !important;
            }
        }
    </style>
</head>
<body>

<div class="container mb-3 mt-2">
    <div class="header-admin">
        <img src="{{ asset('immagini/logo.png') }}" alt="RAAF-GAMING" style="width:180px;">
        <a class="btn btn-dark" href="{{ route('logoutAdmin') }}" role="button">LogOut</a>
    </div>
</div>

<h4 class="testo" style="font-family: Acunim Variable Concept;text-align:center">Inserisci prodotto:</h4>

@if(session('success'))
    <h4 style="color:green; text-align:center;" name="successo">{{ session('success') }}</h4>
@endif

@if($errors->any())
    <div class="alert alert-danger" style="margin: 20px;">
        <h4>Errori di validazione:</h4>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('error'))
    <h4 style="color:red; text-align:center;" name="errore">{{ session('error') }}</h4>
@endif

<div class="form-row d-flex justify-content-center mb-3">
    <label>Nuovo prodotto
        <input type="radio" id="nuovoProdotto" name="sceltaP" onchange="formEsistente()">
    </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <label>Prodotto esistente
        <input type="radio" id="esistenteProdotto" name="sceltaP" onchange="formEsistente()">
    </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>

<div class="container d-flex justify-content-center mb-5">
    <div id="containerForm" style="background-color: rgba(254,254,233,0.5);border-radius:20px; width: 100%; max-width: 1200px; padding: 20px; display:none;">
    
        <!-- FORM PRODOTTI ESISTENTI -->
        <div id="esistente" style="display:none">
            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Nome</th>
                            <th scope="col">Prezzo</th>
                            <th scope="col">Quantita' attuale</th>
                            <th scope="col">Quantita' da rifornire</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prodotti as $prodotto)
                        <tr>
                            <form action="#" method="POST">
                                @csrf
                                <input type="hidden" name="prod" id="prod" value="{{ $prodotto->codice_prodotto }}">
                                <td>{{ $prodotto->nome }}</td>
                                <td>{{ $prodotto->prezzo }}</td>
                                <td>{{ $prodotto->quantita_disponibile }}</td>
                                <td><input type="number" name="quantita" min="1" class="form-control"></td>
                                <td><button class="btn btn-dark" style="border-radius:5px;">Rifornisci</button></td>
                            </form>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FORM NUOVO PRODOTTO -->
        <div id="nuovo" style="display:none">
            <form action="{{ route('formProdNuovo') }}" method="POST" name="nuovoProdotto" enctype="multipart/form-data" onsubmit="return controlloProdNuovo();">
                @csrf
                
                <div class="form-group mt-3">
                    <label>VIDEOGIOCO FISICO
                        <input type="radio" value="videogioco fisico" id="videogiocoRadio" name="sceltaP" onchange="prodottoForm();">
                    </label>&nbsp;&nbsp;&nbsp;
                    <label>VIDEOGIOCO DIGITALE
                        <input type="radio" value="videogioco digitale" id="videogiocoRadio2" name="sceltaP" onchange="prodottoForm();">
                    </label>&nbsp;&nbsp;&nbsp;
                    <label>CONSOLE
                        <input type="radio" value="console" id="consoleRadio" name="sceltaP" onchange="prodottoForm()">
                    </label>&nbsp;&nbsp;&nbsp;
                    <label>DLC
                        <input type="radio" value="dlc" id="dlcRadio" name="sceltaP" onchange="prodottoForm()">
                    </label>&nbsp;&nbsp;&nbsp;
                    <label>ABBONAMENTO
                        <input type="radio" value="abbonamento" id="abbonamentoRadio" name="sceltaP" onchange="prodottoForm()">
                    </label>&nbsp;&nbsp;&nbsp;
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="nomeP">Nome:</label>
                        <input type="text" class="form-control" id="nomeProdotto" name="nomeP" style="border-radius:10px">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="prezzoP">Prezzo:</label>
                        <input type="number" class="form-control" id="prezzoProdotto" name="prezzoP" step="0.01" style="border-radius:10px">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="scontoP">Sconto:</label>
                        <input type="number" class="form-control" id="scontoProdotto" name="scontoP" min="0" max="99" style="border-radius:10px">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="dataP">Data uscita:</label>
                        <input type="date" class="form-control" id="uscitaProdotto" name="dataP" style="border-radius:10px">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="fornitoreP">Fornitore:</label>
                        <select class="form-control" style="border-radius:8px;" name="fornitoreP">
                            @foreach($fornitori as $fornitore)
                                <option value="{{ $fornitore->nome }}">{{ $fornitore->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="quantitaP">Quantita da rifornire:</label>
                        <input type="number" class="form-control" id="quantitaProdottoNew" name="quantitaP" min="1" style="border-radius:10px">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="exampleFormControlFile1">Copertina:</label>
                        <input type="file" class="form-control-file" id="copertinaP" name="copertina" accept="image/*">
                    </div>
                </div>
                
                <div>
                    <!-- FORM VIDEOGIOCO -->
                    <div id="videogiocoForm" class="form-group" style="display:none">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <label>Dimensione:</label>
                                <input type="number" name="dimensioni" id="dim" min="1" class="form-control" style="border-radius:7px">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label>Pegi:</label>
                                <input type="number" name="pegi" id="pegi" min="3" max="18" class="form-control" style="border-radius:7px">
                            </div>
                            <div class="col-md-3 mb-2" id="ncdContainer">
                                <label for="ncd" id="labelncd">Numero di cd:</label>
                                <input type="number" name="ncd" id="ncd" min="1" class="form-control" style="border-radius:7px">
                            </div>
                            <div class="col-md-3 mb-2" id="chiaveContainer" style="display:none">
                                <label for="chiave" id="labelchiave">Chiave:</label>
                                <input type="text" name="chiave" id="chiave" class="form-control" style="border-radius:7px">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label>Software House:</label>
                                <select class="form-control" style="border-radius:8px;" name="nomesfh">
                                    @foreach($softwarehouses as $sfh)
                                        <option value="{{ $sfh->nomesfh }}">{{ $sfh->nomesfh }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label>Edizione limitata:</label>
                                <input type="number" name="limitata" id="limitata" min="0" max="1" class="form-control" style="border-radius:7px">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label>Categoria:</label>
                                <select class="form-control" style="border-radius:8px;" name="categoria">
                                    @foreach($categorie as $categoria)
                                        <option value="{{ $categoria->nome }}">{{ $categoria->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-dark mt-2" type="submit" style="border-radius:5px">Inserisci</button>
                    </div>
                    
                    <!-- FORM CONSOLE -->
                    <div id="consoleForm" class="form-group" style="display:none">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Specifiche:</label>
                                <input type="text" name="specifiche" id="specifiche" class="form-control" style="border-radius:7px">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Colore:</label>
                                <input type="text" name="colore" id="colore" class="form-control" style="border-radius:7px">
                            </div>
                        </div>
                        <button class="btn btn-dark mt-2" type="submit" style="border-radius:7px">Inserisci</button>
                    </div>
                    
                    <!-- FORM DLC -->
                    <div id="dlcForm" class="form-group" style="display:none">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Descrizione:</label>
                                <input type="text" name="descrizione" id="descDLC" class="form-control" style="border-radius:7px">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Dimensione:</label>
                                <input type="number" name="dimensioneDlc" id="dimDLC" min="1" class="form-control" style="border-radius:7px">
                            </div>
                        </div>
                        <button class="btn btn-dark mt-2" type="submit" style="border-radius:7px">Inserisci</button>
                    </div>
                    
                    <!-- FORM ABBONAMENTO -->
                    <div id="abbonamentoForm" class="form-group" style="display:none">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Codice:</label>
                                <input type="text" name="codice" id="codiceAbb" class="form-control" style="border-radius:7px">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Durata:</label>
                                <input type="number" name="durata" id="durAbb" min="1" max="12" class="form-control" style="border-radius:7px">
                            </div>
                        </div>
                        <button class="btn btn-dark mt-2" type="submit" style="border-radius:5px">Inserisci</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function prodottoForm() {
    if(document.getElementById("videogiocoRadio").checked) {
        document.getElementById("videogiocoForm").style.display = "block";
        document.getElementById("chiaveContainer").style.display = "none";
        document.getElementById("ncdContainer").style.display = "block";
        document.getElementById("consoleForm").style.display = "none";
        document.getElementById("dlcForm").style.display = "none";
        document.getElementById("abbonamentoForm").style.display = "none";
    }
    else if(document.getElementById("videogiocoRadio2").checked) {
        document.getElementById("videogiocoForm").style.display = "block";
        document.getElementById("chiaveContainer").style.display = "block";
        document.getElementById("ncdContainer").style.display = "none";
        document.getElementById("consoleForm").style.display = "none";
        document.getElementById("dlcForm").style.display = "none";
        document.getElementById("abbonamentoForm").style.display = "none";
    }
    else if(document.getElementById("consoleRadio").checked) {
        document.getElementById("consoleForm").style.display = "block";
        document.getElementById("videogiocoForm").style.display = "none";
        document.getElementById("dlcForm").style.display = "none";
        document.getElementById("abbonamentoForm").style.display = "none";
    }
    else if(document.getElementById("dlcRadio").checked) {
        document.getElementById("dlcForm").style.display = "block";
        document.getElementById("videogiocoForm").style.display = "none";
        document.getElementById("consoleForm").style.display = "none";
        document.getElementById("abbonamentoForm").style.display = "none";
    }
    else if(document.getElementById("abbonamentoRadio").checked) {
        document.getElementById("abbonamentoForm").style.display = "block";
        document.getElementById("videogiocoForm").style.display = "none";
        document.getElementById("dlcForm").style.display = "none";
        document.getElementById("consoleForm").style.display = "none";
    }
}

function formEsistente() {
    if(document.getElementById("esistenteProdotto").checked) {
        document.getElementById("containerForm").style.display = "block";
        document.getElementById("esistente").style.display = "block";
        document.getElementById("nuovo").style.display = "none";
    } else if(document.getElementById("nuovoProdotto").checked) {
        document.getElementById("containerForm").style.display = "block";
        document.getElementById("nuovo").style.display = "block";
        document.getElementById("esistente").style.display = "none";
    }
}

function controlloProdNuovo() {
    var nome = $("#nomeProdotto").val().length;
    
    if(nome > 0 && nome <= 50) {
        $("#nomeProdotto").css("border", "2px solid green");
    } else {
        $("#nomeProdotto").css("border", "2px solid red");
        return false;
    }

    var prezzo = $("#prezzoProdotto").val().length;
    
    if(($("#prezzoProdotto").val() < 9999) && ($("#prezzoProdotto").val() > 0)) {
        $("#prezzoProdotto").css("border", "2px solid green");
    } else {
        $("#prezzoProdotto").css("border", "2px solid red");
        return false;
    }
    
    var sconto = $("#scontoProdotto").val().length;
    
    if(($("#scontoProdotto").val() >= 0) && ($("#scontoProdotto").val() <= 99) && (sconto > 0)) {
        $("#scontoProdotto").css("border", "2px solid green");
    } else {
        $("#scontoProdotto").css("border", "2px solid red");
        return false;
    }
    
    var dataUscita = $("#uscitaProdotto").val().length;
    
    if(dataUscita == 10) {
        $("#uscitaProdotto").css("border", "2px solid green");
    } else {
        $("#uscitaProdotto").css("border", "2px solid red");
        return false;
    }
    
    if($("#quantitaProdottoNew").val() >= 1) {
        $("#quantitaProdottoNew").css("border", "2px solid green");
    } else {
        $("#quantitaProdottoNew").css("border", "2px solid red");
        return false;
    }

    if($("#copertinaP").get(0).files.length === 0) {
        $("#copertinaP").css("border", "2px solid red");
        return false;
    } else {
        $("#copertinaP").css("border", "2px solid green");
    }
    
    // Controllo tipo di prodotto
    if(document.getElementById("videogiocoRadio").checked) {
        var dim = $("#dim").val().length;

        if((dim > 0) && dim <= 3 && $("#dim").val() >= 1 && ($("#dim").val() <= 900)) {
            $("#dim").css("border", "2px solid green");
        } else {
            $("#dim").css("border", "2px solid red");
            return false;
        }
        
        var pegi = $("#pegi").val().length;

        if(($("#pegi").val() >= 3) && ($("#pegi").val() <= 18) && (pegi > 0)) {
            $("#pegi").css("border", "2px solid green");
        } else {
            $("#pegi").css("border", "2px solid red");
            return false;
        }
        
        var ncd = $("#ncd").val().length;

        if(ncd == 0) {
            $("#ncd").css("border", "2px solid red");
            return false;
        } else if($("#ncd").val() > 0) {
            $("#ncd").css("border", "2px solid green");
        }
        
        var limitata = $("#limitata").val().length;
        if(($("#limitata").val() == 0 || $("#limitata").val() == 1) && limitata > 0) {
            $("#limitata").css("border", "2px solid green");
        } else {
            $("#limitata").css("border", "2px solid red");
            return false;
        }

        return true;
    }
    else if(document.getElementById("videogiocoRadio2").checked) {
        $("#quantitaProdottoNew").val(1);
    
        var dim = $("#dim").val().length;

        if((dim > 0) && dim <= 3 && $("#dim").val() >= 1 && ($("#dim").val() <= 900)) {
            $("#dim").css("border", "2px solid green");
        } else {
            $("#dim").css("border", "2px solid red");
            return false;
        }
        
        var pegi = $("#pegi").val().length;

        if(($("#pegi").val() >= 3) && ($("#pegi").val() <= 18) && (pegi > 0)) {
            $("#pegi").css("border", "2px solid green");
        } else {
            $("#pegi").css("border", "2px solid red");
            return false;
        }
        
        var vkey = $("#chiave").val().length;
        
        if(vkey == 0) {
            $("#chiave").css("border", "2px solid red");
            return false;
        } else if(vkey > 0 && vkey <= 14) {
            $("#chiave").css("border", "2px solid green");
        } else {
            $("#chiave").css("border", "2px solid red");
            return false;
        }
        
        var limitata = $("#limitata").val().length;
        if(($("#limitata").val() == 0 || $("#limitata").val() == 1) && limitata > 0) {
            $("#limitata").css("border", "2px solid green");
        } else {
            $("#limitata").css("border", "2px solid red");
            return false;
        }

        return true;
    }
    else if(document.getElementById("consoleRadio").checked) {
        var specifica = $("#specifiche").val().length;
        var colore = $("#colore").val().length;
        if(specifica > 0 && specifica <= 20) {
            $("#specifiche").css("border", "2px solid green");
            
            if(colore > 0 && colore <= 8) {
                $("#colore").css("border", "2px solid green");
            } else {
                $("#colore").css("border", "2px solid red");
                return false;
            }
        } else {
            $("#specifiche").css("border", "2px solid red");
            return false;
        }
        return true;
    }
    else if(document.getElementById("dlcRadio").checked) {
        var descrizione = $("#descDLC").val().length;
        var dimensione = $("#dimDLC").val().length;

        if(descrizione > 0 && descrizione <= 50) {
            $("#descDLC").css("border", "2px solid green");
            
            if(dimensione > 0 && dimensione <= 2) {
                $("#dimDLC").css("border", "2px solid green");
            } else {
                $("#dimDLC").css("border", "2px solid red");
                return false;
            }
        } else {
            $("#descDLC").css("border", "2px solid red");
            return false;
        }
        return true;
    }
    else if(document.getElementById("abbonamentoRadio").checked) {
        var codAbb = $("#codiceAbb").val().length;
        var durAbb = $("#durAbb").val().length;

        if(codAbb > 0 && codAbb <= 11) {
            $("#codiceAbb").css("border", "2px solid green");

            if(durAbb > 0 && $("#durAbb").val() >= 1 && $("#durAbb").val() <= 12) {
                $("#durAbb").css("border", "2px solid green");
            } else {
                $("#durAbb").css("border", "2px solid red");
                return false;
            }
        } else {
            $("#codiceAbb").css("border", "2px solid red");
            return false;
        }
        return true;
    }
}
</script>

</body>
</html>