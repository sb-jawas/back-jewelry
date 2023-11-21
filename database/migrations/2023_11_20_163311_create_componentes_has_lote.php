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
        Schema::create('componentes_has_lote', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');
            $table->unsignedBigInteger('lote_id');
            $table->foreign('lote_id')->references('id')->on('lote')->onDelete('cascade');
            $table->unsignedBigInteger('componentes_id');
            $table->foreign('componentes_id')->references('id')->on('componentes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('componentes_has_lote');
    }
};
