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
        Schema::create('seat_party_candidates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('parliament_seat_id')->constrained()->cascadeOnDelete();
            $table->foreignId('political_party_id')->constrained()->cascadeOnDelete();

            $table->string('candidate_name');
            $table->string('candidate_age')->nullable();
            $table->string('candidate_address')->nullable();
            $table->string('political_background')->nullable();
            $table->string('election_symbol')->nullable();

            $table->unique(['parliament_seat_id', 'political_party_id'], 'seat_party_unique');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seat_party_candidates');
    }
};
