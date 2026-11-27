<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('herramientas', function (Blueprint $table) {
            // Vecino que tiene la herramienta en préstamo (null = nadie)
            $table->unsignedBigInteger('prestado_a')->nullable()->after('disponible')->index();
        });

        // SQLite no permite agregar llaves foráneas a una tabla existente
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            Schema::table('herramientas', function (Blueprint $table) {
                $table->foreign('prestado_a')->references('id')->on('users')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            Schema::table('herramientas', function (Blueprint $table) {
                $table->dropForeign(['prestado_a']);
            });
        }

        Schema::table('herramientas', function (Blueprint $table) {
            $table->dropColumn('prestado_a');
        });
    }
};
