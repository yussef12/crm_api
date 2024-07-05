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
        Schema::create('event_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('event_name', ['invitation sent', 'invitation cancelled', 'invitation validated', 'employee confirmed']);
            $table->string('triggered_by_name');
            $table->foreignId('triggered_by_id')->nullable()->constrained('users');
            $table->string('invited_employee_name');
            $table->string('triggered_by_role');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_logs');
    }
};
