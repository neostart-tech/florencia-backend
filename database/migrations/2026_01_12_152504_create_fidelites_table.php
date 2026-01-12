<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(){
        Schema::create('fidelites', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code');
            $table->integer('pourcentage');
            $table->boolean('is_active')->default(true);
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }
    public function down(){ Schema::dropIfExists('fidelites'); }
};

