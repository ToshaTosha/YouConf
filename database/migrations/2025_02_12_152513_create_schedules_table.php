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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('performance_id')->constrained()->onDelete('cascade'); // Связь с заявкой
            $table->foreignId('section_id')->nullable()->constrained()->onDelete('set null');
            $table->date('date')->nullable(); // Добавляем поле date
            $table->time('start_time')->nullable(); // Добавляем поле start_time
            $table->integer('duration')->nullable()->default(15); // Добавляем поле duration
            $table->time('end_time')->nullable(); // Добавляем поле end_time
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null');
            $table->string('event_type')->default('performance');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
