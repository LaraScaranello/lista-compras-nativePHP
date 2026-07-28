<?php

use App\Models\Item;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public string $nome = '';
    public ?int $quantidade = null;
    public ?float $preco = null;

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
        ]);

        Item::create([
            'nome' => $this->nome,
            'quantidade' => $this->quantidade,
            'preco' => $this->preco,
        ]);

        $this->reset(['nome', 'quantidade', 'preco']);
    }

    public function toggleComprado(Item $item): void
    {
        $item->update(['comprado' => !$item->comprado]);
    }

    public function deletarItem(Item $item): void
    {
        $item->delete();
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
                    placeholder="Preço (opcional)"
                    step="0.01"
                    class="w-full px-4 py-2 border rounded-lg"
                >
            </div>
        </div>

        <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg font-semibold">
            Adicionar Item
        </button>
    </form>

    <!-- Lista de itens -->
    <div class="space-y-2">
        @forelse($this->itens as $item)
            <div class="flex items-center gap-3 p-3 bg-gray-100 rounded-lg">
                <input
                    type="checkbox"
                    wire:change="toggleComprado({{ $item->id }})"
                    {{ $item->comprado ? 'checked' : '' }}
                    class="w-5 h-5"
                >
                <div class="flex-1">
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
</div>
