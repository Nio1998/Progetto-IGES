@extends('layouts.app')

@section('title', 'Dettaglio Prodotto - ' . ($prodotto->nome ?? 'RAAF-GAMING'))

@section('content')
<link rel="stylesheet" href="{{ asset('css/stileGioco.css') }}">
<link rel="stylesheet" href="{{ asset('css/StileIndex.css') }}">

<div class="container d-flex justify-content-center" style="background-color:rgba(230,230,230,0.5); min-height:100vh; width:100%;">

    @if(isset($message))
        <div class="row w-100 align-items-center">
            <div class="col-12">
                <h2 style="color:red; text-align:center;">{{ $message }}</h2>
            </div>
        </div>
    @else

        <div class="row w-100">

            {{-- Colonna immagine --}}
            <div class="col-md-6 mt-4 mb-2 d-flex justify-content-center align-items-start">
                <img src="{{ route('prodotto.getImmagine', ['codice' => $prodotto->codice_prodotto]) }}" 
                     style="border-radius:15px; height:auto; width:75%; max-height: 500px; object-fit: cover; background-color: #ccc;" 
                     alt="Copertina {{ $prodotto->nome }}">
            </div>

            {{-- Colonna dettagli --}}
            <div class="col-md-6">
                <div class="container ml-0 mt-4 mr-5"
                     style="background-color:rgba(240,240,230,0.8); height:auto; min-height:50%; width:100%; border-radius:15px; overflow:hidden;">

                    <div class="row">
                        {{-- Header Titolo --}}
                        <div class="col-12 py-2" style="background-color:rgb(35,35,35);">
                            <h2 class="nome pl-3" style="color:white; font-family:Impact; text-transform:uppercase; font-weight:bold; margin:0;">
                                {{ $prodotto->nome }}
                            </h2>
                        </div>

                        {{-- Dettagli specifici --}}
                        <div class="col-12 p-4">
                            @if($prodotto->videogioco)
                                <h6>
                                    <strong>Edizione limitata:</strong> {{ $prodotto->videogioco->edizione_limitata ? 'SI' : 'NO' }}<br>
                                    <strong>Data Uscita:</strong> {{ $prodotto->data_uscita }}<br>
                                    <strong>Dimensione:</strong> {{ $prodotto->videogioco->dimensione }}<br>
                                    <strong>Tipo:</strong> {{ is_null($prodotto->videogioco->vkey) ? 'FISICO (' . $prodotto->videogioco->ncd . ' CD)' : 'DIGITALE' }}<br>
                                    <strong>Software House:</strong> {{ $prodotto->videogioco->software_house }}<br>
                                    <strong>Sconto:</strong> {{ $prodotto->sconto }}%<br>
                                    <strong>PEGI:</strong> {{ $prodotto->videogioco->pegi }}
                                </h6>
                            @elseif($prodotto->console)
                                <h6>
                                    <strong>Specifica:</strong> {{ $prodotto->console->specifica }}<br>
                                    <strong>Data Uscita:</strong> {{ $prodotto->data_uscita }}<br>
                                    <strong>Colore:</strong> {{ $prodotto->console->colore }}<br>
                                    <strong>Sconto:</strong> {{ $prodotto->sconto }}%
                                </h6>
                            @elseif($prodotto->abbonamento)
                                <h6>
                                    <strong>Durata:</strong> {{ $prodotto->abbonamento->durata_abbonamento }} mesi<br>
                                    <strong>Data Uscita:</strong> {{ $prodotto->data_uscita }}<br>
                                    <strong>Sconto:</strong> {{ $prodotto->sconto }}%
                                </h6>
                            @elseif($prodotto->dlc)
                                <h6>
                                    <strong>Descrizione:</strong> {{ $prodotto->dlc->descrizione }}<br>
                                    <strong>Data Uscita:</strong> {{ $prodotto->data_uscita }}<br>
                                    <strong>Dimensione:</strong> {{ $prodotto->dlc->dimensione }}<br>
                                    <strong>Sconto:</strong> {{ $prodotto->sconto }}%
                                </h6>
                            @endif

                            <hr>

                            {{-- Prezzo e Carrello --}}
                            <div class="d-flex align-items-center">
                                <h3 class="mb-0">
                                    @if($prodotto->sconto > 0)
                                        <span style="color:gray; text-decoration:line-through; font-size: 0.8em;">{{ number_format($prodotto->prezzo, 2) }}€</span>
                                        <span class="ml-2" style="font-weight:bold; color:red;">{{ number_format($prodotto->prezzo_effettivo, 2) }}€</span>
                                    @else
                                        <span style="font-weight:bold;">{{ number_format($prodotto->prezzo, 2) }}€</span>
                                    @endif
                                </h3>

                                @if($prodotto->disponibile)
                                    <button class="btn ml-auto" style="background:transparent;" onclick="aggiungiCarrello()">
                                        <i id="sostituisciCarrello" class='fas fa-shopping-cart' style='font-size:35px; color:black;'></i>
                                    </button>
                                @else
                                    <button class="btn ml-auto" style="background:transparent;" onclick="nonPuoiAcquistare()">
                                        <i class='fas fa-shopping-cart' style='font-size:35px; color:gray;'></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Box Recensioni --}}
                <div class="container mt-4 mb-5">
                    <h4 style="font-family:Impact; text-transform:uppercase;">Recensioni</h4>
                    
                    <div id="stampe" class="p-3 mb-3" style="height:150px; overflow-y:auto; background-color:rgba(240,240,230,0.8); border-radius:10px;">
                        @forelse($prodotto->recensioni as $recensione)
                            <div class="mb-2 border-bottom pb-2">
                                <strong>{{ $recensione->cliente }}</strong> - Voto: {{ $recensione->voto }}/10<br>
                                <em>{{ $recensione->commento }}</em>
                            </div>
                        @empty
                            <p class="text-muted">Nessuna recensione ancora. Sii il primo a recensire!</p>
                        @endforelse
                    </div>

                    <div class="form-group">
                        <div class="mb-2">
                            <span class="star-rating">
                                @for($i = 1; $i <= 10; $i++)
                                    <input type="radio" name="rating" value="{{ $i }}" id="stella{{ $i }}"><i></i>
                                @endfor
                            </span>
                        </div>
                        <textarea class="form-control mb-2" id="commento" rows="3" placeholder="Lascia la tua opinione..." style="resize:none"></textarea>
                        
                        @if(isset($cliente))
                            <button type="button" class="btn btn-dark btn-block" onclick="recensione()">
                                INVIA RECENSIONE
                            </button>
                        @else
                            <button type="button" class="btn btn-dark btn-block" onclick="nonPuoiRecensire()">
                                INVIA RECENSIONE
                            </button>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    @endif
</div>

{{-- JavaScript --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
var flag = 0;

// Aggiungi al carrello
function aggiungiCarrello() {
    $.ajax({
        type: "POST",
        url: "{{ route('prodotto.aggiungiCarrello') }}",
        data: {
            _token: "{{ csrf_token() }}",
            id: {{ $prodotto->codice_prodotto }}
        },
        dataType: "json",
        success: function(data) {
            alert(data.message);
            $("#sostituisciCarrello").removeClass("fas fa-shopping-cart");
            $("#sostituisciCarrello").addClass("fa fa-cart-arrow-down");
        },
        error: function(err) {
            console.log(err);
            alert("Errore durante l'aggiunta al carrello");
        }
    });
}

// Invia recensione
function recensione() {
    // Trova quale stella è selezionata
    var voto = null;
    for(var i = 1; i <= 10; i++) {
        if(document.getElementById("stella" + i).checked) {
            voto = i;
            break;
        }
    }
    
    // Validazione voto
    if(!voto) {
        alert("Non hai inserito il voto");
        return;
    }
    
    // Validazione commento
    var commento = document.getElementById("commento").value;
    if(!commento || commento.trim() === "") {
        alert("Commento non inserito");
        return;
    }
    
    // Invia recensione
    $.ajax({
        type: "POST",
        url: "{{ route('recensione.store') }}",
        data: {
            _token: "{{ csrf_token() }}",
            prodotto: {{ $prodotto->codice_prodotto }},
            voto: voto,
            commento: commento
        },
        dataType: "json",
        success: function(data) {
            if(data.success) {
                alert("Recensione pubblicata con successo!");
                
                // Aggiungi recensione alla lista (evita duplicati)
                if(flag == 0) {
                    var nuovaRecensione = '<div class="mb-2 border-bottom pb-2">' +
                        '<strong>{{ auth()->user()->email ?? "Tu" }}</strong> - Voto: ' + voto + '/10<br>' +
                        '<em>' + commento + '</em>' +
                        '</div>';
                    $("#stampe").prepend(nuovaRecensione);
                    flag = 1;
                    
                    // Reset form
                    document.getElementById("commento").value = "";
                    $('input[name="rating"]').prop('checked', false);
                }
            } else {
                alert(data.message || "Hai già recensito questo prodotto");
            }
        },
        error: function(xhr) {
            if(xhr.status === 422) {
                alert("Hai già recensito questo prodotto");
            } else {
                alert("Errore durante l'invio della recensione");
            }
        }
    });
}

// Prodotto non disponibile
function nonPuoiAcquistare() {
    alert("Prodotto non disponibile in magazzino");
}

// Non autenticato
function nonPuoiRecensire() {
    alert("Effettua l'accesso per recensire!");
}
</script>
@endsection