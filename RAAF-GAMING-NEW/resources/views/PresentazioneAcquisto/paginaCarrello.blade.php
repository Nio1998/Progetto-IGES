@extends('layouts.app')

@section('title', 'Carrello - RAAF-GAMING')

@section('content')
<link rel="stylesheet" href="{{ asset('css/stileCarrello.css') }}">
<link rel="stylesheet" href="{{ asset('css/StileIndex.css') }}">
<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">

@php
$prodotti = $data['prodotti'];
@endphp

<main class="page">
    <section class="shopping-cart dark">
        <div class="container" style="min-height:100vh;">

            <div class="block-heading">
                @if(session('success'))
                    <h2 name="acquisto" style="color:orange;">{{ session('success') }}</h2>
                @else
                    <h2 style="color:orange;">Conferma Acquisto</h2>
                    <p>Benvenuto nel tuo Carrello, premi Conferma per procedere con l'acquisto.</p>
                @endif

                @if(session('error'))
                    <h2 name="prodottoNonDisponibile" style="color:red;">{{ session('error') }}</h2>
                @endif
            </div>

            <div class="content">
                <div class="row">

                    {{-- Lista prodotti --}}
                    <div class="col-md-12 col-lg-8">
                        <div class="items">
                            @if($prodotti === null || $prodotti->isEmpty())
                                <p name="NonHaiProdotti" class="my-5" style="text-align:center; color:orange; font-weight:bold;">
                                    NON HAI NESSUN PRODOTTO NEL CARRELLO!
                                </p>
                            @else
                                @foreach($prodotti as $prodotto)
                                    <div class="product">
                                        <div class="row">
                                            <div class="col-md-3 mt-4 ml-1 mr-1">
                                                <img class="img-fluid mx-auto d-block image"
                                                     src="{{ route('prodotto.getImmagine', ['codice' => $prodotto->codice_prodotto]) }}"
                                                     style="border-radius:10px;"
                                                     alt="{{ $prodotto->nome }}">
                                            </div>
                                            <div class="col-md-8">
                                                <div class="info">
                                                    <div class="row">

                                                        {{-- Nome e info --}}
                                                        <div class="col-md-5 product-name">
                                                            <div class="product-name">
                                                                <a href="{{ route('prodotto.show', ['codice' => $prodotto->codice_prodotto]) }}"
                                                                   name="nomeProd" style="color:orange;">
                                                                    <h4 style="color:orange;">{{ $prodotto->nome }}</h4>
                                                                </a>
                                                                <div class="product-info">
                                                                    <div>Data Uscita: <span class="value">{{ $prodotto->data_uscita?->format('d-m-Y') }}</span></div>
                                                                    <div>Scontato del: <span class="value">{{ $prodotto->sconto }}%</span></div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- Elimina --}}
                                                        <div class="col-md-4 quantity">
                                                            <form method="POST" action="{{ route('carrello.delete') }}">
                                                                @csrf
                                                                <input type="hidden" name="id" value="{{ $prodotto->codice_prodotto }}">
                                                                <button type="submit" class="form-control quantity-input btn btn-outline-danger mt-4">
                                                                    elimina
                                                                </button>
                                                            </form>
                                                        </div>

                                                        {{-- Prezzo --}}
                                                        <div class="col-md-3 price">
                                                            @if($prodotto->sconto == 0)
                                                                <span>{{ number_format($prodotto->prezzo, 2) }}&euro;</span>
                                                            @else
                                                                <span>{{ number_format($prodotto->prezzo_effettivo, 2) }}&euro;</span>
                                                            @endif
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    {{-- Riepilogo costo --}}
                    <div class="col-md-12 col-lg-4">
                        <div class="summary" style="border-color:orange; background-color:white;">
                            <h3>COSTO TOTALE</h3>

                            @if($prodotti === null || $prodotti->isEmpty())
                                <div class="summary-item">
                                    <span class="text">Totale</span>
                                    <span class="price">0&euro;</span>
                                </div>
                                <button type="button" class="btn btn-outline-warning btn-lg btn-block" onclick="noppuoi();">
                                    Conferma
                                </button>
                            @else
                                @php
                                    $totale = $prodotti->sum(fn($p) => $p->prezzo_effettivo);
                                @endphp
                                <div class="summary-item mb-3">
                                    <span class="text">Totale</span>
                                    <span class="price">{{ number_format($totale, 2) }}&euro;</span>
                                </div>
                                <form action="{{ route('carrello.shop') }}" method="POST" onsubmit="return controllo(this);">
                                    @csrf
                                    <div class="summary-item mb-4">
                                        <span class="text mr-4">Indirizzo di consegna:</span>
                                        <input type="text" name="indirizzodiconsegna" required maxlength="200" class="form-control mt-2" style="border-radius:8px;">
                                    </div>
                                    <button type="submit" class="btn btn-outline-warning btn-lg btn-block">Conferma</button>
                                </form>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</main>

<script>
function controllo(x) {
    if (x.elements["indirizzodiconsegna"].value.length > 0 && x.elements["indirizzodiconsegna"].value.length <= 49)
        return true;
    else {
        alert("INDIRIZZO DI CONSEGNA NON VALIDO!");
        return false;
    }
}

function noppuoi() {
    alert("Non hai prodotti nel carrello");
}
</script>

@endsection