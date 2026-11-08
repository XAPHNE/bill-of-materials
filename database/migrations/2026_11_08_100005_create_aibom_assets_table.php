<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aibom_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bom_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('model_type')->default('llm');
            $table->string('provider')->nullable();
            $table->string('version')->nullable();
            $table->string('license')->nullable();
            $table->string('dataset')->nullable();
            $table->string('purpose')->nullable();
            $table->string('risk_classification')->default('limited');
            $table->text('evaluation')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aibom_assets');
    }
};
