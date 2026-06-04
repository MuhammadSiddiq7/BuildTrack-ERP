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
        Schema::table('item_demand_item', function (Blueprint $table) {
            $table->text('comments')->nullable()->after('over_description'); // Yahaan 'after' specify kar sakte hain ke column kahan aayega.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_demand_item', function (Blueprint $table) {
            $table->dropColumn('comments');
        });
    }
};
