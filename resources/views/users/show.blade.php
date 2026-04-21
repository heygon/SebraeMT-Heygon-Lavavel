@extends('layouts.app')

@section('title', $user->name)

@section('content')
@php
    $initials = collect(explode(' ', $user->name))
        ->filter()
        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
        ->take(2)
        ->implode('');
    $sync = 70 + (($user->id * 13) % 30);
    $identifier = 'WK-' . str_pad((string) $user->id, 3, '0', STR_PAD_LEFT) . '-DELTA';
    $authorityLevels = [
        'civic' => 'Cívico',
        'warrior' => 'Guerreiro',
        'elder' => 'Ancião',
    ];
@endphp

<div class="relative px-4 pb-32 pt-8 max-w-5xl mx-auto">
    <div class="fixed inset-0 pointer-events-none bg-gradient-to-b from-primary-container/20 via-background to-surface-container-lowest z-[-1]"></div>

    <section class="mb-16 flex flex-col items-center">
        <div class="relative w-64 h-64 md:w-80 md:h-80 flex items-center justify-center">
            <div class="absolute inset-0 border-[2px] border-secondary-container/20 hex-clip"></div>
            <div class="absolute inset-4 border border-primary/30 hex-clip rotate-12"></div>
            <div class="relative w-[85%] h-[85%] bg-surface-container-highest hex-clip overflow-hidden shadow-[0_0_50px_rgba(34,211,238,0.2)]">
                @if ($user->avatar_url)
                    <img class="w-full h-full object-cover" src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-container via-surface-container-high to-surface-container-low">
                        <span class="font-headline text-5xl font-black text-secondary-container">{{ $initials }}</span>
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-secondary-container/10 to-transparent pointer-events-none"></div>
            </div>
            <div class="absolute -top-4 -right-4 bg-surface-container-high px-4 py-2 rounded-xl border border-secondary/20 shadow-xl backdrop-blur-md">
                <p class="font-headline text-[10px] tracking-tighter text-secondary-fixed-dim uppercase">Status</p>
                <p class="font-headline text-sm font-bold text-secondary-container">
                    {{ $user->email_verified_at ? 'LINK_ATIVO' : 'LINK_PENDENTE' }}
                </p>
            </div>
        </div>

        <div class="mt-8 text-center">
            <h2 class="font-headline text-4xl md:text-6xl font-black tracking-tighter text-on-surface mb-2">{{ strtoupper($user->name) }}</h2>
            <div class="flex items-center justify-center gap-3">
                <span class="w-2 h-2 rounded-full bg-secondary-container animate-ping"></span>
                <p class="font-label text-zinc-500 tracking-[0.3em] text-xs uppercase">Usuário Elite • Arquivo de Usuários</p>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="md:col-span-2 bg-surface-container-high/40 backdrop-blur-xl p-8 rounded-xl shadow-2xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
            <h3 class="font-headline text-zinc-500 text-xs tracking-widest uppercase mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-sm">fingerprint</span>
                Núcleo de Identidade
            </h3>
            <div class="grid grid-cols-2 gap-8">
                <div>
                    <p class="text-zinc-500 text-[10px] font-headline uppercase tracking-widest mb-1">ID do Arquivo</p>
                    <p class="font-headline text-lg font-bold text-on-surface">{{ $identifier }}</p>
                </div>
                <div>
                    <p class="text-zinc-500 text-[10px] font-headline uppercase tracking-widest mb-1">Nível de Acesso</p>
                    <p class="font-headline text-lg font-bold text-tertiary">
                        {{ $user->authority_level ? mb_strtoupper($authorityLevels[$user->authority_level] ?? str_replace('_', ' ', $user->authority_level)) : 'REVISÃO NECESSÁRIA' }}
                    </p>
                </div>
                <div>
                    <p class="text-zinc-500 text-[10px] font-headline uppercase tracking-widest mb-1">Criado em</p>
                    <p class="font-headline text-lg font-bold text-on-surface">{{ optional($user->created_at)->format('d.m.Y') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-zinc-500 text-[10px] font-headline uppercase tracking-widest mb-1">Última sincronização</p>
                    <p class="font-headline text-lg font-bold text-on-surface">{{ optional($user->updated_at)->diffForHumans() ?? '—' }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-zinc-500 text-[10px] font-headline uppercase tracking-widest mb-1">Resumo do arquivo</p>
                    <p class="text-sm leading-relaxed text-on-surface-variant">{{ $user->summary ?? 'Nenhum resumo informado.' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-surface-container-low/60 backdrop-blur-md p-8 rounded-xl border border-secondary/10 flex flex-col justify-between">
            <div>
                <h3 class="font-headline text-zinc-500 text-xs tracking-widest uppercase mb-4">Link neural</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-end">
                        <span class="text-xs text-zinc-400">Estabilidade da sincronização</span>
                        <span class="text-xs font-headline text-secondary-container">{{ $sync }}%</span>
                    </div>
                    <div class="w-full h-1 bg-zinc-800 rounded-full overflow-hidden">
                        <div class="h-full bg-secondary-container shadow-[0_0_10px_rgba(0,241,254,0.5)]" style="width: {{ $sync }}%"></div>
                    </div>
                </div>
            </div>
            <div class="mt-8">
                <p class="text-xs text-zinc-500 leading-relaxed italic">
                    "A lealdade ao arquivo está codificada em cada registro de usuário."
                </p>
            </div>
        </div>

        <div class="md:col-span-3 bg-surface-container-high/20 backdrop-blur-lg rounded-xl overflow-hidden border border-white/5">
            <div class="px-8 py-4 bg-white/5 flex justify-between items-center">
                <h3 class="font-headline text-xs tracking-widest text-zinc-400 uppercase">Registros de acesso</h3>
                <span class="material-symbols-outlined text-zinc-600">history</span>
            </div>
            <div class="p-4 overflow-x-auto">
                <table class="w-full text-left font-body text-sm">
                    <thead>
                    <tr class="text-zinc-500 border-b border-white/5">
                        <th class="pb-4 font-medium uppercase tracking-tighter text-[10px]">Vetor</th>
                        <th class="pb-4 font-medium uppercase tracking-tighter text-[10px]">Operação</th>
                        <th class="pb-4 font-medium uppercase tracking-tighter text-[10px]">Data</th>
                        <th class="pb-4 font-medium uppercase tracking-tighter text-[10px] text-right">Métrica</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                    <tr class="hover:bg-white/5 transition-colors group">
                        <td class="py-4 font-headline text-on-surface">USERS_CORE</td>
                        <td class="py-4 text-zinc-400">Síntese de perfil</td>
                        <td class="py-4 text-zinc-500">{{ optional($user->created_at)->format('Y.m.d') ?? '—' }}</td>
                        <td class="py-4 text-right font-headline text-secondary-container">OK</td>
                    </tr>
                    <tr class="hover:bg-white/5 transition-colors group">
                        <td class="py-4 font-headline text-on-surface">AUDIT_TRAIL</td>
                        <td class="py-4 text-zinc-400">Reindexação de dados</td>
                        <td class="py-4 text-zinc-500">{{ optional($user->updated_at)->format('Y.m.d') ?? '—' }}</td>
                        <td class="py-4 text-right font-headline text-secondary-container">OK</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="flex flex-col md:flex-row gap-6 items-center justify-center">
        <a href="{{ route('users.edit', $user) }}" class="group relative px-12 py-4 bg-primary-container rounded-lg transition-all active:scale-95 shadow-[0_0_20px_rgba(45,27,77,0.5)] overflow-hidden">
            <span class="relative z-10 flex items-center gap-3 font-headline font-bold text-on-primary-container tracking-widest uppercase">
                <span class="material-symbols-outlined text-lg">edit_square</span>
                Editar
            </span>
        </a>

        <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Arquivar este usuário?')" class="w-full md:w-auto">
            @csrf
            @method('DELETE')
            <button type="submit" class="group relative px-12 py-4 bg-surface-container-highest/20 rounded-lg border border-error-container/40 transition-all active:scale-95 hover:bg-error-container/10 overflow-hidden w-full">
                <span class="relative z-10 flex items-center gap-3 font-headline font-bold text-error tracking-widest uppercase">
                    <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">warning</span>
                    Desativar
                </span>
            </button>
        </form>
    </div>
</div>
@endsection
