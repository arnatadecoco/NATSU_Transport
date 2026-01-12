<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            
            // Info Transaksi
            $table->string('external_id')->nullable(); // ID dari Payment Gateway (misal: ORDER-123)
            $table->decimal('amount', 15, 2); // Jumlah bayar
            $table->string('payment_method')->nullable(); // misal: BANK_TRANSFER, QRIS
            $table->string('payment_channel')->nullable(); // misal: BCA, MANDIRI, GOPAY
            
            // Info Pembayaran (VA / Link)
            $table->string('va_number')->nullable(); // Nomor Virtual Account
            $table->string('payment_url')->nullable(); // Link bayar (kalau redirect)
            
            // Status: pending, paid, expired, failed
            $table->string('status')->default('pending'); 
            
            $table->timestamp('paid_at')->nullable(); // Waktu lunas
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};