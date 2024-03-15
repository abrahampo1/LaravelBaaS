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
        Schema::create('columns', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            //add name
            $table->string('name');
            //add type
            $table->string('type');
            //add table relation
            $table->foreignId('table_id');

            //add table to relate
            $table->string("reference_table")->nullable();
            //add column to relate
            $table->string("reference_column")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('columns');
    }
};
