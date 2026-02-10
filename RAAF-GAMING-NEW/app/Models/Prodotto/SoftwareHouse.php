<?php

namespace App\Models\Prodotto;

use App\Models\Prodotto\Videogioco;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoftwareHouse extends Model
{
    /** @use HasFactory<\Database\Factories\SoftwareHouseFactory> */
    use HasFactory;

    public $timestamps = false;
    protected $table = 'softwarehouse';
    protected $primaryKey = 'nomesfh';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nomesfh',
        'logo',
    ];

    // Relationship

    public function videogiochi()
    {
        return $this->hasMany(Videogioco::class, 'software_house', 'nomesfh');
    }
}

