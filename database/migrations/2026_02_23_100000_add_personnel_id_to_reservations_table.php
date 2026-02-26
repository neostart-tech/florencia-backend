<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('reservations', 'personnel_id')) {
                $table->foreignUuid('personnel_id')->nullable()->constrained('personnels')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'personnel_id')) {
                $table->dropForeign(['personnel_id']);
                $table->dropColumn('personnel_id');
            }
        });
    }
};
