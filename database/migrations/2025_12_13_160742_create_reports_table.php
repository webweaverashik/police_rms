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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parliament_seat_id')->constrained();
            $table->foreignId('upazila_id')->constrained();
            $table->foreignId('zone_id')->constrained();
            $table->foreignId('union_id')->constrained();
            $table->string('location_name');
            $table->foreignId('political_party_id')->constrained();
            $table->string('candidate_name')->nullable();
            $table->foreignId('program_type_id')->constrained();
            $table->dateTime('program_date_time');
            $table->string('program_special_guest')->nullable();
            $table->string('program_chair')->nullable();
            $table->integer('tentative_attendee_count')->nullable();
            $table->enum('program_status', ['done', 'ongoing', 'upcoming']);
            $table->integer('final_attendee_count')->nullable();
            $table->text('program_title');
            $table->text('program_description');
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
