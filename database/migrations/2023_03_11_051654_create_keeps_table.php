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
        Schema::create('keeps', function (Blueprint $table) {
            $table->id();
            $table->integer('from_juz');
            $table->integer('to_juz');
            $table->integer('from_surah');
            $table->integer('to_surah');
            $table->integer('from_ayah');
            $table->integer('to_ayah');
            $table->integer('faults')->default(0);
            $table->foreignId('student_id')->constrained('users', 'id')->cascadeOnDelete();
            $table->foreignId('group_id')->nullable()->constrained('groups', 'id')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keeps');
    }
};
