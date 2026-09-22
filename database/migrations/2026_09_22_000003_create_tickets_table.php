<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->uuid('ticket_id')->unique();
            $table->string('passenger_name');
            $table->string('passenger_nik', 16);
            $table->string('passenger_phone', 13);
            $table->string('origin');
            $table->string('destination');
            $table->date('departure_date');
            $table->string('vehicle')->nullable();
            $table->string('vehicle_plate')->nullable();
            $table->string('status')->default('booked');
            $table->timestamp('scanned_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
