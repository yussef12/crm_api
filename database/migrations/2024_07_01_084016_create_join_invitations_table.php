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
        Schema::create('join_invitations', function (Blueprint $table) {
            $table->id();
            $table->string('invited_email')->unique();
            $table->string('invited_name');
            $table->string('app_url');
            $table->string('token', 20)->unique();
            $table->enum('status', ['saved', 'sent', 'cancelled','validated'])->default('saved');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('join_invitations');
    }
};
