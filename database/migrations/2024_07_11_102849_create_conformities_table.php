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
        Schema::create('conformities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('building_id');
            $table->string('type');
            $table->date('date_verification');
            $table->string('result');
            $table->unsignedBigInteger('user_id');
            $table->foreign('building_id')->references('id')->on('buildings')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conformities');
    }
};
