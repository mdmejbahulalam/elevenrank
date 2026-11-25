<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

// PLACEHOLDER PRICES: edit them in Staff > Plans before launch.
return new class extends Migration {
    public function up(): void
    {
        $now = now();
        foreach ([['personal', 'Personal', 1, 4900, 1], ['agency', 'Agency', 3, 9900, 2], ['studio', 'Studio', 10, 19900, 3]] as [$slug, $name, $sites, $price, $sort]) {
            DB::table('plans')->insertOrIgnore(['slug' => $slug, 'name' => $name, 'sites' => $sites, 'price_cents' => $price, 'currency' => 'USD', 'months' => 12, 'active' => true, 'sort' => $sort, 'created_at' => $now, 'updated_at' => $now]);
        }
    }

    public function down(): void
    {
        DB::table('plans')->whereIn('slug', ['personal', 'agency', 'studio'])->delete();
    }
};
