<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateTypesSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('types_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('duration');
            $table->float('price');
            $table->foreignId('activity_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->integer('number_of_sessions');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('types_subscriptions');
    }
}
