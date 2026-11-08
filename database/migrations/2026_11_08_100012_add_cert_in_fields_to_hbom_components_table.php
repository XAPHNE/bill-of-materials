<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hbom_components', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('bom_id')->constrained('hbom_components')->nullOnDelete();
            $table->string('product_version')->nullable()->after('name');
            $table->text('product_details')->nullable()->after('product_version');
            $table->string('warranty')->nullable()->after('product_details');
            $table->string('manufacturer_location')->nullable()->after('manufacturer');
            $table->date('manufacturing_date')->nullable()->after('manufacturer_location');
            $table->string('supplier_name')->nullable()->after('manufacturing_date');
            $table->string('supplier_location')->nullable()->after('supplier_name');
            $table->string('serial_number')->nullable()->after('supplier_location');
            $table->text('technical_specification')->nullable()->after('serial_number');
            $table->string('technology_node')->nullable()->after('technical_specification');
            $table->text('compliance')->nullable()->after('technology_node');
            $table->string('power_supply')->nullable()->after('compliance');
            $table->string('license')->nullable()->after('power_supply');
            $table->text('test_result')->nullable()->after('license');
            $table->text('vulnerabilities')->nullable()->after('test_result');
        });
    }

    public function down(): void
    {
        Schema::table('hbom_components', function (Blueprint $table) {
            $table->dropConstrainedForeignId('parent_id');
            $table->dropColumn([
                'product_version',
                'product_details',
                'warranty',
                'manufacturer_location',
                'manufacturing_date',
                'supplier_name',
                'supplier_location',
                'serial_number',
                'technical_specification',
                'technology_node',
                'compliance',
                'power_supply',
                'license',
                'test_result',
                'vulnerabilities',
            ]);
        });
    }
};
