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
        Schema::create('header_penjualans', function (Blueprint $table) {
            $table->id();
            $table->date('tgl');
            $table->unsignedBigInteger('id_cabang');
            $table->foreign('id_cabang')->references('id')->on('cabangs');
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('header_penjualans');
    }
};
