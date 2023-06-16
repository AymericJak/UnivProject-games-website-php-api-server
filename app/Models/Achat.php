<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achat extends Model
{
    use HasFactory;

    function user(){
        return $this->belongsTo(User::class);
    }

    function jeu(){
        return $this->belongsTo(Jeu::class);
    }
}
