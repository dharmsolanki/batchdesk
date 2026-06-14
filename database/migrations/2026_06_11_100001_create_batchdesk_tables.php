<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 20);
            $table->string('gst_number', 20)->nullable();
            $table->string('license_no')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->default('Gujarat');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('role', 20)->default('owner')->after('phone');
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('phone', 20);
            $table->string('gst_number', 20)->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
            $table->index(['company_id', 'phone']);
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('hsn', 20)->nullable();
            $table->string('unit', 20)->default('kg');
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('gst_rate', 5, 2)->default(18.00);
            $table->timestamps();
        });

        Schema::create('spec_params', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('parameter');
            $table->string('specification');
            $table->string('method')->nullable();
            $table->integer('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('raw_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('unit', 20)->default('kg');
            $table->timestamps();
        });

        Schema::create('material_lots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('raw_material_id')->constrained()->cascadeOnDelete();
            $table->string('lot_no');
            $table->string('supplier')->nullable();
            $table->decimal('qty', 12, 3)->default(0);
            $table->date('expiry')->nullable();
            $table->date('received_date')->nullable();
            $table->timestamps();
        });

        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('batch_no');
            $table->date('mfg_date');
            $table->date('exp_date')->nullable();
            $table->decimal('qty', 12, 3)->default(0);
            $table->decimal('produced_qty', 12, 3)->default(0);
            $table->string('status', 20)->default('testing');
            $table->string('coa_token', 64)->unique();
            $table->string('tested_by')->nullable();
            $table->string('approved_by')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->index(['company_id', 'batch_no']);
        });

        Schema::create('batch_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('material_lot_id')->constrained()->cascadeOnDelete();
            $table->decimal('qty_used', 12, 3)->default(0);
            $table->timestamps();
        });

        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('spec_param_id')->constrained()->cascadeOnDelete();
            $table->string('result')->nullable();
            $table->boolean('pass')->default(true);
            $table->timestamps();
            $table->unique(['batch_id', 'spec_param_id']);
        });

        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_no');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('gst_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->string('status', 20)->default('paid');
            $table->string('notes')->nullable();
            $table->timestamps();
            $table->index(['company_id', 'created_at']);
        });

        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('description');
            $table->decimal('qty', 12, 3)->default(1);
            $table->string('unit', 20)->default('kg');
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('gst_rate', 5, 2)->default(0);
            $table->decimal('gst_amount', 12, 2)->default(0);
            $table->decimal('line_total', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('mode', 30)->default('cash');
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('sales');
        Schema::dropIfExists('test_results');
        Schema::dropIfExists('batch_materials');
        Schema::dropIfExists('batches');
        Schema::dropIfExists('material_lots');
        Schema::dropIfExists('raw_materials');
        Schema::dropIfExists('spec_params');
        Schema::dropIfExists('products');
        Schema::dropIfExists('customers');
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('company_id');
            $table->dropColumn(['phone', 'role']);
        });
        Schema::dropIfExists('companies');
    }
};
