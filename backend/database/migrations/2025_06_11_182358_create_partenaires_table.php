<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('partenaires', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable(); // Stores partner description
            $table->string('phone')->nullable(); // Stores partner phone number
            $table->string('email')->nullable(); // Stores partner email address
            $table->string('image_url')->nullable(); // Stores path to partner logo/image
            $table->foreignId('edition_id')->constrained('edition')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('partenaires');
    }
};