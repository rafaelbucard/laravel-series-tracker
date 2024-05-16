<x-layout title="Atualizar Série">
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <form action={{route('series.update', $serie->id)}} method="post">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col col-8">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" class="form-control" name="nome" id="nome" value="{{$serie->nome}}">
            </div>
        </div>  
        <button class="btn btn-primary mt-2">Confirmar</button>
    </form>
</x-layout>
