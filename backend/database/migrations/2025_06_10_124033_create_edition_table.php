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
        Schema::create('edition', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description_fr')->nullable();
            $table->text('description_en')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->json('images_url')->nullable();
            $table->string('place');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('edition');
    }
};
