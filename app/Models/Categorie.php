<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static pluck(string $string)
 */
class Categorie extends Model
{
    use HasFactory;

    public function jeux(): HasMany
    {
        return $this->hasMany(Jeu::class);
    }
}
