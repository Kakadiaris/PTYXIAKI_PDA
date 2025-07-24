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
        Schema::create('statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_id')->constrained('menu_items'); // Σύνδεση με το menu_items
            $table->integer('sold_count'); // Πόσες φορές πουλήθηκε το προϊόν
            $table->decimal('total_revenue', 8, 2); // Συνολικός τζίρος από το προϊόν
            $table->date('date'); // Η ημερομηνία της πώλησης
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistics');
    }
};
