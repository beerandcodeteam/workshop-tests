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
        DB::table('order_items')->update([
            'price' => DB::raw('price * 100'),
        ]);

        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('price')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->change();
        });

        DB::table('order_items')->update([
            'price' => DB::raw('price / 100'),
        ]);
    }
};
