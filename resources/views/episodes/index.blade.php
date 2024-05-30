
<x-layout title="Episódios">
    <form action="">
        <ul class="list-group">
            @foreach($episodes as $episodie)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Episódio {{ $episodie->numero }}
                    <input type="checkbox">
                </li>

            @endforeach
        </ul>
        <button class="btn btn-primary mt-2 mb-2">Salvar</button>
    </form>
</x-layout>