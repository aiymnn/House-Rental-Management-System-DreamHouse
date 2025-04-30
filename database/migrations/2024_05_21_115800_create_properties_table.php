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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landlord_id')->constrained('landlords');
            $table->foreignId('staff_id')->nullable()->constrained('staff');
            $table->foreignId('agent_id')->nullable()->constrained('staff');
            $table->string('address');
            $table->integer('type');
            $table->integer('status')->default(3);
            $table->integer('available')->default(2);
            $table->decimal('deposit', 6, 2)->nullable();
            $table->decimal('monthly', 6, 2)->nullable();
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
