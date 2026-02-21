<?php

namespace App\Models\Acquisto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Profilo\Gestore;
use App\Models\Profilo\Cliente;
use App\Models\Acquisto\Spedito;
use App\Models\Acquisto\Riguarda;

class Ordine extends Model
{
    use HasFactory;

    protected $table = 'ordine';
    protected $primaryKey = 'codice';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'codice',
        'metodo_di_pagamento',
        'data_acquisto',
        'indirizzo_di_consegna',
        'cliente',
        'prezzo_totale',
        'gestore',
        'stato',
    ];

    protected $casts = [
        'data_acquisto' => 'date',
        'prezzo_totale' => 'double',
    ];

    // --- Relationships ---

    public function getGestore()
    {
        return $this->belongsTo(Gestore::class, 'gestore', 'email');
    }

    public function getCliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente', 'email');
    }

    public function getSpedito()
    {
        return $this->hasOne(Spedito::class, 'ordine', 'codice');
    }

    public function getRiguarda()
    {
        return $this->hasMany(Riguarda::class, 'ordine', 'codice');
    }
}