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
        Schema::create('coupon_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->unsignedBigInteger('user_coupon_id')->nullable();
            $table->unsignedBigInteger('transaction_id');
            $table->timestamps();

            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("coupon_id")->references("id")->on("coupons")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("user_coupon_id")->references("id")->on("user_coupons")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("transaction_id")->references("id")->on("transactions")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupon_transactions');
    }
};
