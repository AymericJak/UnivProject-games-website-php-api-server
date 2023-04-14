<?php

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
        Schema::create('commentaires', function (Blueprint $table) {
            $table->id();
            $table->string('commentaire');
            $table->dateTime('date_com')->default(date("Y-m-d H:i:s"));
            $table->integer('note');
            $table->enum('etat', ['attente de validation', 'public'])->default('public');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('jeu_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('jeu_id')->references('id')->on('jeus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commentaires');
    }
};
