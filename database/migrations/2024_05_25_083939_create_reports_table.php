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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->nullable()->constrained('staff');
            $table->foreignId('tenant_id')->nullable()->constrained('tenants');
            $table->integer('title');
            $table->string('description');
            $table->string('remark')->nullable();
            $table->integer('status')->default(2);
            $table->string('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
