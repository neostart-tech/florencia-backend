<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(){
        Schema::create('sous_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('libelle');
            $table->foreignUuid('categorie_id')->constrained('categories');
            $table->timestamps();
        });
    }
    public function down(){ Schema::dropIfExists('sous_categories'); }
};

