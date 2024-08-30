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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('name')->default('AssetLink');
            // Billing Plans
            $table->enum('plan_name',['Basic plan','Enterprise Plan','Professional Plan']);
            $table->integer('plan_price')->default(0); // In cents or in the smallest currency unit
            $table->enum('plan_currency',['USD','DH','Euro'])->default('DH');
            $table->integer('max_users')->default(1); // Maximum number of users for the plan
            $table->integer('max_projects')->default(1); // Maximum number of projects for the plan
            $table->integer('max_storage')->default(1024);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
