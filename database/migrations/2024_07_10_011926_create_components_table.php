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
        Schema::create('components', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->float('quantity');
            $table->string('unit');
            $table->string('last_rehabilitation_year')->nullable();
            $table->enum('condition', ['C1', 'C2', 'C3', 'C4']);
            $table->enum('severity_max', ['S1', 'S2', 'S3', 'S4']);
            $table->enum('risk_level', ['R1', 'R2', 'R3', 'R4']);
            $table->text('description')->nullable();
            $table->enum('severity_safety', ['S1', 'S2', 'S3', 'S4'])->nullable();
            $table->enum('severity_operations', ['S1', 'S2', 'S3', 'S4'])->nullable();
            $table->enum('severity_work_conditions', ['S1', 'S2', 'S3', 'S4'])->nullable();
            $table->enum('severity_environment', ['S1', 'S2', 'S3', 'S4'])->nullable();
            $table->string('severity_image')->nullable();
            $table->unsignedBigInteger('building_id');
            $table->foreign('building_id')->references('id')->on('buildings')->onDelete('cascade');
            $table->string('characteristics')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('components');
    }
};
