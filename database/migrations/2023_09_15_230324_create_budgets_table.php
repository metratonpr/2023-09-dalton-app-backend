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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->date('budget_date');
            $table->date('expiration_date');
            $table->date('delivery_date')->nullable();
            $table->decimal('shipping_value', 10, 2)->nullable();
            $table->foreignId('address_id')->constrained('addresses');
            $table->foreignId('budget_type_id')->constrained('budget_types');
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
        Schema::dropIfExists('budgets');
    }
};
