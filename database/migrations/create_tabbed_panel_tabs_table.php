<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tabbed_panel_tabs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('tenant_id')->nullable();
            $table->string('tab_key', 128);
            $table->json('tab_data');
            $table->unsignedInteger('tab_order');
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'tenant_id', 'tab_key'], 'uq_tab');
            $table->index(['user_id', 'tenant_id'], 'idx_user_tenant');
            $table->index(['user_id', 'tenant_id', 'tab_order'], 'idx_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tabbed_panel_tabs');
    }
};
