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
        Schema::create('follows', function (Blueprint $table) {
            // Quem está seguindo
            $table->foreignId('follower_id')->constrained('users')->cascadeOnDelete();
            // Quem está sendo seguido
            $table->foreignId('following_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            // Garante que o usuário não siga a mesma pessoa duas vezes
            $table->primary(['follower_id', 'following_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};
