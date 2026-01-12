<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(){
        Schema::create('reservations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique();
            $table->foreignUuid('service_id')->constrained('services');
            $table->foreignUuid('horaire_id')->constrained('horaires');
            $table->foreignUuid('user_id')->constrained('users');
            $table->timestamps();
        });
    }
    public function down(){ Schema::dropIfExists('reservations'); }
};

