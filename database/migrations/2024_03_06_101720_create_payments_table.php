<?php

use App\Constant\GlobalConstant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('householder_id')->nullable();
            $table->unsignedBigInteger('resident_id')->nullable();
            $table->unsignedBigInteger('payment_type_id')->nullable();
            $table->enum('type', GlobalConstant::PAYMENT_TYPES)->default(GlobalConstant::IN);
            $table->date('date')->nullable();
            $table->tinyInteger('month')->nullable();
            $table->integer('year')->nullable();
            $table->float('nominal', 16, 0)->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
