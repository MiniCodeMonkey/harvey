<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAcuracyToEmergencies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emergencies', function (Blueprint $table) {
            $table->decimal('accuracy_score', 3, 2)->after('lng');
            $table->string('accuracy_type')->after('accuracy_score');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emergencies', function (Blueprint $table) {
            $table->dropColumn('accuracy_score');
            $table->dropColumn('accuracy_type');
        });
    }
}
