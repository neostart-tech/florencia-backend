<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(){
        Schema::create('articles_variantes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('article_id')->constrained('articles');
            $table->foreignUuid('variante_id')->constrained('variantes');
        });
    }
    public function down(){ Schema::dropIfExists('articles_variantes'); }
};

