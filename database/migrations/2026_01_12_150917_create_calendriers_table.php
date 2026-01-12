<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(){
        Schema::create('calendriers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('debut');
            $table->date('fin');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(){ Schema::dropIfExists('calendriers'); }
};

