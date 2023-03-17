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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keeper_id')->constrained('users', 'id')->nullOnDelete();
            $table->enum('status', \App\Models\Report::STATUS);
            $table->enum('type', \App\Models\Report::TYPE);
            $table->json('payload');
            $table->text('submitted_msg')->nullable();
            $table->text('supervisor_reply_msg')->nullable();
            $table->text('manager_reply_msg')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
