<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sbom_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bom_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('version')->nullable();
            $table->string('supplier')->nullable();
            $table->string('component_type')->default('library');
            $table->string('scope')->default('required');
            $table->string('license')->nullable();
            $table->string('purl')->nullable();
            $table->string('cpe')->nullable();
            $table->string('hash_algorithm')->nullable();
            $table->string('hash_value')->nullable();
            $table->unsignedInteger('vulnerabilities_count')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sbom_components');
    }
};
