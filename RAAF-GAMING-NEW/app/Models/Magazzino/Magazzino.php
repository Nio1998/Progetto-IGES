<?php

namespace App\Models\Magazzino;

use App\Models\Magazzino\PresenteIn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magazzino extends Model
{
    /** @use HasFactory<\Database\Factories\MagazzinoFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $table = 'magazzino';

    protected $primaryKey = 'indirizzo';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'indirizzo',
        'capienza',
    ];

    // Relationship

    public function presenteIn()
    {
        return $this->hasMany(PresenteIn::class, 'magazzino', 'indirizzo');
    }
}