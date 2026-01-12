<?php

namespace App\Models\Profilo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartaDiCredito extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    
    public $timestamps = false;
    
    /**
     * The table associated with the model.cliente freccia nome
     *
     * @var string
     */
    protected $table = 'cartadicredito';

    /**
     * La chiave primaria associata alla tabella.
     *
     * @var string
     */
    protected $primaryKey = 'codicecarta';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'codicecarta',
        'data_scadenza',
        'codice_cvv',
    ];

       /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'data_scadenza' => 'datetime',
        ];
    }
}