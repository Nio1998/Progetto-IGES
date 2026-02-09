<?php

namespace App\Models\Prodotto;

use App\Models\Prodotto\Prodotto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abbonamento extends Model
{
    /** @use HasFactory<\Database\Factories\AbbonamentoFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $table = 'abbonamento';

    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'codice',
        'prodotto',
        'durata_abbonamento',
    ];

    protected $casts = [
        'durata_abbonamento' => 'integer',
        'prodotto' => 'integer',
    ];

    // Relationship

    public function prodotto()
    {
        return $this->belongsTo(Prodotto::class, 'prodotto', 'codice_prodotto');
    }
}