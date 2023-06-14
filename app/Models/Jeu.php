<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jeu extends Model {
    use HasFactory;

    public function editeur(): \Illuminate\Database\Eloquent\Relations\HasOne {
        return $this->hasOne(Editeur::class, 'id', 'editeur_id');
    }

    public function theme(): \Illuminate\Database\Eloquent\Relations\HasOne {
        return $this->hasOne(Theme::class, 'id', 'theme_id');
    }

    public function categorie(): \Illuminate\Database\Eloquent\Relations\HasOne {
        return $this->hasOne(Categorie::class, 'id', 'categorie_id');
    }

    public function commentaires() {
        return $this->hasMany(Commentaire::class);
    }

    public function likes() {
        return $this->hasOne(Like::class, 'jeu_id', 'like_id');
    }

    public function achats() {
        return $this->hasMany(Achat::class);
    }
}
