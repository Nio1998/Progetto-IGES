<?php

namespace App\Models\Prodotto;

use App\Models\Prodotto\Videogioco;
use App\Models\Prodotto\Categoria;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParteDi extends Model
{
    /** @use HasFactory<\Database\Factories\ParteDiFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $table = 'parte_di';

    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'videogioco',
        'categoria',
    ];

    protected $casts = [
        'videogioco' => 'integer',
    ];

    // Relationships

    public function videogioco()
    {
        return $this->belongsTo(Videogioco::class, 'videogioco', 'prodotto');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria', 'nome');
    }
}