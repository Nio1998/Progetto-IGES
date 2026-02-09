<?php

namespace App\Models\Prodotto;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Prodotto\Videogioco;

class SoftwareHouse extends Model
{
    use HasFactory;

    /**
     * La tabella associata al model.
     *
     * @var string
     */
    protected $table = 'softwarehouse';

    protected $primaryKey = 'nomesfh';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nomesfh',
        'logo',
    ];

    public function getVideogioco()
    {
        return $this->hasMany(Videogioco::class, 'software_house', 'nomesfh');
    }
}
