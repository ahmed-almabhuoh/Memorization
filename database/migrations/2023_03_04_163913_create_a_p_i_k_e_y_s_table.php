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
        Schema::create('a_p_i_k_e_y_s', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('key', 50)->unique();
            $table->string('secret');
            $table->string('name', 50)->nullable();
            $table->enum('status', \App\Models\APIKEY::STATUS);
            $table->bigInteger('rat_limit')->unsigned();
            $table->foreignId('manager_id')->constrained('users', 'id')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('a_p_i_k_e_y_s');
    }
};
