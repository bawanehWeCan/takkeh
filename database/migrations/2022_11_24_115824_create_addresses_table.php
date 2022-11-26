<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->text('name')->nullable();
            $table->text('city')->nullable();
            $table->text('region')->nullable();
            $table->text('street')->nullable();
            $table->text('building_number')->nullable();
            $table->text('floor')->nullable();
            $table->text('apartment_number')->nullable();
            $table->text('additional_tips')->nullable();
            $table->text( 'lat' )->nullable();
            $table->text( 'long' )->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
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
        Schema::dropIfExists('addresses');
    }
};
