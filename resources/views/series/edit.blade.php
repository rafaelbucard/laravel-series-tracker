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
    </div>
</x-app-layout>
