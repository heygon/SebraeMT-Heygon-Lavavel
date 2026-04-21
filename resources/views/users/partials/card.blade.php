@php
    $initials = collect(explode(' ', $user->name))
        ->filter()
        ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
        ->take(2)
        ->implode('');
    $sync = 40 + (($user->id * 17) % 60);
@endphp

<article class="group relative overflow-hidden rounded-2xl border border-cyan-500/10 bg-surface-container-high/40 p-6 shadow-2xl">
    <div class="absolute inset-y-0 right-0 w-24 bg-primary-container/10"></div>
    <div class="relative flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-5">
            <div class="hex-clip h-24 w-24 bg-surface-container-lowest p-[1px] shadow-[0_0_24px_rgba(0,241,254,0.12)]">
                @if ($user->avatar_url)
                    <div class="hex-clip h-full w-full overflow-hidden">
                        <img class="h-full w-full object-cover" src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
                    </div>
                @else
                    <div class="hex-clip flex h-full w-full items-center justify-center bg-gradient-to-br from-primary-container via-surface-container-highest to-surface-container-low">
                        <span class="font-headline text-2xl font-black text-secondary-container">{{ $initials }}</span>
                    </div>
                @endif
            </div>

            <div class="space-y-1">
                <p class="font-headline text-[10px] tracking-[0.35em] uppercase text-tertiary">
                    {{ $user->email_verified_at ? 'Usuário Elite' : 'Acesso Pendente' }}
                </p>
                <h3 class="font-headline text-2xl font-black tracking-tight text-on-surface">{{ $user->name }}</h3>
                <p class="text-sm text-on-surface-variant">{{ $user->email }}</p>
                <p class="text-xs uppercase tracking-[0.25em] text-outline">
                    {{ $user->authority_level ? ['civic' => 'Cívico', 'warrior' => 'Guerreiro', 'elder' => 'Ancião'][$user->authority_level] ?? str_replace('_', ' ', $user->authority_level) : 'Autoridade não definida' }}
                </p>
                @if ($user->summary)
                    <p class="max-w-xl text-sm text-on-surface-variant">{{ $user->summary }}</p>
                @endif
                <div class="flex items-center gap-3 pt-1">
                    <span class="h-2.5 w-2.5 rounded-full {{ $user->email_verified_at ? 'bg-secondary-container' : 'bg-outline' }}"></span>
                    <span class="text-[10px] uppercase tracking-[0.25em] text-outline">
                        Sincronização: {{ $sync }}%
                    </span>
                </div>
            </div>
        </div>

        <div class="flex flex-1 items-center justify-between gap-6 md:justify-end">
            <div class="w-full max-w-[180px]">
                <div class="h-1.5 rounded-full bg-surface-container-low overflow-hidden">
                    <div class="h-full rounded-full bg-secondary-container" style="width: {{ $sync }}%"></div>
                </div>
                <p class="mt-2 text-[10px] uppercase tracking-[0.3em] text-outline">
                    Criado em {{ optional($user->created_at)->format('d.m.Y') ?? '—' }}
                </p>
            </div>

            <a href="{{ route('users.show', $user) }}" class="font-headline text-sm font-bold uppercase tracking-[0.35em] text-tertiary transition-colors hover:text-secondary-container">
                Ver registro <span class="ml-2">›</span>
            </a>
        </div>
    </div>
</article>
