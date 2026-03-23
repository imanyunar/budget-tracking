<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('investments', function (Blueprint $table) {
            $table->string('asset_type', 10)->default('stock')->after('user_id'); // 'stock' | 'crypto'
            $table->decimal('quantity', 20, 8)->default(0)->after('lots');        // unit crypto (bisa desimal)
        });
    }

    public function down(): void
    {
        Schema::table('investments', function (Blueprint $table) {
            $table->dropColumn(['asset_type', 'quantity']);
        });
    }
};
