<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table): void {
            $table->string('day_of_week')->nullable()->after('date');
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table): void {
            $table->dropColumn('day_of_week');
        });
    }
};
