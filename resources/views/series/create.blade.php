<x-layout title="Nova Serie">
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <form action={{route('series.store')}} method="post">
        @csrf
        <div class="row">
            <div class="col col-8">
                <label for="nome"class="form-label">Nome:</label>
                <input type="text" class="form-control" name="nome" id="nome">

            </div>
            <div class="col col-2">
                <label for="qtd_temporada" class="form-label">Temporadas:</label>
                <input type="number" class="form-control" name="qtd_temporadas" id="nome">

            </div>
            <div class="col col-2">
                <label for="qtd_episodios" class="form-label">Episódios:</label>
                <input type="number" class="form-control" name="qtd_episodios" id="nome">

            </div>
        </div>  
        <button class="btn btn-primary mt-2">Adicionar</button>
    </form>
</x-layout>
