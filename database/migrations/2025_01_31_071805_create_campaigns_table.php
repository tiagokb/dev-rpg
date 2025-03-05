<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('cover_img_url')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->boolean('is_open')->default(false);
            $table->integer('max_players')->default(5);
            $table->timestamps();
            $table->string('invite_code')->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
