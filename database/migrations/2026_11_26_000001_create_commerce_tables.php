<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $t) {
            $t->id();
            $t->string('slug', 40)->unique();
            $t->string('name', 80);
            $t->unsignedInteger('sites')->default(1);        // 0 = unlimited
            $t->unsignedInteger('price_cents')->default(0);
            $t->string('currency', 3)->default('USD');
            $t->unsignedInteger('months')->default(12);
            $t->boolean('active')->default(true);
            $t->unsignedInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('orders', function (Blueprint $t) {
            $t->id();
            $t->string('number', 24)->unique();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('plan_id')->constrained();
            $t->unsignedInteger('amount_cents')->default(0);
            $t->string('currency', 3)->default('USD');
            $t->string('status', 20)->default('pending')->index(); // pending, paid, refunded, cancelled
            $t->string('provider', 30)->default('manual');
            $t->string('provider_ref', 80)->default('')->index();
            $t->unsignedBigInteger('licence_id')->nullable()->index();
            $t->timestamp('paid_at')->nullable();
            $t->timestamp('refunded_at')->nullable();
            $t->text('note')->nullable();
            $t->timestamps();
        });

        Schema::create('tickets', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('subject', 160);
            $t->string('status', 20)->default('open')->index(); // open, answered, closed
            $t->timestamps();
        });

        Schema::create('ticket_replies', function (Blueprint $t) {
            $t->id();
            $t->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->boolean('is_staff')->default(false);
            $t->text('body');
            $t->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('user_id')->nullable();
            $t->string('action', 80)->index();
            $t->string('subject', 190)->default('');
            $t->timestamps();
        });

        Schema::table('licences', function (Blueprint $t) {
            $t->unsignedBigInteger('user_id')->nullable()->index();
            $t->unsignedBigInteger('plan_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('licences', fn (Blueprint $t) => $t->dropColumn(['user_id', 'plan_id']));
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('ticket_replies');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('plans');
    }
};
