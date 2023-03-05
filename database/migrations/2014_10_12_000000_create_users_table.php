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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fname', 20);
            $table->string('sname', 20);
            $table->string('tname', 20);
            $table->string('lname', 20);
            $table->string('identity_no', 9)->unique();
            $table->string('phone', 13)->unique();
            $table->text('local_region')->nullable();
            $table->string('password');
            $table->enum('position', \App\Models\User::POSITIONS);
            $table->enum('gender', \App\Models\User::GENDER);
            $table->enum('status', \App\Models\User::STATUS);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
