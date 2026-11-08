<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qbom_assets', function (Blueprint $table) {
            $table->string('primitive')->nullable()->after('asset_type');
            $table->string('mode')->nullable()->after('primitive');
            $table->text('crypto_functions')->nullable()->after('mode');
            $table->string('classical_security_level')->nullable()->after('crypto_functions');
            $table->string('oid')->nullable()->after('classical_security_level');
            $table->text('algorithm_list')->nullable()->after('oid');
            $table->string('key_identifier')->nullable()->after('algorithm_list');
            $table->string('key_state')->nullable()->after('key_identifier');
            $table->integer('key_size')->nullable()->after('key_state');
            $table->date('key_created_at')->nullable()->after('key_size');
            $table->date('key_activated_at')->nullable()->after('key_created_at');
            $table->string('protocol_version')->nullable()->after('key_activated_at');
            $table->text('cipher_suites')->nullable()->after('protocol_version');
            $table->string('subject_name')->nullable()->after('cipher_suites');
            $table->string('issuer_name')->nullable()->after('subject_name');
            $table->date('not_valid_before')->nullable()->after('issuer_name');
            $table->date('not_valid_after')->nullable()->after('not_valid_before');
            $table->string('signature_algorithm')->nullable()->after('not_valid_after');
            $table->string('public_key_reference')->nullable()->after('signature_algorithm');
            $table->string('certificate_format')->nullable()->after('public_key_reference');
            $table->string('certificate_extension')->nullable()->after('certificate_format');
        });
    }

    public function down(): void
    {
        Schema::table('qbom_assets', function (Blueprint $table) {
            $table->dropColumn([
                'primitive',
                'mode',
                'crypto_functions',
                'classical_security_level',
                'oid',
                'algorithm_list',
                'key_identifier',
                'key_state',
                'key_size',
                'key_created_at',
                'key_activated_at',
                'protocol_version',
                'cipher_suites',
                'subject_name',
                'issuer_name',
                'not_valid_before',
                'not_valid_after',
                'signature_algorithm',
                'public_key_reference',
                'certificate_format',
                'certificate_extension',
            ]);
        });
    }
};
