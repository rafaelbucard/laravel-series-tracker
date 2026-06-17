<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-brand-gradient">Editar perfil</h1>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            @include('profile.partials.update-password-form')
        </div>

        <div class="bg-white rounded-xl border border-red-200 shadow-sm p-6">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
