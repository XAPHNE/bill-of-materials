<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qbom_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bom_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('asset_type')->default('algorithm');
            $table->string('current_algorithm')->nullable();
            $table->string('quantum_risk')->default('unknown');
            $table->string('migration_status')->default('not_started');
            $table->string('target_algorithm')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qbom_assets');
    }
};
