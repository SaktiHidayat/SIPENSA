<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('spk_proses_hasils');
        Schema::dropIfExists('spk_proses');
    }

    public function down(): void
    {
        //
    }
};
