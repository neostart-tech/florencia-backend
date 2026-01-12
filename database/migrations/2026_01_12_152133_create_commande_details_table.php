<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(){
        Schema::create('commande_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('quantite');
            $table->decimal('prix_unitaire',10,2);
            $table->foreignUuid('article_id')->constrained('articles');
            $table->foreignUuid('commande_id')->constrained('commandes');
        });
    }
    public function down(){ Schema::dropIfExists('commande_details'); }
};

