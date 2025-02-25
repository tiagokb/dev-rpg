<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
            $table->string('invite_code')->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
