<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('vendor')->nullable();
            $table->string('version')->nullable();
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('boms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type')->index();
            $table->string('name');
            $table->string('version')->default('1.0');
            $table->string('specification')->nullable();
            $table->string('status')->default('draft');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->date('released_at')->nullable();
            $table->timestamps();

            $table->unique(['product_id', 'type', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boms');
        Schema::dropIfExists('products');
    }
};
