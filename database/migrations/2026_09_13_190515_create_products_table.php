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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->decimal('base_price', 10, 2);
            $table->decimal('compare_price', 10, 2)->nullable();
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->string('status')->default('active'); // active, draft, archived
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            // Additional attributes for frontend UI display & quick stock tracking
            $table->string('icon')->nullable();
            $table->string('plate')->nullable(); // Hex color e.g., #DCD3BC
            $table->json('colors')->nullable();
            $table->json('sizes')->nullable();
            $table->integer('stock')->default(0);
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->integer('reviews_count')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
