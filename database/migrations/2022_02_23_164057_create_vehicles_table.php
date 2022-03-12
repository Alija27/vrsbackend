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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained();
            $table->string('name');
            $table->foreignId('type_id')->constrained();
            $table->string('model');
            $table->string('color');
            $table->string('total_seats');
            $table->string('rental_price');
            $table->string('description');
            $table->string('terms')->nullable();
            $table->string('image');
            $table->string('condition');
            $table->boolean('is_available');
            $table->boolean('has_driver');
            $table->boolean('is_approved');
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
        Schema::dropIfExists('vehicles');
    }
};
