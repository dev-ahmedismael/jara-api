<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('title');
            $table->text('description');
            $table->dateTime('start_date')->nullable();
            $table->time('start_time')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->time('end_time')->nullable();
            $table->dateTime('expiry_date')->nullable();
            $table->time('expiry_time')->nullable();
            $table->decimal('price', 10, 2);
            $table->boolean('enable_comments')->default(false);
            $table->boolean('enable_rating')->default(false);
            $table->bigInteger('max_allowed_bookings')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
