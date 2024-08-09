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
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('activity');
            $table->text('address');
            $table->string('zipcode');
            $table->string('city');
            $table->string('country');
            $table->string('floor_area');
            $table->unsignedBigInteger('workspace_id');
            $table->foreign('workspace_id')->references('id')->on('workspaces')->onDelete('cascade');
            $table->timestamps();
             
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
