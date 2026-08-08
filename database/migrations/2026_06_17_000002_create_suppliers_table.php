<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('gst_number')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('payment_terms')->nullable(); // e.g. "30 days credit"
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('supplier_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->string('product_name');
            $table->string('hsn')->nullable();
            $table->string('unit')->default('kg');
            $table->decimal('rate', 12, 2)->nullable(); // current rate per unit
            $table->decimal('gst_rate', 5, 2)->default(18);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('supplier_coas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_product_id')->constrained()->cascadeOnDelete();
            $table->string('lot_no');
            $table->date('received_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('coa_status')->default('pending'); // pending, received, verified
            $table->text('parameters')->nullable(); // JSON: [{name, spec, result, pass}]
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_coas');
        Schema::dropIfExists('supplier_products');
        Schema::dropIfExists('suppliers');
    }
};
