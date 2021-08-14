<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionGymsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_gyms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_id')->nullable()->constrained('gyms')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('offer_id')->nullable()->constrained('offers')->onUpdate('cascade')->onDelete('cascade');
            $table->date('start_at');
            $table->date('end_at');
            $table->string('payment_receipt');
            $table->string('status')->default("en attente");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_gyms');
    }
}
