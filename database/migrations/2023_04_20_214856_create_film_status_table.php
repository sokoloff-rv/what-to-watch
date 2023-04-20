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
        DB::statement("CREATE TYPE film_statuses AS ENUM('pending', 'moderate', 'ready');");

        Schema::create('film_status', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['pending', 'moderate', 'ready']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('film_status');
        DB::statement('DROP TYPE IF EXISTS film_statuses;');
    }
};
