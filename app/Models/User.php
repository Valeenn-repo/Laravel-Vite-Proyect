<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    // Si el nombre de la tabla es 'users', Laravel lo detecta automáticamente.

    protected $table = 'users'; // Asegurar el nombre correcto de la tabla
    public $timestamps = false; // Desactivar timestamps para evitar el error
    
    /**
     * Los atributos que se asignan de forma masiva.
     */
    protected $fillable = [
        'email',
        'password_hash',
        'role',
        'is_active',
        // otros campos...
    ];

    /**
     * Sobrescribir para que Laravel utilice 'password_hash' en vez de 'password'
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /**
     * Los atributos que se deben ocultar para arrays.
     */
    protected $hidden = [
        'password_hash',
    ];

    /**
     * Métodos requeridos para JWTSubject.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    
}
