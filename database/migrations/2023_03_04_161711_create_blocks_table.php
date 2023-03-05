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
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->enum('position', \App\Models\Block::POSITIONS);
            $table->enum('status', \App\Models\Block::STATUS);
//            $table->bigInteger('blocked_id');
            $table->foreignId('blocked_id')->constrained('users', 'id')->cascadeOnDelete();
            $table->dateTime('from')->nullable();
            $table->dateTime('to')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocks');
    }
};
