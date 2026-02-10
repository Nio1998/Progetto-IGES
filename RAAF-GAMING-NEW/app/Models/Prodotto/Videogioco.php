<?php

namespace App\Models\Prodotto;

use App\Models\Prodotto\Prodotto;
use App\Models\Prodotto\SoftwareHouse;
use App\Models\Prodotto\ParteDi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Videogioco extends Model
{
    /** @use HasFactory<\Database\Factories\VideogiocoFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $table = 'videogioco';

    protected $primaryKey = 'prodotto';
    public $incrementing = false;

    protected $fillable = [
        'prodotto',
        'dimensione',
        'pegi',
        'edizione_limitata',
        'ncd',
        'vkey',
        'software_house',
    ];

    protected $casts = [
        'prodotto' => 'integer',
        'dimensione' => 'decimal:1',
        'pegi' => 'integer',
        'edizione_limitata' => 'boolean',
        'ncd' => 'integer',
    ];

    // Relationships

    public function prodotto()
    {
        return $this->belongsTo(Prodotto::class, 'prodotto', 'codice_prodotto');
    }

    public function softwareHouse()
    {
        return $this->belongsTo(SoftwareHouse::class, 'software_house', 'nomesfh');
    }

    public function parteDi()
    {
        return $this->hasMany(ParteDi::class, 'videogioco', 'prodotto');
    }
}