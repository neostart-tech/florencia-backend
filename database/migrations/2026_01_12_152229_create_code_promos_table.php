<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(){
        Schema::create('code_promos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('date_debut');
            $table->date('date_fin');
            $table->string('code')->unique();
            $table->integer('pourcentage');
            $table->timestamps();
        });
    }
    public function down(){ Schema::dropIfExists('code_promos'); }
};

