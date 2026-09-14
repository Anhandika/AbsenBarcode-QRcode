<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('attendance_token_id')->constrained()->cascadeOnDelete();
            $table->date('attendance_date');
            $table->timestamp('scanned_at');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->decimal('accuracy_meters', 8, 2)->nullable();
            $table->decimal('distance_meters', 10, 2);
            $table->string('result')->default('success');
            $table->string('failure_reason')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'attendance_date']);
            $table->index(['attendance_date', 'result']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
