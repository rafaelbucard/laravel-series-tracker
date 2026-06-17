<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-brand-gradient">Nova série</h1>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <form action="{{ route('series.store') }}" method="POST" enctype="multipart/form-data"
              x-data="omdbSearch()"
              class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-6">
            @csrf

            @if ($omdbConfigured)
                <div class="rounded-lg border border-brand-mid/20 bg-brand-gradient-soft p-4">
                    <label class="block text-sm font-semibold text-brand-mid mb-2">Buscar no OMDB (opcional)</label>
                    <div class="flex gap-2">
                        <input type="text" x-model="query" placeholder="Digite o nome da série..."
                               class="flex-1 rounded-lg border-slate-300 focus:border-brand-blue focus:ring-brand-blue text-sm">
                        <button type="button" @click="search" :disabled="loading"
                                class="rounded-lg bg-brand-gradient px-4 py-2 text-sm font-semibold text-white shadow-brand hover:opacity-90 disabled:opacity-50">
                            <span x-show="!loading">Buscar</span>
                            <span x-show="loading">...</span>
                        </button>
                    </div>
                    <div x-show="results.length > 0" class="mt-3 grid grid-cols-2 sm:grid-cols-4 gap-2">
                        <template x-for="item in results" :key="item.imdb_id">
                            <button type="button" @click="pick(item)"
                                    class="rounded-lg border-2 border-transparent hover:border-brand-mid transition overflow-hidden text-left">
                                <template x-if="item.poster">
                                    <img :src="item.poster" :alt="item.title" class="w-full aspect-[2/3] object-cover">
                                </template>
                                <template x-if="!item.poster">
                                    <div class="w-full aspect-[2/3] bg-slate-100 flex items-center justify-center text-xs text-slate-400">Sem capa</div>
                                </template>
                                <p class="px-1 py-1 text-xs font-semibold truncate" x-text="item.title"></p>
                                <p class="px-1 pb-1 text-[10px] text-slate-500" x-text="item.year"></p>
                            </button>
                        </template>
                    </div>
                    <p x-show="searched && results.length === 0" class="mt-2 text-xs text-slate-500">Nenhum resultado.</p>
                </div>
            @else
                <div class="rounded-lg border border-amber-200 bg-amber-50 p-3 text-xs text-amber-800">
                    Configure <code class="font-mono">OMDB_API_KEY</code> no <code class="font-mono">.env</code> para habilitar busca automática de capas.
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <x-input-label for="name" :value="'Nome'" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" x-ref="nameInput" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="streaming_service_id" :value="'Serviço de streaming'" />
                    <select id="streaming_service_id" name="streaming_service_id"
                            class="mt-1 block w-full rounded-lg border-slate-300 focus:border-brand-blue focus:ring-brand-blue">
                        <option value="">— sem streaming —</option>
                        @foreach ($streamingServices as $svc)
                            <option value="{{ $svc->id }}" @selected(old('streaming_service_id') == $svc->id)>{{ $svc->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('streaming_service_id')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="year" :value="'Ano'" />
                    <x-text-input id="year" name="year" type="number" min="1900" max="2100" class="mt-1 block w-full" :value="old('year')" x-ref="yearInput" />
                    <x-input-error :messages="$errors->get('year')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="qt_seasons" :value="'Temporadas'" />
                    <x-text-input id="qt_seasons" name="qt_seasons" type="number" min="1" max="50" class="mt-1 block w-full" :value="old('qt_seasons', 1)" required />
                    <x-input-error :messages="$errors->get('qt_seasons')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="qt_episodes" :value="'Média de episódios por temporada'" />
                    <x-text-input id="qt_episodes" name="qt_episodes" type="number" min="1" max="500" class="mt-1 block w-full" :value="old('qt_episodes', 10)" required />
                    <p class="mt-1 text-xs text-slate-500">Aplicado a todas as temporadas. Você pode ajustar cada temporada depois, ao editar a série.</p>
                    <x-input-error :messages="$errors->get('qt_episodes')" class="mt-1" />
                </div>

                <input type="hidden" name="imdb_id" x-ref="imdbInput" value="{{ old('imdb_id') }}">

                <div class="md:col-span-2">
                    <x-input-label for="synopsis" :value="'Sinopse'" />
                    <textarea id="synopsis" name="synopsis" rows="3"
                              class="mt-1 block w-full rounded-lg border-slate-300 focus:border-brand-blue focus:ring-brand-blue">{{ old('synopsis') }}</textarea>
                    <x-input-error :messages="$errors->get('synopsis')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="cover_file" :value="'Capa (upload)'" />
                    <input id="cover_file" name="cover_file" type="file" accept="image/*"
                           class="mt-1 block w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-brand-gradient file:px-3 file:py-2 file:text-sm file:font-semibold file:text-white">
                    <x-input-error :messages="$errors->get('cover_file')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="cover_url" :value="'Ou URL da capa'" />
                    <x-text-input id="cover_url" name="cover_url" type="url" class="mt-1 block w-full" x-ref="coverUrlInput" :value="old('cover_url')" />
                    <x-input-error :messages="$errors->get('cover_url')" class="mt-1" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('series.index') }}" class="text-sm font-semibold text-slate-600 hover:text-brand-blue">Cancelar</a>
                <x-gradient-button>Criar série</x-gradient-button>
            </div>
        </form>
    </div>

    <script>
        function omdbSearch() {
            return {
                query: '',
                loading: false,
                searched: false,
                results: [],
                async search() {
                    if (!this.query.trim()) return;
                    this.loading = true;
                    try {
                        const res = await axios.get('{{ route('series.omdb.search') }}', { params: { q: this.query } });
                        this.results = res.data.results || [];
                        this.searched = true;
                    } finally {
                        this.loading = false;
                    }
                },
                pick(item) {
                    this.$refs.nameInput.value = item.title || '';
                    if (item.year) {
                        const y = ('' + item.year).match(/\d{4}/);
                        this.$refs.yearInput.value = y ? y[0] : '';
                    }
                    this.$refs.imdbInput.value = item.imdb_id || '';
                    this.$refs.coverUrlInput.value = item.poster || '';
                },
            }
        }
    </script>
</x-app-layout>
