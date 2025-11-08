<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // relasi ke tabel users

            // Jenis transaksi: pemasukan atau pengeluaran
            $table->enum('type', ['income', 'expense']);

            // Keterangan atau nama transaksi
            $table->string('title');

            // Jumlah uang (gunakan decimal agar akurat)
            $table->decimal('amount', 15, 2);

            // Deskripsi tambahan opsional
            $table->text('description')->nullable();

            // Gambar bukti transaksi (opsional)
            $table->string('image')->nullable();

            // Tanggal transaksi
            $table->date('transaction_date')->default(now());

            $table->timestamps();

            // Relasi ke user
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Hapus tabel jika rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
