@extends('layout')

@section('cabecalho')
Temporadas {{$serie->nome}}
@endsection
@section('conteudo')

<ul class="list-group ">
    @foreach($temporadas as $temporada)
    <li class="list-group-item d-flex justify-content-between align-items-center">
        <a href="/temporadas/{{$temporada->id}}/episodios">Temporada {{$temporada->numero}}</a>
        <span class="badge badge-secondary"> Eps {{$temporada->episodios->count()}} </span>
    </li>
    @endforeach
@endsection