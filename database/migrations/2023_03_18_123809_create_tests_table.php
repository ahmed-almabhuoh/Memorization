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
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->enum('type', \App\Models\Test::TYPE);
            $table->integer('from')->unsigned();
            $table->integer('to')->unsigned();
            $table->foreignId('keeper_id')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->foreignId('student_id')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->foreignId('sc_id')->nullable()->constrained('supervision_committees', 'id')->nullOnDelete();
            $table->float('mark')->nullable()->unsigned();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
