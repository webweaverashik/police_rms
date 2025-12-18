<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('upazila_id')->constrained();
            $table->foreignId('zone_id')->constrained();
            $table->foreignId('union_id')->constrained();
            $table->string('location_name')->nullable();
            
            $table->foreignId('parliament_seat_id')->constrained();
            $table->foreignId('political_party_id')->constrained();
            $table->string('candidate_name')->nullable();
            $table->foreignId('program_type_id')->nullable()->constrained()->nullOnDelete();

            $table->date('program_date')->nullable();
            $table->time('program_time')->nullable();

            $table->string('program_special_guest')->nullable();
            $table->string('program_chair')->nullable();
            $table->integer('tentative_attendee_count')->nullable();

            $table->enum('program_status', ['upcoming', 'ongoing', 'done']);

            $table->text('program_title')->nullable();
            $table->text('program_description')->nullable();

            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->softDeletes();
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
