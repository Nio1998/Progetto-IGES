<?php

namespace App\Models\Profilo;


use App\Models\Acquisto\Ordine;
use App\Models\Prodotto\Recensisce;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    /**
     * The table associated with the model.cliente freccia nome
     *
     * @var string
     */
    protected $table = 'cliente';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nome',
        'cognome',
        'email',
        'password',
        'data_di_nascita',
        'carta_fedelta',
        'cartadicredito',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'cartadicredito',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'data_di_nascita' => 'datetime',
        ];
    }

    //Relationship

    public function cartacredito()
    {
        return $this->hasOne(CartaDiCredito::class,'codicecarta','cartadicredito');
    }

    public function cartafedelta()
    {
        return $this->hasOne(CartaFedelta::class,'codice','carta_fedelta');
    }

    public function effettua()
    {
        return $this->hasMany(Ordine::class,'cliente','email');
    }

    public function recensisce()
    {
        return $this->hasMany(Recensisce::class, 'cliente', 'email');
    }
}
