<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create the settings table
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default settings directly
        DB::table('settings')->insert([
            // --- Basic Shop Info ---
            ['key' => 'shop_name', 'value' => 'POS', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'shop_address', 'value' => 'Silicon Valley, CA', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'shop_phone', 'value' => '+1 555 123 4567', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'currency_symbol', 'value' => '$', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'tax_percentage', 'value' => '5', 'created_at' => now(), 'updated_at' => now()],

            // --- Branding Assets ---
            ['key' => 'site_logo', 'value' => 'assets/images/logo.png', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_favicon', 'value' => 'assets/images/favicon.ico', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_banner', 'value' => 'assets/images/promo-banner.jpg', 'created_at' => now(), 'updated_at' => now()],
            
            // --- SEO Metadata ---
            ['key' => 'meta_title', 'value' => 'Antigravity POS - Next Gen Point of Sale', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'meta_description', 'value' => 'The fastest, cloud-based POS system for modern retail and restaurants.', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'meta_keywords', 'value' => 'pos, point of sale, retail software, inventory management', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'og_image', 'value' => 'assets/images/og-share.jpg', 'created_at' => now(), 'updated_at' => now()],
            
            // --- Social & Contact ---
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/antigravitypos', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'contact_email', 'value' => 'support@antigravity.io', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};