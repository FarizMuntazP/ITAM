<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedInteger('current_sequence')->default(0)->after('description');
        });

        // Initialize sequences for existing categories
        $categories = DB::table('categories')->get();
        foreach ($categories as $category) {
            $count = DB::table('assets')->where('category_id', $category->id)->count();
            DB::table('categories')->where('id', $category->id)->update(['current_sequence' => $count]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('current_sequence');
        });
    }
};
