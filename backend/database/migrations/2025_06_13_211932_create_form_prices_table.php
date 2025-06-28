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
        Schema::create('form_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('edition_id')
                ->constrained('edition')
                ->onDelete('cascade');
            $table->integer('prix_tun')->default(0);
            $table->integer('prix_international')->default(0);
            $table->integer('prix_tun_hebergement')->default(0);
            $table->integer('prix_eur_hebergement')->default(0);
            $table->integer('prix_article_tun')->default(0);
            $table->integer('prix_article_eur')->default(0);
            $table->integer ('prix_acc_adulte_tun')->default(0);
            $table->integer ('prix_acc_adulte_eur')->default(0);
            $table->integer ('prix_acc_enfant_tun')->default(0);
            $table->integer ('prix_acc_enfant_eur')->default(0);
            $table->integer('prix_nuit_supp_tun')->default(0);
            $table->integer('prix_nuit_supp_eur')->default(0);
            $table->integer('prix_single_supp_tun')->default(0);
            $table->integer('prix_single_supp_eur')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_prices');
    }
};
