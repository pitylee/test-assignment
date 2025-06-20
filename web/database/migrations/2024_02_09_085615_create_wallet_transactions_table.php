<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wallet_id')->references('id')->on('wallets')->cascadeOnDelete();
            $table->unsignedBigInteger('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->float('amount', 8, 2);
            $table->float('coins_current', 8, 2)->nullable();
            $table->float('coins_previous', 8, 2)->nullable();
            $table->json('metadata')->nullable();
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
        Schema::dropIfExists('wallet_transactions');
    }
};
