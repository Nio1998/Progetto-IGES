<?php

namespace App\Models\Profilo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartaFedelta extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    public $timestamps = false;
    
    /**
     * The table associated with the model.cliente freccia nome
     *
     * @var string
     */
    protected $table = 'cartafedelta';

    /**
     * La chiave primaria associata alla tabella.
     *
     * @var string
     */
    protected $primaryKey = 'codice';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'codice',
        'punti',
    ];
}
