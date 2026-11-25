<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('assigned_to')->nullable();
        });
        Schema::table('ticket_replies', function (Blueprint $table) {
            $table->boolean('is_note')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('tickets', fn (Blueprint $t) => $t->dropColumn('assigned_to'));
        Schema::table('ticket_replies', fn (Blueprint $t) => $t->dropColumn('is_note'));
    }
};
