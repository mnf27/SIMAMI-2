<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('audits', function (Blueprint $table) {
            $table->foreignId('auditor_1_id')
                ->nullable()
                ->after('wakil_auditi_id')
                ->constrained('users');

            $table->foreignId('auditor_2_id')
                ->nullable()
                ->after('auditor_1_id')
                ->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audits', function (Blueprint $table) {
            $table->dropForeign(['auditor_1_id']);
            $table->dropForeign(['auditor_2_id']);

            $table->dropColumn([
                'auditor_1_id',
                'auditor_2_id'
            ]);
        });
    }
};
