<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('battles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user1_id')->constrained('users')->onDelete('cascade'); 
            $table->foreignId('user2_id')->constrained('users')->onDelete('cascade'); 
            $table->foreignId('user1_rapper_id')->nullable()->constrained('rappers')->onDelete('cascade'); 
            $table->foreignId('user2_rapper_id')->nullable()->constrained('rappers')->onDelete('cascade'); 
            $table->enum('status', ['pending', 'accepted', 'completed', 'rejected'])->default('pending'); 
            $table->foreignId('winner_id')->nullable()->constrained('users')->onDelete('cascade'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('battles');
    }
};
