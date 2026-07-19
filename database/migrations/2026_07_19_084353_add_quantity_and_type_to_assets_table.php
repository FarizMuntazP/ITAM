<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 'unit' = aset satuan ber-SN (1 aset = 1 baris, qty selalu 1)
     * 'bulk' = aset massal/non-SN (qty bisa > 1, tanpa SN)
     */
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->enum('asset_type', ['unit', 'bulk'])->default('unit')->after('asset_id');
            $table->unsignedSmallInteger('quantity')->default(1)->after('asset_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['asset_type', 'quantity']);
        });
    }
};
