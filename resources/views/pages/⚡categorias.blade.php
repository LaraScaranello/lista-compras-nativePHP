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
    <h1 class="titulo">Categorias</h1>

    <div class="categorias-lista">
        @foreach($this->categorias as $categoria)
            <button class="categoria">
                <div
                    class="categoria-cor"
                    style="background-color: {{ $categoria->cor->hex() }}">
                </div>
                <span>
                    {{ $categoria->nome }}
                </span>
            </button>
        @endforeach
    </div>

    <button class="fab" wire:click="abrirModal">
        +
    </button>

    @if($mostrarModal)
        <div class="sheet-backdrop" wire:click="fecharModal">
            <div class="sheet" wire:click.stop>
                <div class="form-group">
                    <label class="form-label">
                        Nome
                    </label>
                    <input class="form-control" wire:model.live="nome">
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

<style>
    .categorias {
        padding: 24px;
    }

    .titulo {
        font-size: 34px;
        margin-bottom: 28px;
    }

    .categorias-lista {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .categoria {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 18px;
        border: none;
        border-radius: var(--radius);
        background: var(--surface);
        color: var(--text);
        cursor:pointer;
    }

    .categoria-cor {
        width: 18px;
        height: 18px;
        border-radius: 50%;
    }

    .fab {
        position: fixed;
        right: 24px;
        bottom: 24px;
        width: 64px;
        height: 64px;
        border: none;
        border-radius: 50%;
        background: var(--color-primary);
        color: white;
        font-size: 32px;
        cursor: pointer;
        box-shadow: var(--shadow);
    }
</style>
