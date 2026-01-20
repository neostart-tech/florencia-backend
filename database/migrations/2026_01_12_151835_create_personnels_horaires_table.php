<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('personnels_horaires', function (Blueprint $table) {
            $table->foreignUuid('horaire_id')->constrained('horaires');
            $table->foreignUuid('personnel_id')->constrained('personnels');

            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('personnels_horaires');
    }
};

