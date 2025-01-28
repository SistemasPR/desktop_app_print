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
        Schema::create('printer_categories', function (Blueprint $table) {
            $table->id();
            $table->string('printer_ip');
            $table->string('printer_name');
            $table->string('printer_id');
            $table->integer('category_id')->nullable();
            $table->boolean('all_categories')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('printer_categories');
    }
};
