<?php

namespace App\Models;

use App\CategoriaCor;
use App\CategoriaIcone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    protected $fillable = [
        'nome',
        'cor',
        'icone',
    ];

    protected $casts = [
        'cor' => CategoriaCor::class,
        'icone' => CategoriaIcone::class,
    ];

    public function itens(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
