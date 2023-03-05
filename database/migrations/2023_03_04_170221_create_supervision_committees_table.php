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
        Schema::create('supervision_committees', function (Blueprint $table) {
            $table->id();
            $table->string('name', 25)->unique();
            $table->string('image')->nullable();
            $table->enum('status', \App\Models\SupervisionCommittee::STATUS);
            $table->enum('type', \App\Models\SupervisionCommittee::TYPES);
            $table->text('region', 50)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supervision_committees');
    }
};
