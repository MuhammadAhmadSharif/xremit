<?php

use App\Constants\PaymentGatewayConst;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger("payment_gateway_currency_id")->nullable();

            $table->string("type");
            $table->text('remittance_data');

            $table->string('trx_id')->comment('Transaction ID');
            $table->decimal('request_amount', 28, 16)->default(0);
            $table->decimal('exchange_rate', 28, 16)->default(0);
            $table->decimal('payable', 28, 16)->default(0);
            $table->decimal('fees', 28, 16)->default(0);
            $table->decimal('convert_amount', 28, 16)->default(0);
            $table->decimal('will_get_amount', 28, 16)->default(0);

            $table->string("remark")->nullable();
            $table->text("details")->nullable();
            $table->text("reject_reason")->nullable();
            $table->tinyInteger("status")->default(0)->comment("1: Review Payment, 2: Pending, 3: Confirm Payment, 4: On Hold, 5: Settled, 6: Completed, 7: Canceled, 8: Failed, 9: Refunded, 10: Delayed");
            $table->enum("attribute",[
                PaymentGatewayConst::SEND,
                PaymentGatewayConst::RECEIVED,
            ]);
            $table->timestamps();


            $table->foreign("admin_id")->references("id")->on("admins")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("payment_gateway_currency_id")->references("id")->on("payment_gateway_currencies")->onDelete("cascade")->onUpdate("cascade");
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
