

<x-layout title="Temporadas">
    @isset($menssage)
    <div class="alert alert-success">
        {{$menssage}}
    </div>
    @endisset
    <ul class="list-group ">
        @foreach($seasons as $season)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <a href={{ route('episodes.index', $season->id)}}>{{$series->name}} - Temporada {{$season->number}}</a>
            <span class="badge bg-secondary"> Eps {{$season->episodes->count()}} </span>
        </li>
        @endforeach
    </ul>
</x-layout>