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
        Schema::create('face_encodings', function (Blueprint $table) {
            $table->id();

            // Employé auquel appartient cette empreinte
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Numéro de la référence :
            // 1 = référence 1
            // 2 = référence 2
            // 3 = référence 3
            $table->unsignedTinyInteger('reference_number');

            // Vecteur facial SFace de 128 valeurs
            // Nous le stockerons sous forme JSON.
            $table->longText('encoding');

            $table->timestamps();

            // Un employé ne peut avoir qu'une seule référence
            // avec le même numéro.
            $table->unique([
                'user_id',
                'reference_number'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('face_encodings');
    }
};
