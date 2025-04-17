<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->enum('status', ['open', 'closed', 'on_hold'])
                ->default('open');
            $table->foreignId('client_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tickets');
    }
};
