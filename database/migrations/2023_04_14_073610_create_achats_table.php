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
            $table->integer('number');
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
