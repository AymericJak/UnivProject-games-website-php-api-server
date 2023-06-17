<?php

use App\Models\User;
use App\Models\Jeu;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Nous avons préféré ne pas mettre date_achat comme clé primaire car cela fesait plus sens pour nous qu'un utilisateur ne pouvait acheter qu'une fois un jeu
        Schema::create('achats', function (Blueprint $table) {
            $table->primary(['user_id', 'jeu_id']);
            $table->foreignIdFor(User::class)
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignIdFor(Jeu::class)
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->dateTime('date_achat');
            $table->string('lieu_achat');
            $table->integer('prix');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achats');
    }
};
