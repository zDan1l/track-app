<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('location_city', 100);
            $table->string('location_district', 100);
            $table->string('location_village', 100);
            $table->date('work_date');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->text('activity_details');
            $table->string('site_pic', 100);
            $table->enum('status', ['Daily', 'Final'])->default('Daily');
            $table->string('bast_scan_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status', 'work_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
