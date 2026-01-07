<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evidence_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
            $table->enum('category', ['on_site', 'work_area', 'work_proof', 'other']);
            $table->string('file_path');
            $table->string('original_name');
            $table->timestamps();

            $table->index(['work_order_id', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evidence_photos');
    }
};
