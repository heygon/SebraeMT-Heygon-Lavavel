@extends('layouts.app')

@section('title', 'Usuários Ativos')

@section('content')
<div class="relative px-4 pb-32 pt-8 max-w-5xl mx-auto">
    <div class="fixed inset-0 pointer-events-none bg-gradient-to-b from-primary-container/20 via-background to-surface-container-lowest z-[-1]"></div>

    <section class="mb-12">
        <p class="font-label text-tertiary tracking-[0.35em] uppercase text-[10px] mb-3">Protocolo Vibranium v4.0</p>
        <h2 class="font-headline text-4xl md:text-6xl font-black tracking-tighter text-on-surface mb-4">Usuários Ativos</h2>
        <p class="max-w-2xl text-lg text-on-surface-variant leading-relaxed">
            Acessando o arquivo biométrico ancestral. Filtrando assinaturas digitais pela rede da Cidade Dourada.
        </p>
    </section>

    <form method="GET" action="{{ route('users.index') }}" class="mb-10">
        <label class="block">
            <span class="sr-only">Escanear identificação</span>
            <div class="flex items-center gap-4 border-b border-outline-variant pb-3">
                <input
                    class="w-full bg-transparent border-0 p-0 text-lg tracking-[0.25em] uppercase text-on-surface placeholder:text-outline focus:ring-0"
                    name="search"
                    placeholder="Escanear identificação..."
                    type="search"
                    value="{{ request('search') }}"
                />
                <button class="text-secondary-fixed-dim" type="submit">
                    <span class="material-symbols-outlined text-2xl">search</span>
                </button>
            </div>
        </label>
    </form>

    <div class="space-y-6">
        @forelse ($users as $user)
            @include('users.partials.card', ['user' => $user])
        @empty
            <div class="rounded-2xl bg-surface-container-high/40 p-8 text-on-surface-variant shadow-2xl">
                Ainda não há usuários arquivados.
            </div>
        @endforelse
    </div>

    <a href="{{ route('users.create') }}" class="fixed bottom-24 right-6 z-40 flex h-16 w-16 items-center justify-center rounded-2xl border border-secondary-container bg-surface-container-highest text-secondary-container shadow-[0_0_30px_rgba(0,241,254,0.25)] transition-transform active:scale-95">
        <span class="material-symbols-outlined text-3xl">add</span>
    </a>
</div>
@endsection
