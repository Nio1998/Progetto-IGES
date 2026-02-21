<?php

namespace App\Models\Prodotto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Magazzino\PresenteIn;
use App\Models\Prodotto\Abbonamento;
use App\Models\Prodotto\Console;
use App\Models\Prodotto\Dlc;
use App\Models\Prodotto\Videogioco;
use App\Models\Prodotto\Fornitore;
use App\Models\Prodotto\Recensisce;
use App\Models\Profilo\Gestore;

class Prodotto extends Model
{
    use HasFactory;

    protected $table = 'prodotto';
    protected $primaryKey = 'codice_prodotto';
    public $timestamps = false;

    protected $fillable = [
        'codice_prodotto',
        'prezzo',
        'copertina',
        'sconto',
        'data_uscita',
        'nome',
        'quantita_fornitura',
        'data_fornitura',
        'fornitore',
        'gestore',
    ];

    protected $casts = [
        'prezzo' => 'double',
        'sconto' => 'decimal:0',
        'data_uscita' => 'date',
        'quantita_fornitura' => 'integer',
        'data_fornitura' => 'date',
    ];

    // --- Relationships ---

    public function presenteIn()
    {
        return $this->hasMany(PresenteIn::class, 'prodotto', 'codice_prodotto');
    }

    public function abbonamento()
    {
        return $this->hasOne(Abbonamento::class, 'prodotto', 'codice_prodotto');
    }

    public function console()
    {
        return $this->hasOne(Console::class, 'prodotto', 'codice_prodotto');
    }

    public function dlc()
    {
        return $this->hasOne(Dlc::class, 'prodotto', 'codice_prodotto');
    }

    public function videogioco()
    {
        return $this->hasOne(Videogioco::class, 'prodotto', 'codice_prodotto');
    }

    public function getFornitore()
    {
        return $this->belongsTo(Fornitore::class, 'fornitore', 'nome');
    }

    public function recensioni()
    {
        return $this->hasMany(Recensisce::class, 'prodotto', 'codice_prodotto');
    }

    public function getGestore()
    {
        return $this->belongsTo(Gestore::class, 'gestore', 'email');
    }
}