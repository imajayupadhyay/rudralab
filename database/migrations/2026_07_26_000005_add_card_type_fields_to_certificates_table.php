<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table): void {
            $table->string('card_type', 20)->default('type_1')->index()->after('extra_fields');
            $table->string('reference_code', 80)->nullable()->after('card_type');
            $table->string('issue_location', 120)->nullable()->after('reference_code');
            $table->string('particulars', 160)->nullable()->after('issue_location');
            $table->string('natural_faces', 80)->nullable()->after('particulars');
            $table->string('artificial_faces', 80)->nullable()->after('natural_faces');
            $table->string('test_carried_out', 255)->nullable()->after('artificial_faces');
            $table->text('xray_results')->nullable()->after('test_carried_out');
            $table->text('conclusions')->nullable()->after('xray_results');
            $table->string('genus_type', 180)->nullable()->after('conclusions');
            $table->string('certificate_title', 180)->nullable()->after('genus_type');
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table): void {
            $table->dropColumn([
                'card_type',
                'reference_code',
                'issue_location',
                'particulars',
                'natural_faces',
                'artificial_faces',
                'test_carried_out',
                'xray_results',
                'conclusions',
                'genus_type',
                'certificate_title',
            ]);
        });
    }
};
