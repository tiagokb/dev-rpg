<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('race');
            $table->string('class');
            $table->integer('level')->default(1);
            $table->text('background')->nullable();
            $table->json('attributes')->nullable(); // Para dados complexos
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relacionamento com o jogador
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade'); // Relacionamento com a campanha
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
