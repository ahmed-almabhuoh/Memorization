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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 25)->unique();
            $table->string('image')->nullable();
            $table->enum('status', \App\Models\Group::STATUS);
            $table->text('region', 50)->nullable();
            $table->foreignId('center_id')->constrained('centers', 'id')->nullOnDelete();
            $table->foreignId('keeper_id')->constrained('users', 'id')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
