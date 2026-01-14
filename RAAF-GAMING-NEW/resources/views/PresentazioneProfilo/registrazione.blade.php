<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>REGISTRAZIONE</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- CSS personalizzato -->
    <link rel="stylesheet" href="{{ asset('css/stileRegistrazione.css') }}" type="text/css">
</head>
<body>
    <div class="row" style="width:70%;">
        <div class="col-md-12 ml-3 mt-3">
            <img src="{{ asset('immagini/logo.png') }}" alt="RAAF-GAMING" class="rounded float-left" style="width:180px; position: static;">
        </div>
    </div>
    
    <div class="container" style="background-color: rgba(254,254,233,0.5); width:50%">
        <form action="{{ route('registrazione.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            
            <div class="form-row">
                <div class="col-md-12 mb-3 mt-2 d-flex justify-content-center">
                    <p class="h2" style="font-family: Acunim Variable Consent;">INSERISCI I TUOI DATI</p>
                </div>
            </div>
            
            <!-- Messaggi di errore -->
            @if($errors->any())
                <div class="form-row">
                    <div class="col-md-12 mb-1 mt-1 d-flex justify-content-center">
                        <p name="messaggioerrore" class="h5" style="color:red;">
                            {{ $errors->first() }}
                        </p>
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="form-row">
                    <div class="col-md-12 mb-1 mt-1 d-flex justify-content-center">
                        <p class="h5" style="color:red;">{{ session('error') }}</p>
                    </div>
                </div>
            @endif
            
            <!-- Nome e Cognome -->
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="validationCustom01">Nome:</label>
                    <input type="text" 
                           name="nome" 
                           class="form-control @error('nome') is-invalid @enderror" 
                           id="validationCustom01" 
                           placeholder="Rocco" 
                           value="{{ old('nome') }}"
                           required 
                           style="width:80%;">
                    @error('nome')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="validationCustom02">Cognome:</label>
                    <input type="text" 
                           name="cognome" 
                           class="form-control @error('cognome') is-invalid @enderror" 
                           id="validationCustom02" 
                           placeholder="Iuliano" 
                           value="{{ old('cognome') }}"
                           required 
                           style="width:80%;">
                    @error('cognome')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <!-- Data di nascita e Codice carta -->
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="dataNascita">Data di nascita:</label>
                    <input id="datadinascita" 
                           type="date" 
                           name="data" 
                           class="form-control @error('data') is-invalid @enderror" 
                           value="{{ old('data') }}"
                           required 
                           style="width:80%;">
                    @error('data')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="validationCustom07">Codice carta di credito:</label>
                    <input type="text" 
                           name="codicecarta" 
                           class="form-control @error('codicecarta') is-invalid @enderror" 
                           id="validationCustom07" 
                           placeholder="**** **** **** ****" 
                           value="{{ old('codicecarta') }}"
                           required 
                           style="width:80%;">
                    @error('codicecarta')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <!-- Data scadenza e CVV -->
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="data_scadenza">Data di scadenza carta:</label>
                    <input id="data_scadenza" 
                           type="date" 
                           name="data_scadenza" 
                           class="form-control @error('data_scadenza') is-invalid @enderror" 
                           value="{{ old('data_scadenza') }}"
                           required 
                           style="width:80%;">
                    @error('data_scadenza')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="validationCustom08">CVV:</label>
                    <input type="text" 
                           name="codice_cvv" 
                           class="form-control @error('codice_cvv') is-invalid @enderror" 
                           id="validationCustom08" 
                           placeholder="***" 
                           value="{{ old('codice_cvv') }}"
                           required 
                           style="width:80%;">
                    @error('codice_cvv')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <!-- Email e Password -->
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="validationCustomUsername">Email</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroupPrepend">@</span>
                        </div>
                        <input type="email" 
                               name="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="validationCustomUsername" 
                               placeholder="r.iuliano13@gmail.com" 
                               value="{{ old('email') }}"
                               aria-describedby="inputGroupPrepend" 
                               required 
                               style="width:80%; border-radius:0px 5px 5px 0px;">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="validationCustom04">Password</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <i class="input-group-text fa fa-lock" style="font-size:24px;" id="inputGroupPrepend"></i>
                        </div>
                        <input type="password" 
                               name="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="validationCustom04" 
                               placeholder="**************" 
                               required 
                               style="width:80%; border-radius:0px 5px 5px 0px;">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Bottone Submit -->
            <div class="form-row">
                <div class="col-md-12 d-flex justify-content-center mb-3">
                    <input type="submit" 
                           value="REGISTRATI" 
                           class="invio" 
                           style="font-family: Eras Demi ITC; background-color: #FF6600; width: 60%; font-weight: bold; color:white; border-radius: 15px;">
                </div>
            </div>
            
            <!-- Link al Login -->
            <div class="form-row">
                <div class="col-md-12 d-flex justify-content-center mb-3">
                    <p>Hai già un account? <a href="{{route('login')}}">ACCEDI</a></p>
                </div>
            </div>
        </form>
    </div>

    <!-- jQuery e Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!-- JavaScript personalizzato -->
    <script src="{{ asset('javascript/controlloRegistrazione.js') }}"></script>
</body>
</html>