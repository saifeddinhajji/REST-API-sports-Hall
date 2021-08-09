<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColToOfferModel extends Migration
{
    /**
     * Run the migrations.m
     * @return void
     */
    public function up()
    {
        Schema::table('types_subscriptions', function (Blueprint $table) {
            $table->string('unit');});
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('types_subscriptions', function (Blueprint $table) {
            $table->dropColumn('unit');
        });
    }
}
