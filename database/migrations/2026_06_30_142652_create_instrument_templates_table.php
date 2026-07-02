<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instrument_templates', function (Blueprint $table) {
            $table->id();
            $table->string('nama_file');
            $table->string('path');
            $table->string('versi')->nullable();
            $table->boolean('is_active')->default(false);
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instrument_templates');
    }
};
