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
        Schema::create('errors', function (Blueprint $table) {
            $table->id();
            $table->date('error_date');
            $table->date('report_date');
            $table->string('patient_name');
            $table->date('date_of_birth');
            $table->string('area');
            $table->string('folder');
            $table->text('description');
            $table->foreignId('node_id')->constrained();
            $table->foreignId('place_id')->constrained()->cascadeOnDelete();
            $table->foreignId('staff_involved_id')->constrained()->cascadeOnDelete();
            $table->foreignId('factor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('error_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('errors');
    }
};
