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
        Schema::create('intervenant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('edition_id')
                ->constrained('edition')
                ->onDelete('cascade');
            $table->string('full_name');
            $table->string('image_url');
            $table->string('profession');
            $table->foreignId('program_id')
                ->constrained('program')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intervenant');
    }
};
