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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Informations principales
            $table->string('reference_commande', 100)->unique();
            $table->date('date_commande');

            // Informations fournisseur
            $table->string('nom_fournisseur', 191);         // Nom du fournisseur
            $table->string('code_fournisseur', 100)->nullable();         // code du fournisseur


            // Informations adr
            $table->string('commande_par', 255);
            $table->string('commande_a', 255);

            // Montants
            $table->decimal('montant_ht', 10, 2)->default(0);   // Montant HT
            $table->decimal('montant_tva', 10, 2)->default(0);  // Montant TVA
            $table->decimal('montant_ttc', 10, 2)->default(0);  // Montant TTC

            // Référence de commande


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
