<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(){
        Schema::create('articles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nom');
            $table->decimal('prix',10,2);
            $table->text('description')->nullable();
            $table->foreignUuid('sous_categorie_id')->constrained('sous_categories');
            $table->timestamps();
        });
    }
    public function down(){ Schema::dropIfExists('articles'); }
};
