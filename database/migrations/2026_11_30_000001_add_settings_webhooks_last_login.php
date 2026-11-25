<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->string('key', 80)->primary();
            $table->text('value')->nullable();
        });
        Schema::create('webhook_events', function (Blueprint $table) {
            $table->id();
            $table->string('provider', 40)->default('paddle');
            $table->string('event', 80);
            $table->longText('payload');
            $table->unsignedSmallInteger('attempts')->default(0);
            $table->string('status', 20)->default('processed'); // processed | failed
            $table->text('error')->nullable();
            $table->timestamps();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_login_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('webhook_events');
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn('last_login_at'));
    }
};
