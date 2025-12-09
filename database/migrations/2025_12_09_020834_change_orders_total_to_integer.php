<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('orders')->update([
            'total' => DB::raw('total * 100'),
        ]);

        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('total')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total', 10, 2)->change();
        });

        DB::table('orders')->update([
            'total' => DB::raw('total / 100'),
        ]);
    }
};
