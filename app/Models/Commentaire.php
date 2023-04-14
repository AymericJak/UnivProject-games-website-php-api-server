<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    use HasFactory;

    public function jeu() {
        return $this->belongsTo(Jeu::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}