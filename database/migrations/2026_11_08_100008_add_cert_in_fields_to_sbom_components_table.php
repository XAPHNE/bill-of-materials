<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sbom_components', function (Blueprint $table) {
            $table->text('description')->nullable()->after('supplier');
            $table->string('origin')->nullable()->after('license');
            $table->text('dependencies')->nullable()->after('origin');
            $table->string('patch_status')->nullable()->after('dependencies');
            $table->date('release_date')->nullable()->after('patch_status');
            $table->date('end_of_life')->nullable()->after('release_date');
            $table->string('criticality')->nullable()->after('end_of_life');
            $table->text('usage_restrictions')->nullable()->after('criticality');
            $table->string('author')->nullable()->after('usage_restrictions');
            $table->boolean('is_executable')->default(false)->after('author');
            $table->boolean('is_archive')->default(false)->after('is_executable');
            $table->boolean('is_structured')->default(false)->after('is_archive');
        });
    }

    public function down(): void
    {
        Schema::table('sbom_components', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'origin',
                'dependencies',
                'patch_status',
                'release_date',
                'end_of_life',
                'criticality',
                'usage_restrictions',
                'author',
                'is_executable',
                'is_archive',
                'is_structured',
            ]);
        });
    }
};
