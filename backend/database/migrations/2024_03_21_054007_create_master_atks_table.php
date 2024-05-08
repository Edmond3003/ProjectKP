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
        Schema::create('master_atks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->unsignedBigInteger('id_kelompok'); // Menambah kolom id_kelompok dengan tipe bigint
            $table->foreign('id_kelompok')->references('id')->on('kelompoks'); // Menjadikan id_kelompok sebagai foreign key dari tabel kelompoks
            $table->integer('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_atks');
    }
};
