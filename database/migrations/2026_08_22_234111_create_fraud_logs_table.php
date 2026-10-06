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
        Schema::create('fraud_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete('cascade');

            $table->string('rfid_uid')->nullable();
            $table->string('photo_capture')->nullable();
            $table->string('ip_station');

            $table->enum('type',
                         ['BADGE_INCONNU',
                           'VISAGE_INVALIDE',
                            'IP_NON_AUTORISEE',
                            'ANTI_PASSBACK']);
                            
            $table->text('description');
            $table->timestamp('heure_fraude');         
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fraud_logs');
    }
};
 