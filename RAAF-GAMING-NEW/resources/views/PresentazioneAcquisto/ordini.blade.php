@extends('layouts.app')

@section('styles')
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: url({{ asset('immagini/fallOut.jpg') }}) no-repeat;
        background-attachment: fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }
</style>
@endsection

@section('content')

<div class="wrapper rounded" style="min-height:100vh;">

    <div class="px-4">

        <p class="text-center font-weight-bold" id="peppino" style="color:#FF9900; font-size:20px;">
            I TUOI ORDINI
        </p>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <p style="color:white; border-bottom: 3px solid; border-color: #FF9900;">History</p>
        </div>

        @if($ordini->isEmpty())
            <p style="color:white; text-align:center;">NON HAI EFFETTUATO ANCORA NESSUN ORDINE</p>
        @else
            <div class="table-responsive mt-3">
                <table class="table table-dark table-borderless table-hover">
                    <thead>
                        <tr>
                            <th scope="col" style="color:#00FF7F">
                                <i class='fas fa-shopping-basket' style='font-size:20px; display:inline;'></i> ORDER ID
                            </th>
                            <th scope="col" style="color:#00FF7F">
                                <i class="fa fa-home" style="font-size:20px; display:inline;"></i> INDIRIZZO CONSEGNA
                            </th>
                            <th scope="col" style="color:#00FF7F">
                                <i class='far fa-credit-card' style='font-size:20px; display:inline;'></i> CARTA CREDITO
                            </th>
                            <th scope="col" style="color:#00FF7F">
                                <i class='fas fa-calendar' style='font-size:20px; display:inline;'></i> DATA ACQUISTO
                            </th>
                            <th scope="col" style="color:#00FF7F">
                                <i class="fa fa-calendar" style="font-size:20px; display:inline;"></i> DATA CONSEGNA
                            </th>
                            <th scope="col" style="color:#00FF7F">
                                <i class='fas fa-money-bill-wave' style='font-size:20px; display:inline;'></i> TOTALE
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ordini as $ordine)
                            <tr>
                                <td>{{ $ordine->codice }}</td>
                                <td>
                                    <div style="max-width:100%; overflow:auto; white-space:nowrap;">
                                        {{ $ordine->indirizzo_di_consegna }}
                                    </div>
                                </td>
                                <td>****{{ substr($ordine->metodo_di_pagamento, 12, 4) }}</td>
                                <td class="text-muted">{{ $ordine->data_acquisto->format('d/m/Y') }}</td>
                                <td>
                                    @if($ordine->getSpedito)
                                        {{ $ordine->getSpedito->data_consegna->format('d/m/Y') }}
                                    @else
                                        IN FASE DI ELABORAZIONE
                                    @endif
                                </td>
                                <td class="d-flex justify-content-end align-items-center">
                                    {{ $ordine->prezzo_totale }}&euro;
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>

</div>

@endsection