<?php

namespace App\Models\Prodotto;

use App\Models\Prodotto\Prodotto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dlc extends Model
{
    /** @use HasFactory<\Database\Factories\DlcFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $table = 'dlc';

    protected $primaryKey = 'prodotto';
    public $incrementing = false;

    protected $fillable = [
        'prodotto',
        'dimensione',
        'descrizione',
    ];

    protected $casts = [
        'prodotto' => 'integer',
        'dimensione' => 'decimal:1',
    ];

    // Relationship

    public function prodotto()
    {
        return $this->belongsTo(Prodotto::class, 'prodotto', 'codice_prodotto');
    }
}