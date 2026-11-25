<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('licences', function (Blueprint $table) {
            $table->id();
            $table->string('license_key', 64)->unique();
            $table->string('email', 190)->default('');
            $table->unsignedInteger('site_limit')->default(1); // 0 = unlimited
            $table->dateTime('expires')->nullable();
            $table->string('status', 20)->default('active');
            $table->string('order_ref', 64)->default('')->index();
            $table->timestamps();
        });

        Schema::create('activations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('licence_id')->constrained('licences')->cascadeOnDelete();
            $table->string('site', 190);
            $table->timestamps();
            $table->unique(['licence_id', 'site']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activations');
        Schema::dropIfExists('licences');
    }
};
