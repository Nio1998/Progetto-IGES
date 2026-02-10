<?php

namespace App\Models\Prodotto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Profilo\Cliente;
use App\Models\Prodotto\Prodotto;

class Recensisce extends Model
{
    use HasFactory;

    /**
     * La tabella associata al model.
     * * @var string
     */
    protected $table = 'recensisce';

    /**
     * Essendo una tabella pivot con chiave composta (cliente, prodotto),
     * Eloquent non gestisce una singola primaryKey di default.
     */
    protected $primaryKey = null;
    public $incrementing = false;

    /**
     * Indica se il model dovrebbe avere i timestamp automatici.
     * Nel tuo SQL non erano presenti.
     */
    public $timestamps = false;

    /**
     * Gli attributi assegnabili in massa (presi dal tuo Bean).
     * * @var array
     */
    protected $fillable = [
        'cliente',
        'prodotto',
        'voto',
        'commento',
    ];

    /**
     * Cast dei tipi (voto è smallint nell'SQL).
     */
    protected $casts = [
        'prodotto' => 'integer',
        'voto' => 'integer',
    ];

    // Relationships

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente', 'email');
    }

    public function prodotto()
    {
        return $this->belongsTo(Prodotto::class, 'prodotto', 'codice_prodotto');
    }
}