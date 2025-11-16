<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('asset_type');      // crypto, stocks, forex
            $table->string('asset_name');      // BTCUSDT, AAPL, EURUSD
            $table->string('trade_type');      // buy/sell
            $table->decimal('amount', 15, 2);
            $table->string('leverage');
            $table->integer('duration');       // in minutes
            $table->decimal('take_profit', 8, 2)->nullable();
            $table->decimal('stop_loss', 8, 2)->nullable();
            $table->enum('status', ['open','closed'])->default('open');
            $table->decimal('profit_loss', 15, 2)->nullable()->default(0.00);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
