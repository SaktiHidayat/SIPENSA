<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('spks', 'batch_id')) {
            Schema::table('spks', function ($table): void {
                $table->uuid('batch_id')->nullable()->after('user_id')->index();
            });
        }

        $groups = DB::table('spks')
            ->select('user_id', 'tanggal_spk')
            ->whereNull('batch_id')
            ->groupBy('user_id', 'tanggal_spk')
            ->get();

        foreach ($groups as $group) {
            DB::table('spks')
                ->where('user_id', $group->user_id)
                ->whereDate('tanggal_spk', $group->tanggal_spk)
                ->whereNull('batch_id')
                ->update(['batch_id' => (string) Str::uuid()]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('spks', 'batch_id')) {
            Schema::table('spks', function ($table): void {
                $table->dropColumn('batch_id');
            });
        }
    }
};