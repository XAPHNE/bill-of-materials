<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cbom_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bom_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('asset_type')->default('algorithm');
            $table->string('algorithm')->nullable();
            $table->unsignedInteger('key_size')->nullable();
            $table->string('primitive')->nullable();
            $table->boolean('quantum_safe')->default(false);
            $table->string('provider')->nullable();
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cbom_assets');
    }
};
