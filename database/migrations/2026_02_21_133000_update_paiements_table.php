<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('paiements', function (Blueprint $table) {
            $table->decimal('montant', 15, 2)->default(0)->after('reference_transaction');
            // On renomme statut en status pour uniformiser
            $table->renameColumn('statut', 'status');
        });
    }
    public function down() {
        Schema::table('paiements', function (Blueprint $table) {
            $table->dropColumn('montant');
            $table->renameColumn('status', 'statut');
        });
    }
};
