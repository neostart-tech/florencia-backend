<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('horaires', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->integer('nbre_clients');
            $table->foreignUuid('jour_id')->constrained('jours');
            $table->foreignUuid('calendrier_id')->constrained('calendriers');
            $table->foreignUuid('service_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('horaires');
    }
};

