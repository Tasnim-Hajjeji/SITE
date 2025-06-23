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
        Schema::create('keynotes', function (Blueprint $table) {
            $table->id();
            $table->text('description_fr')->nullable();
            $table->text('description_en')->nullable();
            $table->String('speaker_name');
            $table->String('speaker_role');
            $table->string('image_url');
            $table->foreignId('edition_id')
                  ->constrained('edition')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keynotes');
    }
};
