@php
    $utente = null;
    $carta = null // Assumendo relazione con CartaFedelta
@endphp

@extends('layouts.app')

@section('title', 'Profilo - RAAF-GAMING')

@section('content')
<link rel="stylesheet" href="{{ asset('css/stileProfilo.css') }}">

<div class="container profilo-container">
    <div class="row mt-3">
        <!-- Colonna Carta Fedeltà -->
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="flip-card">
                <div class="flip-card-inner">
                    <!-- Fronte della carta -->
                    <div class="flip-card-front">
                        <div class="card text-white carta-fedelta">
                            <img src="{{ asset('immagini/cartafedelta.png') }}" 
                                 class="card-img carta-img" 
                                 alt="Carta Fedeltà">
                            <div class="card-img-overlay carta-overlay">
                                <div class="carta-info">
                                    <ul class="info-item">
                                        <li class="info-label">EMAIL:</li>
                                        <li class="info-value">abcs</li>
                                    </ul>
                                    <ul class="info-item">
                                        <li class="info-label">NOME:</li>
                                        <li class="info-value">seddgasdg</li>
                                    </ul>
                                    <ul class="info-item">
                                        <li class="info-label">COGNOME:</li>
                                        <li class="info-value">SDVSDGV</li>
                                    </ul>
                                    <ul class="info-item">
                                        <li class="info-label">CARTA DI CREDITO:</li>
                                        <li class="info-value" id="cartaAggiornata">
                                            ****@php /*substr($utente->carta_credito, 12, 4)*/ @endphp
                                        </li>
                                    </ul>
                                    <ul class="info-item">
                                        <li class="info-label">CODICE CARTA:</li>
                                        <li class="info-value">dfhgszdfhsdfhsd</li>
                                    </ul>
                                    <ul class="info-item">
                                        <li class="info-label">PUNTI CARTA:</li>
                                        <li class="info-value">0</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Retro della carta (opzionale) -->
                    <div class="flip-card-back">
                        <div class="card text-white carta-fedelta-back">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                <h3 class="mb-4">RAAF-GAMING</h3>
                                <p class="text-center">Carta Fedeltà Premium</p>
                                <p class="text-center">Accumula punti con ogni acquisto!</p>
                                <div class="mt-3">
                                    <i class="fas fa-gamepad fa-3x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonna Form Modifica Credenziali -->
        <div class="col-lg-6 col-md-12">
            <div class="form-modifica">
                <div class="form-header">
                    <h2 class="text-center mb-4">Modifica Credenziali</h2>
                    <p class="text-center subtitle">Aggiorna password e carta di credito</p>
                </div>

                <!-- Messaggio di notifica -->
                <div id="notifica" class="alert" style="display:none;"></div>

                @csrf
                
                <!-- Sezione Password -->
                <div class="form-section">
                    <h5 class="section-title">
                        <i class="fas fa-lock mr-2"></i>Cambia Password
                    </h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="inputPassword1">Nuova password</label>
                            <input type="password" 
                                   name="password1" 
                                   class="form-control" 
                                   id="inputPassword1" 
                                   placeholder="Minimo 8 caratteri">
                            <small class="form-text text-muted">Minimo 8 caratteri</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="inputPassword2">Conferma password</label>
                            <input type="password" 
                                   name="password2" 
                                   class="form-control" 
                                   id="inputPassword2" 
                                   placeholder="Conferma password">
                        </div>
                    </div>
                </div>

                <!-- Sezione Carta di Credito -->
                <div class="form-section mt-4">
                    <h5 class="section-title">
                        <i class="fas fa-credit-card mr-2"></i>Aggiorna Carta di Credito
                    </h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="inputCarta1">Numero carta</label>
                            <input type="text" 
                                   name="numeroCarta" 
                                   class="form-control" 
                                   id="inputCarta1" 
                                   placeholder="1234 5678 9012 3456"
                                   maxlength="16">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="inputCarta2">CVV</label>
                            <input type="text" 
                                   name="cvvCarta" 
                                   class="form-control" 
                                   id="inputCarta2" 
                                   placeholder="123"
                                   maxlength="3">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="inputCarta3">Scadenza</label>
                            <input type="date" 
                                   name="dataScadenza" 
                                   class="form-control" 
                                   id="inputCarta3">
                        </div>
                    </div>
                </div>

                <!-- Bottone Invio -->
                <div class="form-row mt-4">
                    <div class="col-12 text-center">
                        <button type="button" 
                                class="btn btn-primary btn-lg btn-aggiorna" 
                                onclick="aggiornamentoCredenziali()">
                            <i class="fas fa-save mr-2"></i>AGGIORNA CREDENZIALI
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/controlloCambio.js') }}"></script>
<script>
    function aggiornamentoCredenziali() {
        var pass1 = document.getElementById("inputPassword1");
        var pass2 = document.getElementById("inputPassword2");
        var codiceCarta = document.getElementById("inputCarta1");
        var cvv = document.getElementById("inputCarta2");
        var data = document.getElementById("inputCarta3");
        
        if (controlloCambioCredenziali(pass1, pass2, codiceCarta, cvv, data)) {
            var dati = {
                passwordNuova: $("#inputPassword1").val(),
                cartaNuova: $("#inputCarta1").val(),
                codiceNuovo: $("#inputCarta2").val(),
                dataScadNuova: $("#inputCarta3").val(),
                _token: $('input[name="_token"]').val()
            };
            
            $.ajax({
                type: "POST",
                url: "#",
                data: dati,
                dataType: "json",
                success: function(risposta) {
                    if (risposta.errorMessage != null) {
                        $("#notifica").removeClass("alert-success").addClass("alert-danger");
                        $("#notifica").html('<i class="fas fa-exclamation-circle mr-2"></i>' + risposta.errorMessage);
                        $("#notifica").fadeIn();
                    } else {
                        var messaggi = [];
                        
                        if (risposta.password == true) {
                            messaggi.push("Password modificata con successo!");
                        }
                        if (risposta.carta != null) {
                            $("#cartaAggiornata").html(risposta.carta);
                            messaggi.push("Carta di credito aggiornata!");
                        }
                        
                        if (messaggi.length > 0) {
                            $("#notifica").removeClass("alert-danger").addClass("alert-success");
                            $("#notifica").html('<i class="fas fa-check-circle mr-2"></i>' + messaggi.join('<br>'));
                            $("#notifica").fadeIn();
                            
                            // Pulisci i campi dopo successo
                            $("#inputPassword1, #inputPassword2, #inputCarta1, #inputCarta2, #inputCarta3").val("");
                            $(".form-control").css("border", "");
                        }
                    }
                    
                    // Nascondi notifica dopo 5 secondi
                    setTimeout(function() {
                        $("#notifica").fadeOut();
                    }, 5000);
                },
                error: function(xhr) {
                    $("#notifica").removeClass("alert-success").addClass("alert-danger");
                    $("#notifica").html('<i class="fas fa-exclamation-circle mr-2"></i>Errore durante l\'aggiornamento. Riprova.');
                    $("#notifica").fadeIn();
                }
            });
        }
    }

    function controlloCambioCredenziali(pass1, pass2, codiceCarta, cvv, data) {
        // Controllo Password
        if (pass1.value.length > 0 && pass2.value.length > 0) {
            if (pass1.value.length >= 8) {
                pass1.style.border = "2px solid green";
                if (pass1.value == pass2.value) {
                    pass2.style.border = "2px solid green";
                } else {
                    alert("Le password non corrispondono");
                    pass2.value = "";
                    pass2.style.border = "2px solid red";
                    return false;
                }
            } else {
                alert("Password non valida (minimo 8 caratteri)");
                pass1.style.border = "2px solid red";
                return false;
            }
        } else if (pass1.value.length > 0 && pass2.value.length == 0) {
            alert("Conferma la password");
            pass2.style.border = "2px solid red";
            return false;
        } else if (pass1.value.length == 0 && pass2.value.length > 0) {
            alert("Inserisci la nuova password");
            pass1.style.border = "2px solid red";
            return false;
        }

        // Controllo Carta di Credito
        const d2 = new Date(data.value);
        const dataAttuale = new Date();

        if (codiceCarta.value.length > 0 && cvv.value.length > 0 && data.value.length > 0) {
            // Controllo numero carta
            if (codiceCarta.value.length == 16) {
                codiceCarta.style.border = "2px solid green";
            } else {
                alert("Numero carta non valido (16 cifre)");
                codiceCarta.value = "";
                codiceCarta.style.border = "2px solid red";
                return false;
            }

            // Controllo CVV
            if (cvv.value.length == 3) {
                cvv.style.border = "2px solid green";
            } else {
                alert("CVV non valido (3 cifre)");
                cvv.value = "";
                cvv.style.border = "2px solid red";
                return false;
            }

            // Controllo data scadenza
            if (d2.getFullYear() == dataAttuale.getFullYear()) {
                if ((d2.getMonth() + 1) == (dataAttuale.getMonth() + 1)) {
                    if (d2.getDate() > dataAttuale.getDate()) {
                        data.style.border = "2px solid green";
                    } else {
                        alert("La carta di credito è scaduta");
                        data.style.border = "2px solid red";
                        return false;
                    }
                } else if ((d2.getMonth() + 1) > (dataAttuale.getMonth() + 1)) {
                    data.style.border = "2px solid green";
                } else {
                    alert("La carta di credito è scaduta");
                    data.style.border = "2px solid red";
                    return false;
                }
            } else if (d2.getFullYear() > dataAttuale.getFullYear()) {
                data.style.border = "2px solid green";
            } else {
                alert("La carta di credito è scaduta");
                data.style.border = "2px solid red";
                return false;
            }
        } else if (codiceCarta.value.length > 0 || cvv.value.length > 0 || data.value.length > 0) {
            alert("Compila tutti i campi della carta di credito");
            if (codiceCarta.value.length == 0) codiceCarta.style.border = "2px solid red";
            if (cvv.value.length == 0) cvv.style.border = "2px solid red";
            if (data.value.length == 0) data.style.border = "2px solid red";
            return false;
        }

        return true;
    }
</script>
@endsection