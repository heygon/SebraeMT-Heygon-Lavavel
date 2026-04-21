@extends('layouts.app')

@section('title', 'Entrar')

@section('content')
<div class="mx-auto flex min-h-[calc(100vh-5rem)] max-w-3xl items-center px-4 py-12">
    <div class="w-full rounded-3xl border border-cyan-500/10 bg-surface-container-high/50 p-8 shadow-2xl backdrop-blur-xl md:p-12">
        <div class="mb-8">
            <p class="font-label text-xs uppercase tracking-[0.35em] text-tertiary">Acesso restrito</p>
            <h2 class="mt-2 font-headline text-4xl font-black tracking-tight text-on-surface">Entrar no painel</h2>
            <p class="mt-3 max-w-xl text-sm leading-relaxed text-on-surface-variant">
                Use o e-mail e a senha cadastrados para acessar as demais telas do sistema.
            </p>
        </div>

        <form method="POST" action="{{ route('login.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="mb-2 block font-label text-[10px] uppercase tracking-widest text-outline">E-mail</label>
                    <input
                        class="w-full rounded-xl border border-outline-variant bg-transparent px-4 py-3 text-on-surface placeholder:text-surface-variant focus:border-secondary-container focus:ring-0"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        placeholder="voce@exemplo.com"
                        required
                    />
                    @error('email')
                        <p class="mt-2 text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block font-label text-[10px] uppercase tracking-widest text-outline">Senha</label>
                    <input
                        class="w-full rounded-xl border border-outline-variant bg-transparent px-4 py-3 text-on-surface placeholder:text-surface-variant focus:border-secondary-container focus:ring-0"
                        name="password"
                        type="password"
                        placeholder="Sua senha"
                        required
                    />
                    @error('password')
                        <p class="mt-2 text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <a class="font-label text-sm uppercase tracking-widest text-outline transition-colors hover:text-on-surface" href="{{ route('users.create') }}">
                    Não tem conta? Cadastrar
                </a>

                <button class="rounded-full bg-primary-container px-8 py-3 font-headline text-sm font-bold uppercase tracking-[0.2em] text-primary transition-transform active:scale-95 hover:bg-primary hover:text-on-primary" type="submit">
                    Entrar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
