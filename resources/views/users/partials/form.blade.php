@php
    $isEdit = isset($user) && $user->exists;
    $avatarUrl = $isEdit ? $user->avatar_url : null;
    $selectedAuthorityLevel = old('authority_level', $isEdit ? ($user->authority_level ?? 'warrior') : 'warrior');
    $initials = $isEdit
        ? collect(explode(' ', $user->name))->filter()->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))->take(2)->implode('')
        : 'UA';
    $identity = $isEdit
        ? 'U_ARCH_' . str_pad((string) $user->id, 4, '0', STR_PAD_LEFT)
        : 'U_ARCH_7721';
@endphp

<div class="relative px-4 pb-32 pt-8 max-w-4xl mx-auto">
    <div class="mb-12 flex items-center gap-4">
        <a href="{{ route('users.index') }}" class="flex items-center justify-center p-3 rounded-xl bg-surface-container-high border border-outline-variant hover:border-secondary-container transition-all group active:scale-95">
            <span class="material-symbols-outlined text-secondary-fixed-dim group-hover:translate-x-[-4px] transition-transform">keyboard_double_arrow_left</span>
        </a>
        <div>
            <p class="font-label text-secondary-fixed-dim text-[10px] tracking-widest uppercase">Protocolo do Sistema</p>
            <h2 class="text-2xl font-headline font-bold text-primary tracking-tight uppercase">
                {{ $isEdit ? 'Atualizar Cadastro' : 'Cadastro de Entidade' }}
            </h2>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        <div class="lg:col-span-4 flex flex-col items-center text-center">
            <div class="relative mb-8 group">
                <div class="absolute inset-0 bg-secondary-container opacity-20 blur-2xl rounded-full group-hover:opacity-40 transition-opacity"></div>
                <div id="avatar-container" class="relative w-48 h-48 hex-clip bg-surface-container-highest flex items-center justify-center p-1">
                    @if ($avatarUrl)
                        <div class="w-full h-full hex-clip overflow-hidden">
                            <img id="avatar-display" class="w-full h-full object-cover" src="{{ $avatarUrl }}" alt="{{ $user->name }}">
                        </div>
                    @else
                        <div id="avatar-placeholder" class="w-full h-full hex-clip bg-surface overflow-hidden flex items-center justify-center bg-gradient-to-br from-primary-container via-surface-container-highest to-surface-container-low">
                            <span class="font-headline text-4xl font-black text-secondary-container">{{ $initials }}</span>
                        </div>
                    @endif
                </div>
            </div>
            <input id="avatar-upload" form="user-form" class="hidden" name="avatar" type="file" accept="image/*"/>
            <label for="avatar-upload" class="mb-6 inline-flex cursor-pointer items-center gap-2 rounded-full bg-primary-container px-4 py-2 text-primary shadow-lg transition-transform hover:scale-105">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">photo_camera</span>
                <span class="font-label text-xs uppercase tracking-[0.2em]">Atualizar avatar</span>
            </label>
            @error('avatar')
                <p class="mb-4 text-xs text-error">{{ $message }}</p>
            @enderror
            <div class="space-y-2">
                <p class="font-label text-xs text-outline tracking-[0.2em] uppercase">Link de Identidade</p>
                <p class="font-headline text-lg font-bold text-on-surface">{{ $identity }}</p>
            </div>
        </div>

        <div class="lg:col-span-8 space-y-10">
            <form id="user-form" class="space-y-8" method="POST" action="{{ $isEdit ? route('users.update', $user) : route('users.store') }}" enctype="multipart/form-data">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <div class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-outline-variant pb-2">
                        <span class="material-symbols-outlined text-secondary-fixed-dim text-sm">fingerprint</span>
                        <h3 class="font-headline font-bold text-sm tracking-widest uppercase text-outline">Registro de membro da tribo</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="relative group">
                            <label class="block font-label text-[10px] tracking-widest uppercase text-outline group-focus-within:text-secondary-fixed-dim transition-colors mb-2">Nome completo</label>
                            <input class="w-full bg-transparent border-0 border-b border-outline-variant focus:border-secondary-container focus:ring-0 text-on-surface font-body transition-all px-0 pb-2 placeholder:text-surface-variant" name="name" placeholder="Digite o nome do usuário..." type="text" value="{{ old('name', $user->name ?? '') }}"/>
                            @error('name')
                                <p class="mt-2 text-xs text-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="relative group">
                            <label class="block font-label text-[10px] tracking-widest uppercase text-outline group-focus-within:text-secondary-fixed-dim transition-colors mb-2">E-mail da tribo</label>
                            <input class="w-full bg-transparent border-0 border-b border-outline-variant focus:border-secondary-container focus:ring-0 text-on-surface font-body transition-all px-0 pb-2 placeholder:text-surface-variant" name="email" placeholder="comunicacoes@wakanda.tech" type="email" value="{{ old('email', $user->email ?? '') }}"/>
                            @error('email')
                                <p class="mt-2 text-xs text-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="relative group md:col-span-2">
                            <label class="block font-label text-[10px] tracking-widest uppercase text-outline group-focus-within:text-secondary-fixed-dim transition-colors mb-2">
                                {{ $isEdit ? 'Redefinir senha' : 'Senha' }}
                            </label>
                            <input class="w-full bg-transparent border-0 border-b border-outline-variant focus:border-secondary-container focus:ring-0 text-on-surface font-body transition-all px-0 pb-2 placeholder:text-surface-variant" name="password" placeholder="{{ $isEdit ? 'Deixe em branco para manter a senha atual' : 'Crie uma senha segura' }}" type="password"/>
                            @error('password')
                                <p class="mt-2 text-xs text-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-outline-variant pb-2">
                        <span class="material-symbols-outlined text-secondary-fixed-dim text-sm">shield_person</span>
                        <h3 class="font-headline font-bold text-sm tracking-widest uppercase text-outline">Perfil de Acesso</h3>
                    </div>
                    <div class="space-y-4">
                        <label class="block font-label text-[10px] tracking-widest uppercase text-outline mb-4">Nível de Autoridade</label>
                        <div class="flex flex-wrap gap-3">
                            @foreach (['civic' => 'Cívico', 'warrior' => 'Guerreiro', 'elder' => 'Ancião'] as $levelValue => $levelLabel)
                                <label class="cursor-pointer group">
                                    <input class="hidden peer" name="authority_level" type="radio" value="{{ $levelValue }}" @checked($selectedAuthorityLevel === $levelValue)/>
                                    <div class="px-6 py-2 rounded-lg bg-surface-container-low border border-outline-variant peer-checked:border-secondary-container peer-checked:bg-secondary-container/10 transition-all flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-outline group-hover:bg-secondary-fixed-dim peer-checked:bg-secondary-container shadow-[0_0_8px_rgba(34,211,238,0.5)]"></div>
                                        <span class="font-label text-xs uppercase tracking-wider text-outline peer-checked:text-on-surface">{{ $levelLabel }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-outline-variant pb-2">
                        <span class="material-symbols-outlined text-secondary-fixed-dim text-sm">terminal</span>
                        <h3 class="font-headline font-bold text-sm tracking-widest uppercase text-outline">Descrição do membro da tribo</h3>
                    </div>
                    <div class="relative group">
                        <textarea class="w-full bg-surface-container-low/40 rounded-xl border border-outline-variant focus:border-secondary-container focus:ring-0 text-on-surface font-body transition-all p-4 placeholder:text-surface-variant" name="summary" placeholder="Digite um resumo do membro da tribo..." rows="4">{{ old('summary', $user->summary ?? '') }}</textarea>
                    </div>
                </div>

                <div class="pt-8 flex flex-col md:flex-row items-center justify-end gap-6">
                    <a href="{{ route('users.index') }}" class="font-label text-sm uppercase tracking-widest text-outline hover:text-on-surface transition-colors">
                        Descartar alterações
                    </a>
                    <button class="relative group p-[2px] transition-transform active:scale-95" type="submit">
                        <div class="absolute inset-0 bg-primary opacity-40 blur-xl group-hover:opacity-60 transition-opacity hex-clip"></div>
                        <div class="hex-clip bg-gradient-to-br from-primary via-primary-container to-background p-[1px]">
                            <div class="hex-clip bg-primary-container px-12 py-4 flex items-center gap-3">
                                <span class="material-symbols-outlined text-secondary-container" style="font-variation-settings: 'FILL' 1;">verified_user</span>
                                <span class="font-headline font-bold tracking-[0.2em] uppercase text-primary">{{ $isEdit ? 'Atualizar' : 'Autorizar' }}</span>
                            </div>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatarInput = document.getElementById('avatar-upload');
    const avatarContainer = document.getElementById('avatar-container');
    const avatarDisplay = document.getElementById('avatar-display');
    const avatarPlaceholder = document.getElementById('avatar-placeholder');

    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                
                reader.onload = function(event) {
                        // Remove o placeholder se existir
                    if (avatarPlaceholder) {
                        avatarPlaceholder.remove();
                    }

                    // Se a imagem do avatar já existir, atualize-a
                    if (avatarDisplay) {
                        avatarDisplay.src = event.target.result;
                    } else {
                        // Cria um novo elemento de imagem
                        const newImg = document.createElement('div');
                        newImg.className = 'w-full h-full hex-clip overflow-hidden';
                        newImg.innerHTML = `<img id="avatar-display" class="w-full h-full object-cover" src="${event.target.result}" alt="Prévia do avatar">`;
                        
                        // Limpa o contêiner e adiciona a nova imagem
                        avatarContainer.innerHTML = '';
                        avatarContainer.appendChild(newImg);
                    }
                };
                
                reader.readAsDataURL(file);
            }
        });

        // Opcional: aciona o campo de arquivo pela label
        const label = document.querySelector('label[for="avatar-upload"]');
        if (label) {
            label.addEventListener('click', function(e) {
                e.preventDefault();
                avatarInput.click();
            });
        }
    }
});
</script>
