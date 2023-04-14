<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jeu extends Model
{
    use HasFactory;

    public function editeur(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Editeur::class);
    }

    public function theme(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Theme::class);
    }

    public function categorie(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Categorie::class);
    }

    public function commentaires(){
        return $this->hasMany(Commentaire::class);
    }

    public function likes(){
        return $this->hasMany(Like::class);
    }
}
