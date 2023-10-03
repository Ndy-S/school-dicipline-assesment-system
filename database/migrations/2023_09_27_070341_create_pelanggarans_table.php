<?php

use App\Models\Siswa;
use App\Models\Guru;
use App\Models\SOP;
use App\Models\MataPelajaran;
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
        Schema::create('pelanggarans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Siswa::class)->nullOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(SOP::class)->nullOnDelete()->cascadeOnUpdate();
            $table->text('deskripsi');
            $table->string('sanksi');
            $table->foreignIdFor(Guru::class)->nullOnDelete()->cascadeOnUpdate();
            $table->enum('jenis', ['kelas', 'sekolah']);
            $table->foreignIdFor(MataPelajaran::class)->cascadeOnUpdate();
            $table->string('bukti_path')->default('none.webp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggarans');
    }
};
