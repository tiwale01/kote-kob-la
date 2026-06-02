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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained()->cascadeOnDelete();
            $table->string('donor_name');
            $table->string('lakou')->nullable();
            $table->string('lokalite')->nullable();
            $table->decimal('amount', 12, 2);
            $table->boolean('is_paid')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['collection_id', 'is_paid']);
            $table->index(['collection_id', 'lokalite']);
            $table->index(['collection_id', 'lakou']);
            $table->index('donor_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
