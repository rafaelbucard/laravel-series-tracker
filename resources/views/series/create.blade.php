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
                <label for="name"class="form-label">Nome:</label>
                <input type="text" class="form-control" name="name" id="name">

            </div>
            <div class="col col-2">
                <label for="qt_seasons" class="form-label">Temporadas:</label>
                <input type="number" class="form-control" name="qt_seasons" id="qt_seasons">

            </div>
            <div class="col col-2">
                <label for="qt_episodes" class="form-label">Episódios:</label>
                <input type="number" class="form-control" name="qt_episodes" id="qt_episodes">

            </div>
        </div>  
        <button class="btn btn-primary mt-2">Adicionar</button>
    </form>
</x-layout>
