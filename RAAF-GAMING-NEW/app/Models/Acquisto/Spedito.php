<?php

namespace App\Models\Acquisto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Acquisto\Ordine;
use App\Models\Acquisto\CorriereEspresso;

class Spedito extends Model
{
    use HasFactory;

    protected $table = 'spedito';
    public $timestamps = false;

    protected $fillable = [
        'ordine',
        'corriere_espresso',
        'data_consegna',
    ];

    protected $casts = [
        'data_consegna' => 'date',
    ];

    // --- Relationships ---

    public function getOrdine()
    {
        return $this->belongsTo(Ordine::class, 'ordine', 'codice');
    }

    public function getCorriereEspresso()
    {
        return $this->belongsTo(CorriereEspresso::class, 'corriere_espresso', 'nome');
    }
}