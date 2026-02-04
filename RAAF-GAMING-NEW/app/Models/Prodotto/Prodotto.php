<?php

namespace App\Models\Prodotto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Magazzino\PresenteIn;

class Prodotto extends Model
{
    use HasFactory;

    /**
     * La tabella associata al model.
     *
     * @var string
     */
    protected $table = 'prodotto';

    /**
     * La chiave primaria della tabella.
     *
     * @var string
     */
    protected $primaryKey = 'codice_prodotto';

    /**
     * Indica se il model dovrebbe avere i timestamp automatici.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Gli attributi assegnabili in massa.
     *
     * @var array
     */
    protected $fillable = [
        'prezzo',
        'copertina',
        'sconto',
        'data_uscita',
        'nome',
        'quantita_fornitura',
        'ultima_fornitura',
        'fornitore',
        'gestore',
    ];

    /**
     * Gli attributi che devono essere castati a tipi nativi.
     *
     * @var array
     */
    protected $casts = [
        'prezzo' => 'double',
        'sconto' => 'decimal:0',
        'data_uscita' => 'date',
        'quantita_fornitura' => 'integer',
        'ultima_fornitura' => 'date',
    ];

    public function presenteIn()
    {
        return $this->hasMany(PresenteIn::class, 'magazzino', 'indirizzo');
    }
}