<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>LOGIN-ADMIN</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    
    <link rel="stylesheet" href="{{ asset('css/stileAdmin.css') }}" type="text/css">
    
    <script src="{{ asset('javascript/controlloLogin.js') }}"></script>

</head>
<body>

    <div class="container ml-5 mb-3 mt-2">
        <img src="{{ asset('immagini/logo.png') }}" alt="RAAF-GAMING" style="width:180px; position: static;">
    </div>

    <div class="container d-flex justify-content-center" style="background-color: rgba(254,254,233,0.5); width:40%;" >
        
        <form action="{{route('loginAdmin')}}" method="POST" id="stileForm" onsubmit="return controlloValori(this);">
            
            @csrf

            <h3 class="testo mt-3 ml-2" style="font-family: Acunim Variable Consent;">Raaf-Gaming.it</h3>
            
            @if($errors->any() || session('message'))
                <h5 name="messaggioerrore" style="color:red; text-align:center;">
                    {{ session('message') ?? 'Email/Password errata!' }}
                </h5>
            @endif

            <div class="form-group mt-4">
                <label for="exampleInputEmail1"></label>
                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email" name="email" value="{{ old('email') }}">
            </div>
            
            <div class="form-group">
                <label for="exampleInputPassword1"></label>
                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="************" name="password">
            </div>
      
            <button type="submit" class="btn btn-dark mb-4">Accedi</button>
        </form>
    </div>

</body>
</html>