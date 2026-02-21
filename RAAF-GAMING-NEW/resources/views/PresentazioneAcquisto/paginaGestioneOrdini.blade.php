@php
$ordiniNonConsegnati = $data['ordiniNonConsegnati'];
$corrieri = $data['corrieri'];
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
    <title>Ordini da gestire</title>

    <style>
        body {
            overflow-x: hidden;
        }

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

            .table-responsive th,
            .table-responsive td {
                font-size: 0.85rem;
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

<h4 class="testo" style="font-family: Acunim Variable Concept; text-align:center">Crea spedizione:</h4>

@if(session('success'))
    <h4 style="color:green; text-align:center;">{{ session('success') }}</h4>
@endif

@if(session('error'))
    <h4 style="color:red; text-align:center;">{{ session('error') }}</h4>
@endif

<div class="form-row d-flex justify-content-center mb-3">
    <label>Crea spedizione
        <input type="radio" id="spedizioneProdotto" name="sceltaP" onchange="spedizioneProdotto()">
    </label>
</div>

<div class="container d-flex justify-content-center">
    <div id="ordine" style="display:none; width:100%;">
        <div class="table-responsive" style="background-color: rgba(254,254,233,0.5); border-radius:20px; padding:20px;">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Codice ordine:</th>
                        <th scope="col">Email utente:</th>
                        <th scope="col">Indirizzo utente:</th>
                        <th scope="col">Corriere espresso:</th>
                        <th scope="col">Data consegna:</th>
                        <th scope="col">Conferma:</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ordiniNonConsegnati as $i => $ordine)
                    <tr>
                        <form action="{{ route('formOrdini') }}" method="POST" onsubmit="return controlloOrdini('idata{{ $i }}');">
                            @csrf
                            <input type="hidden" name="numeroOrdine" value="{{ $ordine->codice }}">
                            <td>{{ $ordine->codice }}</td>
                            <td>{{ $ordine->cliente }}</td>
                            <td>{{ $ordine->indirizzo_di_consegna }}</td>
                            <td>
                                <select style="border-radius:8px; height:35px; width:110px;" name="corriere">
                                    @foreach($corrieri as $corriere)
                                        <option value="{{ $corriere->nome }}">{{ $corriere->nome }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="date" class="form-control idata{{ $i }}" name="consegnaO" style="border-radius:10px;">
                            </td>
                            <td>
                                <button class="btn btn-dark ml-3" style="border-radius:5px;">Conferma</button>
                            </td>
                        </form>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function spedizioneProdotto() {
        if (document.getElementById("spedizioneProdotto").checked) {
            document.getElementById("ordine").style.display = "block";
        }
    }

    function controlloOrdini(x) {
        const dataAttuale = new Date();
        const dataForm = new Date($("." + x).val());

        if (dataForm.getFullYear() === dataAttuale.getFullYear()) {
            if ((dataForm.getMonth() + 1) > (dataAttuale.getMonth() + 1)) {
                $("." + x).css("border", "2px solid green");
                return true;
            }
            if ((dataForm.getMonth() + 1) === (dataAttuale.getMonth() + 1)) {
                if (dataForm.getDate() >= dataAttuale.getDate()) {
                    $("." + x).css("border", "2px solid green");
                    return true;
                } else {
                    $("." + x).css("border", "2px solid red");
                    return false;
                }
            }
            $("." + x).css("border", "2px solid red");
            return false;
        } else if (dataForm.getFullYear() > dataAttuale.getFullYear()) {
            $("." + x).css("border", "2px solid green");
            return true;
        } else {
            $("." + x).css("border", "2px solid red");
            return false;
        }
    }
</script>

</body>
</html>