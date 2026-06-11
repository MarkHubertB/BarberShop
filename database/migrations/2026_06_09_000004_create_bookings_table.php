<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('reference_code', 8)->unique();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->foreignId('service_id')->constrained()->restrictOnDelete();
            $table->foreignId('barber_id')->nullable()->constrained()->nullOnDelete();
            $table->date('booking_date');
            $table->foreignId('time_slot_id')->constrained()->restrictOnDelete();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->boolean('reminder_sent')->default(false);
            $table->timestamps();

            $table->unique(['barber_id', 'booking_date', 'time_slot_id']);
            $table->index(['booking_date', 'status']);
            $table->index('customer_email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
