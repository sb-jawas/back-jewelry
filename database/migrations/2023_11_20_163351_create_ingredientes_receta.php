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
        Schema::create('ingredientes_receta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('componente_id');
            $table->foreign('componente_id')->references('id')->on('componentes')->onDelete('cascade');
            $table->unsignedBigInteger('joya_id');
            $table->foreign('joya_id')->references('id')->on('joyas')->onDelete('cascade');
            $table->string('cantidad');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredientes_receta');
    }
};
