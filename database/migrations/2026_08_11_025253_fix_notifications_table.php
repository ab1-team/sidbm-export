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
        Schema::table('notifications', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('notifications', 'notifiable_type')) {
                $columns[] = 'notifiable_type';
            }
            if (Schema::hasColumn('notifications', 'notifiable_id')) {
                $columns[] = 'notifiable_id';
            }
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
