<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary Key
            $table->unsignedBigInteger('classroom_id'); // Referensi ke classroom
            $table->unsignedBigInteger('user_id'); // Referensi ke user
            $table->timestamp('reservation_startTime'); // Waktu reservasi
            $table->timestamp('reservation_endTime')->nullable(); // Waktu reservasi
            $table->enum('status', ['confirmed', 'cancelled'])->default('confirmed'); // Status reservasi
            $table->timestamps(); // created_at dan updated_at

            // Membuat foreign key constraints
            $table->foreign('classroom_id')->references('id')->on('classrooms')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
