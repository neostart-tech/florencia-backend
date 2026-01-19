<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('stock_mouvements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('type', ['entree', 'sortie']);
            $table->integer('quantite');
            $table->text('commentaire')->nullable();
            $table->foreignUuid('article_id')->constrained('articles');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('stock_mouvements');
    }
};

