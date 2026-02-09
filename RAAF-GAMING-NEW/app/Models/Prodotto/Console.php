<?php

namespace App\Models\Prodotto;

use App\Models\Prodotto\Prodotto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Console extends Model
{
    /** @use HasFactory<\Database\Factories\ConsoleFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $table = 'console';

    protected $primaryKey = 'prodotto';
    public $incrementing = false;

    protected $fillable = [
        'prodotto',
        'specifica',
        'colore',
    ];

    protected $casts = [
        'prodotto' => 'integer',
    ];

    // Relationship

    public function prodotto()
    {
        return $this->belongsTo(Prodotto::class, 'prodotto', 'codice_prodotto');
    }
}