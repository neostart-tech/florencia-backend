<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(){
        Schema::create('personnels', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nom');
            $table->string('prenom');
            $table->string('tel')->unique();
            $table->string('email')->unique();
            $table->timestamps();
        });
    }
    public function down(){ Schema::dropIfExists('personnels'); }
};

