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
        Schema::create('rapports', function (Blueprint $table) {

            $table->id();

            // Type du rapport :
            // pointage, personnel, retard, entrees_sortie
            $table->string('type_rapport');

            // Employé concerné.
            // NULL = rapport concernant tous les employés
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Période couverte par le rapport 
            $table->date('date_debut');
            $table->date('date_fin');

            // Nom du fichier PDF généré
            $table->string('nom_fichier');

            // Nombre de pointages présents dans le rapport
            $table->unsignedInteger('nombre_pointages')->default(0);

            // Date et heure de génération
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapports');
    }
};