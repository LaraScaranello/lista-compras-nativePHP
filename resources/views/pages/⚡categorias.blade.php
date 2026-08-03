<?php

use App\CategoriaCor;
use App\CategoriaIcone;
use App\Models\Categoria;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public string $nome = '';
    public string $cor = '';
    public string $icone = '';

    public bool $mostrarModal = false;

    public function getCoresProperty(): array
    {
        return CategoriaCor::cases();
    }

    public function getIconesProperty(): array
    {
        return CategoriaIcone::cases();
    }

    #[Computed]
    public function categorias(): Collection
    {
        return Categoria::query()->orderBy('nome')->get();
    }

    public function adicionarCategoria(): void
    {
        $this->validate([
            'nome' => 'required|string|max:255',
            'cor' => 'required|string|max:255',
            'icone' => 'required|string|max:255'
        ]);

        Categoria::create([
            'nome' => $this->nome,
            'cor' => $this->cor,
            'icone' => $this->icone
        ]);

        $this->fecharModal();
    }

    public function deletarCategoria(Categoria $categoria): void
    {
        $categoria->delete();
    }

    public function abrirModal(): void
    {
        $this->mostrarModal = true;
    }

    public function fecharModal(): void
    {
        $this->mostrarModal = false;

        $this->reset([
            'nome',
            'icone',
            'cor'
        ]);
    }
};
?>

<div class="categorias">
    <div class="header">
        <div>
            <p class="header-subtitle">
                Organize suas tarefas
            </p>

            <h1 class="title">
                Categorias
            </h1>
        </div>

        <span class="badge">
            {{ $this->categorias->count() }}
        </span>
    </div>

    <div class="categorias-lista">
        @foreach($this->categorias as $categoria)
            <button class="categoria">
                <div class="categoria-icone"
                     style="background:{{ $categoria->cor->hex() }}20;color:{{ $categoria->cor->hex() }}">

                    <x-dynamic-component :component="$categoria->icone->blade()" class="w-5 h-5"/>
                </div>
                <div class="categoria-content">
                    <span class="categoria-nome">
                        {{ $categoria->nome }}
                    </span>
                </div>

                <div class="categoria-right">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </div>
            </button>
        @endforeach
    </div>

    <button class="fab" wire:click="abrirModal">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 5v14"/>
            <path d="M5 12h14"/>
        </svg>
    </button>

    @if($mostrarModal)
        <div class="sheet-backdrop" wire:click="fecharModal">
            <div class="sheet" wire:click.stop>
                <div class="sheet-header">
                    <div class="sheet-handle"></div>
                    <h2>Nova categoria</h2>
                    <p>Organize suas tarefas com cores e ícones.</p>
                </div>
                <div class="form-group">
                    <label class="form-label">
                        Nome
                    </label>
                    <input class="form-control" placeholder="Ex.: Trabalho" wire:model.live="nome">
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Ícone
                    </label>
                    <div class="icon-picker">
                        @foreach($this->icones as $icone)
                            <button
                                type="button"
                                class="{{ $this->icone === $icone->value ? 'active' : '' }}"
                                wire:click="$set('icone','{{ $icone->value }}')">

                                <x-dynamic-component
                                    :component="$icone->blade()"
                                    class="w-5 h-5"/>
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Cor
                    </label>
                    <div class="color-picker">
                        @foreach($this->cores as $cor)
                            <button
                                type="button"
                                class="{{ $this->cor === $cor->value ? 'active' : '' }}"
                                style="background: {{ $cor->hex() }}"
                                wire:click="$set('cor','{{ $cor->value }}')">
                            </button>
                        @endforeach
                    </div>
                </div>

                <button
                    class="btn-primary"
                    wire:click="adicionarCategoria">
                    Adicionar Categoria
                </button>
            </div>
        </div>
    @endif
</div>
