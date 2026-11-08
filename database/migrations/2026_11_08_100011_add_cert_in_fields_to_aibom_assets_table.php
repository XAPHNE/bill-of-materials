<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aibom_assets', function (Blueprint $table) {
            $table->text('software_dependencies')->nullable()->after('license');
            $table->text('algorithms')->nullable()->after('software_dependencies');
            $table->text('performance_metrics')->nullable()->after('algorithms');
            $table->string('data_source')->nullable()->after('performance_metrics');
            $table->text('hardware')->nullable()->after('dataset');
            $table->text('security_requirements')->nullable()->after('hardware');
            $table->string('model_input')->nullable()->after('security_requirements');
            $table->string('model_output')->nullable()->after('model_input');
            $table->text('out_of_scope_usage')->nullable()->after('purpose');
            $table->text('environmental_impact')->nullable()->after('out_of_scope_usage');
            $table->text('vulnerabilities')->nullable()->after('evaluation');
            $table->text('attestations')->nullable()->after('vulnerabilities');
        });
    }

    public function down(): void
    {
        Schema::table('aibom_assets', function (Blueprint $table) {
            $table->dropColumn([
                'software_dependencies',
                'algorithms',
                'performance_metrics',
                'data_source',
                'hardware',
                'security_requirements',
                'model_input',
                'model_output',
                'out_of_scope_usage',
                'environmental_impact',
                'vulnerabilities',
                'attestations',
            ]);
        });
    }
};
