<?php

namespace App\Models\Prodotto;

use App\Models\Prodotto\ParteDi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    /** @use HasFactory<\Database\Factories\CategoriaFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $table = 'categoria';

    protected $primaryKey = 'nome';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nome',
    ];

    public function parteDi()
    {
        return $this->hasMany(ParteDi::class, 'categoria', 'nome');
    }
}