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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties');
            $table->foreignId('staff_id')->nullable()->constrained('staff');
            // $table->foreignId('staff_id')->constrained('staff');
            $table->foreignId('agent_id')->constrained('staff');
            $table->foreignId('tenant_id')->constrained('tenants');
            $table->integer('period');
            $table->decimal('deposit', 6, 2);
            $table->decimal('total', 6, 2);
            $table->decimal('balance', 6, 2);
            $table->integer('status')->default(2);
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
