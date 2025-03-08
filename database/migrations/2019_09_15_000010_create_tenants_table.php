<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->string('website_type');
            $table->string('license_type');
            $table->string('license_name');
            $table->string('license_number');
            $table->decimal('paid_amount', 8, 2)->default(0);
            $table->decimal('due_amount', 8, 2)->default(0);
            $table->date('due_date')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->json('data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
}
