<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('buyer_declarations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sale_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_no');
            $table->string('declaration_no');
            $table->string('buyer_name');
            $table->string('buyer_company');
            $table->string('intended_use');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('buyer_declarations');
    }
};
