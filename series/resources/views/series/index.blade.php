@extends('layout')

@section('cabecalho')
Séries
@endsection

@section('conteudo')
@if(!@empty($mensagem))
<div class="alert alert-success">
{{$mensagem}}
</div>
@endif

<a href="/series/criar" class="btn btn-dark mb-2">Adicionar</a>

<ul class="list-group ">
    @foreach($series as $serie)
    <li class="list-group-item d-flex justify-content-between align-items-center">
        {{$serie->nome}}
        <samp class="d-flex">
            <a href="/series/{{$serie->id}}/temporadas" class="btn btn-info btn-sm mr-2"><i class="fas fas fa-external-link-alt"></i></a>
            <form  method="post" action="/series/remover/{{$serie->id}}" onsubmit="return confirm('Tem certeza que deseja excluir')">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
            </form>
        </samp>
    </li>
    @endforeach
</ul>

@endsection