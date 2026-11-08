<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hbom_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bom_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('manufacturer')->nullable();
            $table->string('model_number')->nullable();
            $table->string('part_number')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->string('category')->default('other');
            $table->string('firmware_version')->nullable();
            $table->string('country_of_origin')->nullable();
            $table->date('end_of_life')->nullable();
            $table->string('criticality')->default('medium');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hbom_components');
    }
};
