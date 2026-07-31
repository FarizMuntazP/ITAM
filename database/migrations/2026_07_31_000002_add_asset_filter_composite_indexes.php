<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->index(['store_id', 'status'], 'assets_store_status_index');
            $table->index(['category_id', 'status'], 'assets_category_status_index');
            $table->index(['status', 'condition'], 'assets_status_condition_index');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropIndex('assets_store_status_index');
            $table->dropIndex('assets_category_status_index');
            $table->dropIndex('assets_status_condition_index');
        });
    }
};
