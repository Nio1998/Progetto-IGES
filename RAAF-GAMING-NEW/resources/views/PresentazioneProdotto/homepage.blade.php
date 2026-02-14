@extends('layouts.app')

@section('title', 'HomePage - RAAF-GAMING')


    <link rel="stylesheet" href="{{ asset('css/StileIndex.css') }}">
    <link rel="stylesheet" href="{{ asset('css/stileCard.css') }}">


@section('content')

    <div class="container" style="background-color:rgba(230,230,230,0.5); min-height:100vh;">
        
        {{-- Loop principale: Chunk divide la collection in gruppi da 3 per le righe --}}
        @foreach($prodotti->chunk(3) as $chunk)
            <div class="row">
                <div class="col-md-12 inline-block">
                    <ul class="cards">
                        @foreach($chunk as $prodotto)
                            <li style="height:250px; max-width:250px;">
                                
                                {{-- Link al dettaglio prodotto --}}
                                <a href="{{route('prodotto.show', ['codice'=> $prodotto->codice_prodotto])}}" class="card">
                                    
                                    {{-- Immagine --}}
                                    <img src="{{ route('prodotto.getImmagine', ['codice' => $prodotto->codice_prodotto]) }}" 
                                         class="card__image" 
                                         alt="{{ $prodotto->nome }}" />
                                    
                                    <div class="card__overlay">
                                        <div class="card__header">
                                            {{-- Icona Carrello: Puoi usare $prodotto->disponibile per cambiarne il colore se vuoi --}}
                                            <i class='fas fa-shopping-cart' 
                                               style='font-size:27px; color: {{ $prodotto->disponibile ? "black" : "grey" }};'>
                                            </i>
                                            
                                            <div class="card__header-text">
                                                <h3 class="card__title">{{ $prodotto->nome }}</h3> 
                                                
                                                {{-- 
                                                    NUOVA LOGICA PREZZI:
                                                    Usiamo direttamente le proprietà calcolate dal Service.
                                                    Nessun calcolo matematico qui!
                                                --}}
                                                @if($prodotto->in_promozione)
                                                    {{-- Caso in Sconto --}}
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
                                                    {{-- Prezzo Pieno --}}
                                                    <span class="card__status" style="color:red;">
                                                        {{ number_format($prodotto->prezzo_effettivo, 2) }}&euro;
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <p class="card__description">
                                            Data uscita:&nbsp;{{ \Carbon\Carbon::parse($prodotto->data_uscita)->format('d/m/Y') }}<br>
                                            
                                            {{-- Mostra lo sconto solo se esiste --}}
                                            @if($prodotto->in_promozione)
                                                Sconto:&nbsp;{{ $prodotto->sconto }}%
                                            @else
                                                <br> {{-- Spazio vuoto per allineamento --}}
                                            @endif
                                            
                                            {{-- Opzionale: Mostra stato disponibilità --}}
                                            @if(!$prodotto->disponibile)
                                                <br><span style="color: red; font-weight: bold;">NON DISPONIBILE</span>
                                            @endif
                                        </p>
                                    </div>
                                </a>      
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach

    </div>

@endsection