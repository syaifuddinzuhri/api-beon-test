<?php

use App\Constant\GlobalConstant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnStatusHouseholdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('householders', function (Blueprint $table) {
            $table->enum('resident_status', GlobalConstant::RESIDENT_STATUS)->after('is_done');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('householders', function (Blueprint $table) {
            $table->dropColumn('resident_status');
        });
    }
}
