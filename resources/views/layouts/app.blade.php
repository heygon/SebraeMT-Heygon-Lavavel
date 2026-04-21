<!DOCTYPE html>
<html class="dark" lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Arquivo de Usuários')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        background: "#131313",
                        "on-background": "#e5e2e1",
                        surface: "#131313",
                        "surface-dim": "#131313",
                        "surface-bright": "#3a3939",
                        "surface-variant": "#353534",
                        "surface-container-lowest": "#0e0e0e",
                        "surface-container-low": "#1c1b1b",
                        "surface-container": "#201f1f",
                        "surface-container-high": "#2a2a2a",
                        "surface-container-highest": "#353534",
                        primary: "#d3bcf9",
                        "primary-container": "#2d1b4d",
                        "primary-fixed": "#ebdcff",
                        "primary-fixed-dim": "#d3bcf9",
                        "on-primary": "#382759",
                        "on-primary-container": "#9783bc",
                        "on-primary-fixed": "#231043",
                        "on-primary-fixed-variant": "#4f3d71",
                        secondary: "#ddfcff",
                        "secondary-container": "#00f1fe",
                        "secondary-fixed": "#74f5ff",
                        "secondary-fixed-dim": "#00dbe7",
                        "on-secondary": "#00363a",
                        "on-secondary-container": "#006a70",
                        "on-secondary-fixed": "#002022",
                        "on-secondary-fixed-variant": "#004f54",
                        "on-surface": "#e5e2e1",
                        "on-surface-variant": "#cbc4cf",
                        tertiary: "#e9c349",
                        "tertiary-container": "#cca830",
                        "tertiary-fixed": "#ffe088",
                        "tertiary-fixed-dim": "#e9c349",
                        "on-tertiary": "#3c2f00",
                        "on-tertiary-container": "#4f3e00",
                        "on-tertiary-fixed": "#241a00",
                        "on-tertiary-fixed-variant": "#574500",
                        error: "#ffb4ab",
                        "error-container": "#93000a",
                        "on-error": "#690005",
                        "on-error-container": "#ffdad6",
                        outline: "#948e99",
                        "outline-variant": "#49454e",
                        "inverse-on-surface": "#313030",
                        "inverse-surface": "#e5e2e1",
                        "inverse-primary": "#68558a"
                    },
                    borderRadius: {
                        DEFAULT: "0.125rem",
                        lg: "0.25rem",
                        xl: "0.5rem",
                        full: "0.75rem"
                    },
                    fontFamily: {
                        headline: ["Space Grotesk"],
                        body: ["Manrope"],
                        label: ["Space Grotesk"]
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700;900&family=Manrope:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .hex-clip {
            clip-path: polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%);
        }

        .energy-grid {
            background-image:
                radial-gradient(circle at 50% 50%, rgba(45, 27, 77, 0.15) 0%, transparent 80%),
                linear-gradient(rgba(34, 211, 238, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(34, 211, 238, 0.03) 1px, transparent 1px);
            background-size: 100% 100%, 40px 40px, 40px 40px;
        }

        body {
            min-height: max(884px, 100dvh);
        }
    </style>
    @stack('head')
</head>
<body class="bg-surface text-on-surface font-body selection:bg-secondary-container selection:text-on-secondary-container min-h-screen overflow-x-hidden energy-grid">
<header class="bg-zinc-950/80 backdrop-blur-2xl text-violet-300 dark:text-violet-200 sticky top-0 z-50 border-b border-cyan-500/10 shadow-[0_20px_50px_rgba(0,0,0,0.5)]">
    <div class="flex items-center justify-between px-6 py-4 w-full max-w-none">
        <div class="flex items-center gap-4">
            <button class="active:scale-95 transition-transform hover:text-cyan-300 transition-all duration-300" type="button">
                <span class="material-symbols-outlined">menu</span>
            </button>
            <h1 class="font-headline tracking-[0.2em] font-black text-xl text-violet-300 dark:text-cyan-400">ARQUIVO DE USUÁRIOS</h1>
        </div>

        <div class="flex items-center gap-6">
            <nav class="hidden md:flex gap-8 font-headline uppercase font-bold text-sm tracking-widest">
                <a class="{{ request()->routeIs('users.index') ? 'text-cyan-400' : 'text-zinc-500 hover:text-cyan-300' }} transition-all" href="{{ route('users.index') }}">LISTA</a>
                <a class="{{ request()->routeIs('users.create') ? 'text-cyan-400' : 'text-zinc-500 hover:text-cyan-300' }} transition-all" href="{{ route('users.create') }}">CADASTRAR</a>
                <a class="{{ request()->routeIs('users.show') || request()->routeIs('users.edit') ? 'text-cyan-400' : 'text-zinc-500 hover:text-cyan-300' }} transition-all" href="{{ route('users.index') }}">PERFIL</a>
            </nav>
            <div class="w-10 h-10 rounded-full border-2 border-primary-container overflow-hidden flex items-center justify-center bg-surface-container-highest">
                <span class="font-headline text-xs tracking-[0.2em] text-primary">UA</span>
            </div>
        </div>
    </div>
</header>

@if (session('status'))
    <div class="mx-auto max-w-6xl px-6 pt-6">
        <div class="rounded-xl bg-surface-container-high/70 px-4 py-3 text-sm text-secondary-fixed-dim shadow-lg">
            {{ session('status') }}
        </div>
    </div>
@endif

<main>
    @yield('content')
</main>

<nav class="md:hidden fixed bottom-0 left-0 w-full flex justify-around items-center px-4 pb-8 pt-4 bg-[#131313]/90 backdrop-blur-3xl border-t border-violet-500/20 shadow-[0_-15px_40px_rgba(45,27,77,0.4)] z-50">
    <a class="flex flex-col items-center justify-center {{ request()->routeIs('users.index') ? 'bg-violet-900/30 text-cyan-300 rounded-2xl px-5 py-2 ring-1 ring-cyan-500/30 shadow-[0_0_20px_rgba(34,211,238,0.2)]' : 'text-zinc-500 hover:text-zinc-200' }} transition-all duration-200" href="{{ route('users.index') }}">
        <span class="material-symbols-outlined">groups</span>
        <span class="font-headline text-[10px] tracking-[0.1em] font-medium uppercase mt-1">LISTA</span>
    </a>
    <a class="flex flex-col items-center justify-center {{ request()->routeIs('users.create') ? 'bg-violet-900/30 text-cyan-300 rounded-2xl px-5 py-2 ring-1 ring-cyan-500/30 shadow-[0_0_20px_rgba(34,211,238,0.2)]' : 'text-zinc-500 hover:text-zinc-200' }} transition-all duration-200" href="{{ route('users.create') }}">
        <span class="material-symbols-outlined">add_circle</span>
        <span class="font-headline text-[10px] tracking-[0.1em] font-medium uppercase mt-1">CADASTRAR</span>
    </a>
    <a class="flex flex-col items-center justify-center {{ request()->routeIs('users.show') || request()->routeIs('users.edit') ? 'bg-violet-900/30 text-cyan-300 rounded-2xl px-5 py-2 ring-1 ring-cyan-500/30 shadow-[0_0_20px_rgba(34,211,238,0.2)]' : 'text-zinc-500 hover:text-zinc-200' }} transition-all duration-200" href="{{ route('users.index') }}">
        <span class="material-symbols-outlined" style="font-variation-settings: {{ request()->routeIs('users.show') || request()->routeIs('users.edit') ? "'FILL' 1" : "'FILL' 0" }};">account_circle</span>
        <span class="font-headline text-[10px] tracking-[0.1em] font-medium uppercase mt-1">PERFIL</span>
    </a>
</nav>
</body>
</html>
