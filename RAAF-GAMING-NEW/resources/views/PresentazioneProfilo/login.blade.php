<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LOGIN</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- CSS personalizzato -->
    <link rel="stylesheet" href="{{ asset('css/stileLogin.css') }}" >
    
<body>
    <div class="container ml-5 mb-3 mt-2">
        <img src="{{ asset('immagini/logo.png') }}" alt="RAAF-GAMING" style="width:180px; position: static;">
    </div>
    
    <div class="container d-flex justify-content-center" style="background-color: rgba(254,254,233,0.5); width:50%;">
        <form action="{{route('login')}}" method="POST" id="stileForm">
            @csrf
            
            <h4 class="h4 mt-3 ml-2">BENVENUTO GIOCATORE!</h4>
            
            <!-- Messaggio di errore -->
            @if($errors->any())
                <h5 style="color:red; text-align:center;" name="messaggioerrore">
                    Email/Password errata!
                </h5>
            @endif
            
            @if(session('error'))
                <h5 style="color:red; text-align:center;">
                    {{ session('error') }}
                </h5>
            @endif
            
            @if(session('success'))
                <h5 style="color:green; text-align:center;">
                    {{ session('success') }}
                </h5>
            @endif
            
            <!-- Campo Email -->
            <div class="input-group mb-3 mt-3 ml-4 w-75">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">
                        <i class='fas fa-user-alt' style='font-size:20px'></i>
                    </span>
                </div>
                <input type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       style="border-radius:0px 5px 5px 0px;" 
                       placeholder="Email" 
                       name="email" 
                       value="{{ old('email') }}"
                       required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Campo Password -->
            <div class="input-group mb-3 mt-3 ml-4 w-75">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon2">
                        <i class="fa fa-lock" style="font-size:20px"></i>
                    </span>
                </div>
                <input type="password" 
                       name="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       style="border-radius:0px 5px 5px 0px;" 
                       placeholder="************"
                       required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Bottone Submit -->
            <input type="submit" value="ACCEDI" class="invio ml-5"><br>
            
            <!-- Link Registrazione -->
            <p class="mr-2">oppure<a href="{{route('registrazione')}}" target="_self">&nbsp;REGISTRATI</a></p>
        </form>
    </div>
    
    <!-- jQuery e Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!-- JavaScript personalizzato -->
    <script src="{{ asset('js/controlloLogin.js') }}"></script>
</body>
</html>