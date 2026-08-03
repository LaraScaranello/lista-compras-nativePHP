<?php

use App\Models\Categoria;
use App\Models\Item;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public string $nome = '';
    public ?int $quantidade = null;
    public ?float $preco = null;
    public ?int $categoria_id = null;
    public ?int $filtroCategoria = null;

    #[Computed]
    public function categorias(): Collection
    {
        return Categoria::all();
    }

    #[Computed]
    public function itens(): Collection
    {
        return Item::query()->orderBy('created_at', 'desc')->get();
    }

    public function adicionarItem(): void
    {
        $this->validate([
            'nome' => 'required|string|max:255',
            'quantidade' => 'required|integer|min:1',
            'preco' => 'nullable|numeric|min:0',
            'categoria_id' => 'nullable|integer|exists:categoria,id'
        ]);

        Item::create([
            'nome' => $this->nome,
            'quantidade' => $this->quantidade,
            'preco' => $this->preco,
            'categoria_id' => $this->categoria_id,
        ]);

        $this->reset(['nome', 'quantidade', 'preco', 'categoria_id']);
    }

    #[Computed]
    public function totalGeral(): float
    {
        return $this->itens
            ->sum(fn($item) => ($item->preco ?? 0) * $item->quantidade);
    }

    public function toggleComprado(Item $item): void
    {
        $item->update(['comprado' => !$item->comprado]);
    }

    public function deletarItem(Item $item): void
    {
        $item->delete();
    }

    public function limparFiltro(): void
    {
        $this->filtroCategoria = null;
    }
};
?>

<div class="p-4">
    <h1 class="text-2xl font-bold mb-6">Lista de Compras</h1>

    <!-- Formulário -->
    <form wire:submit="adicionarItem" class="mb-6 space-y-4">
        <div>
            <input
                type="text"
                wire:model="nome"
                placeholder="Item"
                class="w-full px-4 py-2 border rounded-lg"
            >
            @error('nome') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex gap-4">
            <div class="flex-1">
                <input
                    type="number"
                    wire:model="quantidade"
                    placeholder="Quantidade"
                    min="1"
                    class="w-full px-4 py-2 border rounded-lg"
                >
                @error('quantidade') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="flex-1">
                <input
                    type="number"
                    wire:model="preco"
                    placeholder="Preço"
                    step="0.01"
                    class="w-full px-4 py-2 border rounded-lg"
                >
            </div>
        </div>

        <select wire:model="categoria_id" class="w-full px-4 py-2 border rounded-lg">
            <option value="">Sem categoria</option>
            @foreach($this->categorias as $categoria)
                <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
            @endforeach
        </select>

        <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg font-semibold">
            Adicionar Item
        </button>
    </form>

    <!-- Filtro por categoria -->
    <div class="mb-4 flex gap-2 flex-wrap">
        <button
            wire:click="limparFiltro"
            class="px-3 py-1 rounded-full text-sm {{ $this->filtroCategoria === null ? 'bg-blue-500 text-white' : 'bg-gray-200' }}"
        >
            Todas
        </button>
        @foreach($this->categorias as $categoria)
            <button
                wire:click="$set('filtroCategoria', {{ $categoria->id }})"
                class="px-3 py-1 rounded-full text-sm text-white"
                style="background-color: {{ $categoria->cor->hex() }}"
            >
                {{ $categoria->nome }}
            </button>
        @endforeach
    </div>

    <!-- Lista de itens -->
    <div class="space-y-2 mb-6">
        @forelse($this->itens as $item)
            <div class="flex items-center gap-3 p-3 bg-gray-100 rounded-lg">
                <input
                    type="checkbox"
                    wire:change="toggleComprado({{ $item->id }})"
                    {{ $item->comprado ? 'checked' : '' }}
                    class="w-5 h-5"
                >
                <div class="flex-1">
                    @if($item->categoria)
                        <span class="inline-block px-2 py-1 text-xs text-white rounded" style="background-color: {{ $item->categoria->cor->hex() }}">
                            {{ $item->categoria->nome }}
                        </span>
                    @endif
                    <p class="{{ $item->comprado ? 'line-through text-gray-500' : '' }}">
                        {{ $item->nome }}
                    </p>
                    <p class="text-sm text-gray-600">
                        Qtd: {{ $item->quantidade }}
                        @if($item->preco)
                            | R$ {{ number_format($item->preco, 2, ',', '.') }}
                        @endif
                    </p>
                </div>
                <button
                    wire:click="deletarItem({{ $item->id }})"
                    class="text-red-500 hover:text-red-700"
                >
                    ✕
                </button>
            </div>
        @empty
            <p class="text-center text-gray-500 py-8">Nenhum item na lista</p>
        @endforelse
    </div>

    <!-- Total -->
    <div class="bg-blue-50 p-4 rounded-lg">
        <div class="flex justify-between text-lg">
            <span>Total:</span>
            <strong>R$ {{ number_format($this->totalGeral, 2, ',', '.') }}</strong>
        </div>
    </div>
</div>
