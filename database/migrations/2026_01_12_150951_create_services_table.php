<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(){
        Schema::create('services', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nom');
            $table->string('type');
            $table->integer('duree');
            $table->timestamps();
        });
    }
    public function down(){ Schema::dropIfExists('services'); }
};

