@extends('layouts.app')

@section('title', 'Risultato Ricerca - RAAF-GAMING')

{{-- Includiamo i CSS specifici di questa pagina --}}

    <link rel="stylesheet" href="{{ asset('css/StileIndex.css') }}">
    <link rel="stylesheet" href="{{ asset('css/stileGioco.css') }}">
    <link rel="stylesheet" href="{{ asset('css/stileCard.css') }}">


@section('content')

<div class="container pl-5" style="min-height:100vh;">

    <h3 class="ml-3 mt-5 mb-0" style="color:white;">Risultato Ricerca</h3>

    {{-- Messaggio se non ci sono prodotti --}}
    @if($prodotti->isEmpty())
        <div class="alert alert-warning mt-4 ml-3" role="alert">
            Nessun prodotto trovato con questo criterio di ricerca.
        </div>
    @else
        <div class="row" style="width:100%">
            <div class="col-md-12 inline-block">
                <ul class="cards ml-1 mr-1 pr-5">
                
                    @foreach($prodotti as $prodotto)
                        <li style="height:250px; max-width:250px;" name="prodottoCard">
                        
                            {{-- Link alla pagina di dettaglio --}}
                            <a href="#" class="card">
                                
                                {{-- Immagine tramite rotta dedicata --}}
                                <img src="#" 
                                     class="card__image" 
                                     alt="{{ $prodotto->nome }}" />
                                
                                <div class="card__overlay">
                                    <div class="card__header">                  
                                        <i class='fas fa-shopping-cart' style='font-size:27px; color:black;'></i>
                                        <div class="card__header-text">
                                            <h3 class="card__title">{{ $prodotto->nome }}</h3> 
                                            
                                            {{-- Logica Prezzo (calcolata nel Service) --}}
                                            @if($prodotto->in_promozione)
                                                <span class="card__status">
                                                    <span style="color:black; text-decoration:line-through;">
                                                        {{ number_format($prodotto->prezzo, 2) }}&euro;
                                                    </span>
                                                    &nbsp;&nbsp;
                                                    <h5 style="font-weight:bold; color:red;">
                                                        {{ number_format($prodotto->prezzo_effettivo, 2) }}&euro;
                                                    </h5>
                                                </span>
                                            @else
                                                <span class="card__status" style="color:red;">
                                                    {{ number_format($prodotto->prezzo_effettivo, 2) }}&euro;
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <p class="card__description">
                                        Data uscita:&nbsp;{{ \Carbon\Carbon::parse($prodotto->data_uscita)->format('d/m/Y') }}<br>
                                        Sconto:&nbsp;{{ $prodotto->sconto }}%
                                    </p>
                                </div>
                            </a>      
                        </li>
                    @endforeach
                        
                </ul>
            </div>
        </div>
    @endif

</div>

@endsection