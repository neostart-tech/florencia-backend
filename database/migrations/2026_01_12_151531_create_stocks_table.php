<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(){
        Schema::create('stocks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('quantite');
            $table->foreignUuid('article_id')->constrained('articles');
            $table->timestamps();
        });
    }
    public function down(){ Schema::dropIfExists('stocks'); }
};

