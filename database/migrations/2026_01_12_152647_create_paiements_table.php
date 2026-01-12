<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(){
        Schema::create('paiements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('moyen_paiement');
            $table->string('reference_transaction')->unique();
            $table->string('statut');
            $table->uuidMorphs('owner');
            $table->timestamps();
        });
    }
    public function down(){ Schema::dropIfExists('paiements'); }
};

