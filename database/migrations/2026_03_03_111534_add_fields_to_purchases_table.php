<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->string('invoice_no')->nullable()->after('user_id');
            $table->date('purchase_date')->nullable()->after('invoice_no');
            $table->string('status')->default('Received')->after('total_amount');
        });
    }

    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn(['invoice_no', 'purchase_date', 'status']);
        });
    }
};
