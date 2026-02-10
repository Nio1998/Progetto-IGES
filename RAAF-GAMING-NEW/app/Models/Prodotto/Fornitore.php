<?php

namespace App\Models\Prodotto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Prodotto\Prodotto;

class Fornitore extends Model
{
    use HasFactory;

    /**
     * La tabella associata al model.
     * @var string
     */
    protected $table = 'fornitore';

    /**
     * La chiave primaria della tabella è il nome (stringa).
     * @var string
     */
    protected $primaryKey = 'nome';

    /**
     * Poiché la chiave primaria è una stringa, disabilitiamo l'auto-incremento.
     * @var bool
     */
    public $incrementing = false;

    /**
     * Il tipo di dati della chiave primaria.
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Disattiviamo i timestamp poiché non presenti nel file SQL.
     * @var bool
     */
    public $timestamps = false;

    /**
     * Gli attributi assegnabili in massa (presi dal tuo FornitoreBean).
     * @var array
     */
    protected $fillable = [
        'nome',
        'indirizzo',
        'telefono',
    ];

    // Relationships

    /**
     * Un fornitore fornisce molti prodotti.
     */
    public function prodotti()
    {
        return $this->hasMany(Prodotto::class, 'fornitore', 'nome');
    }
}