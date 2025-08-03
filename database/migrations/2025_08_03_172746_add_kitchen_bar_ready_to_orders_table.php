<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('kitchen_ready_at')->nullable()->after('status');
            $table->timestamp('bar_ready_at')->nullable()->after('kitchen_ready_at');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['kitchen_ready_at', 'bar_ready_at']);
        });
    }
};
