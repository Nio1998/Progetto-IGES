<?php

namespace App\Models\Acquisto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Acquisto\Spedito;

class CorriereEspresso extends Model
{
    use HasFactory;

    protected $table = 'corriere_espresso';
    protected $primaryKey = 'nome';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'nome',
        'sito',
    ];

    // --- Relationships ---

    public function getspedito()
    {
        return $this->hasMany(Spedito::class, 'corriere_espresso', 'nome');
    }
}