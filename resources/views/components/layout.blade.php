<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$title}} - Controle de series</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
    <div class="container">
        <h1>{{$title}}</h1>  
        @isset($menssage)
        <div class="alert alert-success">
            {{$menssage}}
        </div>
        @endisset  
        {{$slot}} 
    </div>
</body>
</html>