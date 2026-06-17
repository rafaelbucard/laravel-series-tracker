<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-brand-gradient">Editar série</h1>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <form action="{{ route('series.update', $series) }}" method="POST" enctype="multipart/form-data"
              class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-6">
            @csrf @method('PUT')

            <div class="flex gap-4">
                <x-series-cover :series="$series" class="w-24 shrink-0 aspect-[2/3]" />
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <x-input-label for="name" :value="'Nome'" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $series->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="streaming_service_id" :value="'Streaming'" />
                        <select id="streaming_service_id" name="streaming_service_id"
                                class="mt-1 block w-full rounded-lg border-slate-300 focus:border-brand-blue focus:ring-brand-blue">
                            <option value="">— sem streaming —</option>
                            @foreach ($streamingServices as $svc)
                                <option value="{{ $svc->id }}" @selected(old('streaming_service_id', $series->streaming_service_id) == $svc->id)>{{ $svc->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="year" :value="'Ano'" />
                        <x-text-input id="year" name="year" type="number" min="1900" max="2100" class="mt-1 block w-full" :value="old('year', $series->year)" />
                    </div>
                </div>
            </div>

            <div>
                <x-input-label for="synopsis" :value="'Sinopse'" />
                <textarea id="synopsis" name="synopsis" rows="3"
                          class="mt-1 block w-full rounded-lg border-slate-300 focus:border-brand-blue focus:ring-brand-blue">{{ old('synopsis', $series->synopsis) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="cover_file" :value="'Trocar capa (upload)'" />
                    <input id="cover_file" name="cover_file" type="file" accept="image/*"
                           class="mt-1 block w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-brand-gradient file:px-3 file:py-2 file:text-sm file:font-semibold file:text-white">
                </div>

                <div>
                    <x-input-label for="cover_url" :value="'Ou nova URL da capa'" />
                    <x-text-input id="cover_url" name="cover_url" type="url" class="mt-1 block w-full" :value="old('cover_url')" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('series.index') }}" class="text-sm font-semibold text-slate-600 hover:text-brand-blue">Cancelar</a>
                <x-gradient-button>Salvar</x-gradient-button>
            </div>
        </form>

        <form action="{{ route('seasons.sync', $series) }}" method="POST"
              x-data="seasonsManager({{ $series->seasons->map(fn ($s) => ['id' => $s->id, 'episodes' => $s->episodes_count])->values()->toJson() }})"
              class="mt-6 bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
            @csrf @method('PUT')

            <div>
                <h2 class="text-lg font-bold text-slate-900">Temporadas</h2>
                <p class="text-sm text-slate-500">Adicione ou remova temporadas e ajuste quantos episódios cada uma tem. Ao diminuir, os episódios de número mais alto são removidos.</p>
            </div>

            @if ($errors->seasons->any())
                <div class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->seasons->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-2">
                <template x-for="(season, index) in seasons" :key="index">
                    <div class="flex items-center gap-3 rounded-lg border border-slate-200 p-3">
                        <input type="hidden" :name="`seasons[${index}][id]`" :value="season.id">
                        <span class="font-semibold text-slate-700 w-28">Temporada <span x-text="index + 1"></span></span>
                        <div class="flex-1">
                            <label class="block text-xs text-slate-500 mb-1">Episódios</label>
                            <input type="number" min="1" max="500" x-model.number="season.episodes"
                                   :name="`seasons[${index}][episodes]`"
                                   class="block w-full rounded-lg border-slate-300 focus:border-brand-blue focus:ring-brand-blue">
                        </div>
                        <button type="button" @click="remove(index)" x-show="seasons.length > 1"
                                class="self-end rounded-lg border border-red-200 px-3 py-2 text-sm font-semibold text-red-600 hover:bg-red-50">
                            Remover
                        </button>
                    </div>
                </template>
            </div>

            <div class="flex items-center justify-between gap-3 pt-4 border-t border-slate-100">
                <button type="button" @click="add()"
                        class="rounded-lg border border-brand-mid/30 px-4 py-2 text-sm font-semibold text-brand-mid hover:bg-brand-gradient-soft">
                    + Adicionar temporada
                </button>
                <x-gradient-button>Salvar temporadas</x-gradient-button>
            </div>
        </form>
    </div>

    <script>
        function seasonsManager(initial) {
            return {
                seasons: initial.length ? initial : [{ id: null, episodes: 10 }],
                add() {
                    this.seasons.push({ id: null, episodes: 10 });
                },
                remove(index) {
                    if (! confirm(`Remover a Temporada ${index + 1}? Todos os episódios dela serão excluídos ao salvar.`)) {
                        return;
                    }
                    this.seasons.splice(index, 1);
                },
            }
        }
    </script>
</x-app-layout>
