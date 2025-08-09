<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('reservations')) {
            Schema::create('reservations', function (Blueprint $table) {
                $table->id();

                $table->foreignId('table_id')->constrained()->onDelete('cascade');
                // Στο create, απλώς βάζουμε το name μετά το table_id στη σειρά
                $table->string('name')->nullable();

                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->unsignedInteger('guest_count');
                $table->dateTime('reservation_at');
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        } else {
            Schema::table('reservations', function (Blueprint $table) {
                if (!Schema::hasColumn('reservations', 'name')) {
                    $table->string('name')->nullable()->after('table_id');
                }
                if (!Schema::hasColumn('reservations', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }
    }

    public function down(): void
    {
        // Προσοχή: αυτό θα διαγράψει ΟΛΟ τον πίνακα και τα δεδομένα του στο rollback.
        Schema::dropIfExists('reservations');
    }
};
