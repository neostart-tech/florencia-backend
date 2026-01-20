<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('personnels_horaires', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('horaire_id')->constrained('horaires');
            $table->foreignUuid('personnel_id')->constrained('personnels');
            $table->foreignUuid('service_id')->constrained()->cascadeOnDelete();

            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('personnels_horaires');
    }
};

