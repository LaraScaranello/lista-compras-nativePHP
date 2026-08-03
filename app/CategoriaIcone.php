<?php

namespace App;

enum CategoriaIcone: string
{
    case Casa = 'house';

    case Coracao = 'heart';

    case Trabalho = 'briefcase';

    case Livro = 'book-open';

    case Carrinho = 'shopping-cart';

    case Computador = 'laptop';

    case Localizacao = 'map-pin';

    case Carro = 'car';

    case Camera = 'camera';

    case Aviao = 'plane';

    case Dinheiro = 'wallet';

    case Controle = 'gamepad-2';

    public function blade(): string
    {
        return 'lucide-'.$this->value;
    }
}
