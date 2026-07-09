<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('method')->default('mobile_money');
            $table->decimal('amount', 10, 2);
            $table->string('sender_number', 20)->nullable();   // number customer sent from
            $table->string('transaction_id')->nullable();       // customer-provided MoMo transaction ID
            $table->string('status')->default('pending');       // pending, confirmed, rejected
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
