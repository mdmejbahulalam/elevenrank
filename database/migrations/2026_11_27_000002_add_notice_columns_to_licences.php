<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('licences', function (Blueprint $table) {
            $table->timestamp('notified_30_at')->nullable();
            $table->timestamp('notified_7_at')->nullable();
            $table->timestamp('notified_expired_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('licences', function (Blueprint $table) {
            $table->dropColumn(['notified_30_at', 'notified_7_at', 'notified_expired_at']);
        });
    }
};
