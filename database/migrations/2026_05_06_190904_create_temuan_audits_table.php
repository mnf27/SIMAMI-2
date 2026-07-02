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
        Schema::create('temuan_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained();

            $table->string('kode_indikator');
            $table->text('temuan');
            $table->text('hasil_ami')->nullable();

            $table->text('tindakan_perbaikan_awal')->nullable();
            $table->string('bukti_link')->nullable();

            $table->text('tanggapan_auditor')->nullable();

            $table->enum('status', ['OPEN', 'CLOSED'])->default('OPEN');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temuan_audits');
    }
};
