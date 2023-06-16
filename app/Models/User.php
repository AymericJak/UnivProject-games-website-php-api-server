<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject {
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "login",
        'email',
        'password',
        "nom",
        "prenom",
        "pseudo",
        "valide",
        "avatar"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

    public function roles() {
        return $this->belongsToMany(Role::class);
    }

    public function isAdmin() {
        return $this->roles()->where('nom', 'administrateur')->exists();
    }

    public function commentaires() {
        return $this->hasMany(Commentaire::class);
    }

    public function achats() {
        return $this->hasMany(Achat::class);
    }

    public function likes() {
        return $this->hasMany(Like::class);
    }

    public function hasRole(string $roleName) {
        foreach ($this->roles()->get() as $role) {
            if ($role->nom == $roleName) {
                return true;
            }
        }
        return false;
    }
}
