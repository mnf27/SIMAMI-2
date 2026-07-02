<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audits', function (Blueprint $table) {
            $table->foreignId('instrument_template_id')->nullable()->after('periode_id')
                ->constrained('instrument_templates')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('audits', function (Blueprint $table) {
            $table->dropForeign(['instrument_template_id']);
            $table->dropColumn('instrument_template_id');
        });
    }
};
