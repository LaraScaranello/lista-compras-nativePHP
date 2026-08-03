<?php

namespace App;

enum CategoriaCor: string
{
    case Verde = '#63C132';
    case Vermelho = '#F34563';
    case Amarelo = '#FFD84C';
    case Laranja = '#FF8C24';
    case Azul = '#5C98FF';
    case Roxo = '#A56BFF';
    case Ciano = '#31D2C5';
    case Preto = '#2F2F2F';
    case Branco = '#FFFFFF';

    public function hex(): string
    {
        return match ($this) {
            self::Verde => '#63C132',
            self::Vermelho => '#F34563',
            self::Amarelo => '#FFD84C',
            self::Laranja => '#FF8C24',
            self::Azul => '#5C98FF',
            self::Roxo => '#A56BFF',
            self::Ciano => '#31D2C5',
            self::Preto => '#2F2F2F',
            self::Branco => '#FFFFFF',
        };
    }
}
