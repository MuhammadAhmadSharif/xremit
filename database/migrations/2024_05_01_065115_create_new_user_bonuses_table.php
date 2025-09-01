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
        Schema::create('new_user_bonuses', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->decimal('price',28,8,true)->default(0);
            $table->bigInteger('max_used')->default(1);
            $table->unsignedBigInteger('last_edit_by');
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('last_edit_by')->references('id')->on('admins')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('new_user_bonuses');
    }
};
