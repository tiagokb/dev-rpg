<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->text('magical_properties')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('rarity')->nullable();
            $table->string('type')->nullable();
            $table->string('classification')->nullable();
            $table->string('classes')->nullable();
            $table->string('damage')->nullable();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item');
    }
};
