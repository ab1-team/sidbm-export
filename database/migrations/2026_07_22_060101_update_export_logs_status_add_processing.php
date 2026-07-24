<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('export_logs', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'processing',
                'success',
                'failed'
            ])->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('export_logs', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'success',
                'failed'
            ])->default('pending')->change();
        });
    }
};