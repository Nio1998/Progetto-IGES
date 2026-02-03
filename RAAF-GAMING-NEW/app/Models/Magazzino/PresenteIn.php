<?php

namespace App\Models\Magazzino;

use App\Models\Prodotto\Prodotto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresenteIn extends Model
{
    /** @use HasFactory<\Database\Factories\PresenteInFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $table = 'presente_in';

    protected $primaryKey = ['magazzino', 'prodotto'];
    public $incrementing = false;

    protected $fillable = [
        'magazzino',
        'prodotto',
        'quantita_disponibile',
    ];

    // Relationship

    public function magazzino()
    {
        return $this->belongsTo(Magazzino::class, 'magazzino', 'indirizzo');
    }

    public function prodotto()
    {
        return $this->belongsTo(Prodotto::class, 'prodotto', 'codice_prodotto');
    }
}