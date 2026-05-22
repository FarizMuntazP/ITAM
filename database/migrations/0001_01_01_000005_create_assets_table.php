<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_id', 30)->unique();
            $table->string('asset_name', 150);
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->foreignId('store_id')->constrained('stores')->restrictOnDelete();
            $table->string('brand', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->string('serial_number', 100)->unique()->nullable();
            $table->text('specs')->nullable();
            $table->enum('condition', ['good', 'fair', 'poor', 'damaged'])->default('good');
            $table->enum('status', ['active', 'inactive', 'maintenance', 'disposed'])->default('active');
            $table->date('purchase_date')->nullable();
            $table->date('warranty_until')->nullable();
            $table->decimal('purchase_price', 15, 2)->nullable();
            $table->string('location_detail', 200)->nullable();
            $table->text('notes')->nullable();
            $table->string('photo', 255)->nullable();
            $table->string('qr_code_path', 255)->nullable();
            $table->timestamp('added_at')->useCurrent();
            $table->timestamps();

            // Indexes for filter performance
            $table->index('condition');
            $table->index('status');
            $table->index('purchase_date');
            $table->index('added_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
