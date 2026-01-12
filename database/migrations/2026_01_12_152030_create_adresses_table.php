<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(){
        Schema::create('adresses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('adresse');
            $table->string('ville');
            $table->string('code_postal');
            $table->string('tel');
            $table->foreignUuid('user_id')->constrained('users');
            $table->timestamps();
        });
    }
    public function down(){ Schema::dropIfExists('adresses'); }
};

