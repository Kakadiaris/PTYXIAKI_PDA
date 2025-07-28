<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentFieldsToOrderItemsTable extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('menu_item_id'); // π.χ. 'cash', 'card'
            $table->boolean('is_paid')->default(false)->after('payment_method');
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'is_paid']);
        });
    }
}
