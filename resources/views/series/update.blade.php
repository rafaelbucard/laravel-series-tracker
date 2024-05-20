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
    <form action={{route('series.update', $series->id)}} method="post">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col col-8">
                <label for="name" class="form-label">Nome:</label>
                <input type="text" class="form-control" name="name" id="nome" value="{{$series->name}}">
            </div>
        </div>  
        <button class="btn btn-primary mt-2">Confirmar</button>
    </form>
</x-layout>
