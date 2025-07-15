<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->string('value')->unique(); // π.χ. "A", "B", "C"
            $table->unsignedInteger('tables_count')->default(0); // μετρητής τραπεζιών
            $table->timestamps();
        });

        // Προσθήκη foreign key στη στήλη zone_id του πίνακα tables
        Schema::table('tables', function (Blueprint $table) {
            $table->unsignedBigInteger('zone_id')->nullable()->after('id');
            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('tables', function (Blueprint $table) {
            $table->dropForeign(['zone_id']);
            $table->dropColumn('zone_id');
        });

        Schema::dropIfExists('zones');
    }
};
