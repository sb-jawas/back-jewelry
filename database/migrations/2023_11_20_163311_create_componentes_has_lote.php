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
            $table->string('observation')->nullable();
            $table->unsignedBigInteger('clasificador_id');
            $table->foreign('clasificador_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('lote_id');
            $table->foreign('lote_id')->references('id')->on('lote')->onDelete('cascade');
            $table->unsignedBigInteger('componente_id');
            $table->foreign('componente_id')->references('id')->on('componentes')->onDelete('cascade');
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
