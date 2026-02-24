<?php

namespace App\Models\Acquisto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Acquisto\Ordine;
use App\Models\Prodotto\Prodotto;

class Riguarda extends Model
{
    use HasFactory;

    protected $table = 'riguarda';
    public $timestamps = false;

    protected $fillable = [
        'prodotto',
        'ordine',
        'quantita_acquistata',
    ];

    protected $casts = [
        'prodotto' => 'integer',
        'quantita_acquistata' => 'integer',
    ];



    public function getOrdine()
    {
        return $this->belongsTo(Ordine::class, 'ordine', 'codice');
    }

    public function getProdotto()
    {
        return $this->belongsTo(Prodotto::class, 'prodotto', 'codice_prodotto');
    }
}