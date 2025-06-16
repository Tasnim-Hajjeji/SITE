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
        Schema::create('participant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('edition_id')
                ->constrained('edition')
                ->onDelete('cascade');
            $table->string('nom');
            $table->string('prenom');
            $table->string('email');
            $table->string('fonction');
            $table->string('tel');
            $table->string('pays');
            $table->boolean('est_tunisien');
            $table->string('etablissement')->nullable();
            $table->integer('num_enfant')->default(0);
            $table->integer('num_adulte')->default(0);
            $table->boolean('supp_single');
            $table->integer('supp_nuit')->default(0);
            $table->integer('prix_total')->default(0);
            $table->string('methode_paie');
            $table->string('recu_paie');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participant');
    }
};
