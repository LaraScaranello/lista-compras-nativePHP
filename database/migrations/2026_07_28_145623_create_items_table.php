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
        Schema::create('items', static function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->integer('quantidade')->default(1);
            $table->decimal('preco', 8, 2)->nullable();
            $table->boolean('comprado')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
