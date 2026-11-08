<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qbom_assets', function (Blueprint $table) {
            $table->string('version')->nullable()->after('name');
            $table->string('vendor')->nullable()->after('version');
            $table->string('origin')->nullable()->after('vendor');
            $table->string('license')->nullable()->after('origin');
            $table->string('communication_protocol')->nullable()->after('license');
            $table->text('hardware')->nullable()->after('communication_protocol');
            $table->text('software_dependencies')->nullable()->after('hardware');
            $table->text('environmental_impact')->nullable()->after('software_dependencies');
            $table->text('vulnerabilities')->nullable()->after('environmental_impact');
            $table->text('attestations')->nullable()->after('vulnerabilities');
        });
    }

    public function down(): void
    {
        Schema::table('qbom_assets', function (Blueprint $table) {
            $table->dropColumn([
                'version',
                'vendor',
                'origin',
                'license',
                'communication_protocol',
                'hardware',
                'software_dependencies',
                'environmental_impact',
                'vulnerabilities',
                'attestations',
            ]);
        });
    }
};
